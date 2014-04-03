<?php

class GframeMainClass {	
	// routines
	
	public function printAccessedPage ($requested_URI) {
		$requested_URI = self::returnPageNameFromPath($requested_URI);

		if($requested_URI == 'index.php') {
			$requested_URI = 'home';
		} else if($requested_URI !== '') {
			$requested_URI = $requested_URI;
		} else {
			$requested_URI = 'home';
		}
		
		self::loadPage ($requested_URI);
		
		return;
	}
	
	public function loadPage ($URI) {
		if(file_exists('pages/'.$URI.'.php')) {
			include_once('pages/'.$URI.'.php');
		} else {
			echo 'Not found : '.$URI;
		}
	}
	
	/* it only returns state code by analyzing values stored in $_SERVER */
	
	public function userAccessState () {
		$logged_in = (array_key_exists('logged_in', $_SESSION)) ? $_SESSION['logged_in'] : NULL;
		$result_code = 0;
		if ( $logged_in == 'TRUE' ) {
			$result_code = 1;
		} else if (  $logged_in == 'FALSE' || is_null($logged_in) ) {
			$result_code = 0;
		} else {
			$result_code = -1;
		}
		
		/*
			status code
			
			0: not logged in
			1: logged in
			-1: exceptions
			
		*/
		
		return $result_code;
	}
	
	// public functions ~ lower level
	public function returnPageNameFromPath ($URI) {
		$php_self = str_replace('index.php','',$_SERVER['PHP_SELF']);
		$URI = parse_url(str_replace( $php_self,'',$URI ));
		$URI = basename($URI['path']);
		$URI = preg_replace( '/[^A-Za-z0-9.#\\-$]/','',$URI );
		$URI = mb_substr($URI,0,64);

		return $URI;
	}
}