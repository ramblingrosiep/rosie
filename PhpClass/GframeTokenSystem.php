<?php

class GframeTokenSystem {
	/**
	 constructor
	 */
/*
	public function __construct() {
	}
*/
	
	// public functions ~ lower level
	/*
		 Token system
	*/
	
		private function generateToken(){
			$ipad = getenv('REMOTE_ADDR');
			$time = time();
			$rand = mt_rand();
		
			// hash the value
			$ipad = hash( 'sha256', $ipad );
			$time = hash( 'md5', $time );
			$rand = hash( 'md5', $rand );
		
			$no_token = $ipad.$time.$rand;
		
			// generate token
			$token = hash( 'sha256', $no_token );
		
			return $token;
		}
		
		// *****************************************
		// get the half token
		// *****************************************
		public function getHalfToken(){
			session_start();
			
			if( !array_key_exists('original_token', $_SESSION) && !isset($_SESSION['original_token'])) {
				$original_token = self::generateToken();
				$_SESSION['original_token'] = $original_token;
				$_SESSION['half_token'] = substr( $original_token, 10 );
				self::increaseTokenIssueCount();
			} else {
				$original_token = $_SESSION['original_token'];
			}	
			
			$half = substr( $original_token, 0, 10 );
			
			session_write_close();
			
			return $half;
		}
		
		// requires a started session 
		public function increaseTokenIssueCount () {
			$_count = 0;
			// save the original token and that of half into the session
			if (!isset($_SESSION['count'])) {
				$_SESSION['count'] = 0;
			} else {
				$_SESSION['count']++;
			}
			
			$_count = $_SESSION['count'];
			
			return $_count;
		}
		
		public function getSessionByKey ($_key) {			
			$_session_value = '';
			
			session_start();

			if(array_key_exists($_key,$_SESSION)){
				$_session_value = $_SESSION[$_key];
			}

			session_write_close();
			
			return $_session_value;
		}
		
		public function setSessionByKey ($_key,$_value) {			
			$_session_value = '';
			
			session_start();
			
			$_SESSION[$_key] = $_value;
			
			session_write_close();
			
			return;
		}
		
		public function discardTokenSession(){
			session_start();
			if( array_key_exists('original_token', $_SESSION) ) unset($_SESSION['original_token']);
			if( array_key_exists('half_token', $_SESSION) ) unset($_SESSION['half_token']);
			if( array_key_exists('user_access_state', $_SESSION) ) unset($_SESSION['user_access_state']);
			session_write_close();
			return ;
		}
		
		// *****************************************
		// verification of the token
		// *****************************************
		public function verifyToken( $half_token ){
			session_start();
			
			if( array_key_exists('original_token',$_SESSION) ) {
				// get the token to be verified
				$ch_token = $_SESSION['original_token'];
			
				// concatenate the token in the server and the one submitted from the ajax
				$token = $half_token.$_SESSION['half_token'];
				
				session_write_close();

				// verification
				if( strcmp( $ch_token, $token ) === 0 ){
					return true;
				}
			}
			
			session_write_close();
			
			return false;
		}
		
		// for debug
		public function showToken( $half_token ){
			session_start();
			
			if( array_key_exists('original_token',$_SESSION) ) {
				$ch_token = $_SESSION['original_token'];
				if(isset($half_token)) $token = $half_token.$_SESSION['half_token'];
				var_dump($ch_token);
				if(isset($half_token)) var_dump($half_token);
				var_dump($_SESSION['half_token']);
				if(isset($token)) var_dump($token);
			}
			
			session_write_close();
			
		}
		
		// for debug
		function returnToken_json (){
			session_start();
		
			if( array_key_exists('original_token',$_SESSION) ) {		
				return array ('response'=>false,'message'=>'original token : '. $_SESSION['original_token'].' half token : '.$_SESSION['half_token']);
			}
			
			session_write_close();
		}
}