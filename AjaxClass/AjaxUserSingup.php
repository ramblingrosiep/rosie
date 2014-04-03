<?php
/**
 * Database routines for the G-Frame. Use this class as instance. Set database connection details upon instantiation.
 *
 * @package G-Frame
 * @require GframeDatabase.php (class)
 * @author Shu Miyao
 * @since PHP 5.3
 * @version 1.0
 */
 
class AjaxUserSingup extends GframeDatabase {
	/*
		variables
	*/
	private $gframeDatabase;
	
	/*
		constructor
	*/

	public function __construct( $db_info ) {
		if($db_info) $this->db_info = $db_info;
	}

	/*
		main routines
	*/
	
	/**
	 * Checks up for e mail address duplicate in the db.
	 *
	 * @param $_email string, $db_info needs to be set with db connection details upon instanciation of this class.
	 * @return boolean or string True when successful. String with details returned in case of errors. And json data with results.
	 * no duplicate email address (OK): true with json null, Duplicates detected: false with 
	 */
	function userSignUp ( $_email ) {
		$_sql_statement = '';
		$_bind_parameters = array ();
		$_duplicateEmailCheck;
		$_createUserInfo;
		$_resultVerification ;
		$_emailVerificationKey = '';
		$_accountPassword = '';
		
		$this->gframeDatabase = new GframeDatabase($this->db_info);
		
		$_query_request = array();
		$_query_result = array();

		if ( ($connection_result = $this->gframeDatabase->connect_database ()) !== TRUE ) {
			return $this->gframeDatabase->returnAjaxResponseArray(false,'Failed @ connect_database',$connection_result);
		} else {
			// duplicate email check
			$_duplicateEmailCheck = $this->duplicateEmailCheck( $_email );
			if ($_duplicateEmailCheck['response']===FALSE) {
				$this->gframeDatabase->close_database_connection();
				return $_duplicateEmailCheck; // error then exit
			}

			// generate account password 
			$_accountPassword = $this->returnGeneratedPassword(8);
			
			// create user record
			$_createUserInfo = $this->createUserInfo( $_email,$_accountPassword );
			
			$_resultVerification = $this->queryResultVerification ($_createUserInfo,'createUserInfo');
			if($_resultVerification['response'] === FALSE) {
				$this->gframeDatabase->close_database_connection();
				return $_resultVerification; // error then exit
			}
			
			// generate e mail verification key
			$_emailVerificationKey = $this->returnGeneratedPassword(32);

			// create user meta and email verification key
			// $_createUserInfo['result']['result'] has the last insert ID
			$_createUserInfoMeta = $this->createUserRecordMeta( $_createUserInfo['result']['result'],$_emailVerificationKey);
			$_resultVerification = $this->queryResultVerification ($_createUserInfoMeta,'createUserRecordMeta');
			if($_resultVerification['response'] === FALSE) {
				$this->gframeDatabase->close_database_connection();
				return $_resultVerification; // error then exit
			}
			
			// closing the connection
			$this->gframeDatabase->close_database_connection();
			
			// send out 
			if($this->sendOutVerificationEmail ( $_email,$_accountPassword,$_emailVerificationKey )) {
				return $this->gframeDatabase->returnAjaxResponseArray(true,'OK',null);
			} else {
				return $this->gframeDatabase->returnAjaxResponseArray(false,'failedAtSendingEmail',null);
			}			
		}
	}
	
	/**
		sub routines
	*/
	public function duplicateEmailCheck ( $_email ) {
		$_sql_statement = 'select `user_email` FROM `user_info` WHERE `user_email` = ?;';
		$_bind_parameters = array (array('type' => 's','data' => $_email));
		$_database_response;
		$_response = false;
		$_message = 'unknown';
		$_email_check_regEx_pattern = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";

		$_email = mb_substr($_email,0,253);
		
		if( $_email === '' ) {
			$_response = false;
			$_message = 'blankEmailField';
		} else if ( !preg_match( $_email_check_regEx_pattern, $_email ) ) {
			$_response = false;
			$_message = 'invalidEmailAddress';
		} else {

			$_database_response = $this->databaseSubmitQueryOnly($_sql_statement,$_bind_parameters);

			if($_database_response['response'] === FALSE ) {
				return $this->gframeDatabase->returnResponseArray(false, 'databaseError',$_database_response);
			} else if ($_database_response['response'] === TRUE && empty($_database_response['result']) ) {
				return $this->gframeDatabase->returnResponseArray(true, 'OK',null);
			} else if ($_database_response['response'] === TRUE && !empty($_database_response['result']) ) {
				return $this->gframeDatabase->returnResponseArray(false, 'duplicateEmailAddress',null);
			}
		}

		return $this->gframeDatabase->returnAjaxResponseArray($_response,$_message,null);
	}

	function createUserInfo ( $_email,$_accountPassword) {
		$_sql_statement = "INSERT INTO `user_info` (user_email,user_password,user_status,time_created) VALUES (?,?,1,now());";
		// $_sql_statement .= "INSERT INTO `user_info` (user_email,user_password,user_status,time_created) VALUES (?,?,1,now());";
		$_bind_parameters = array (
			array('type' => 's','data' => $_email),
			array('type' => 's','data' => $_accountPassword )
		);
		$_database_response;
		$_response = false;
		$_message = 'unknown';
		
		$_database_response = $this->databaseSubmitQueryOnly($_sql_statement,$_bind_parameters,'returnInsertID');

		if($_database_response['response'] === FALSE ) {
			return $this->gframeDatabase->returnResponseArray(false, 'databaseError',$_database_response);
		} else if ($_database_response['response'] === TRUE ) {
			return $this->gframeDatabase->returnResponseArray(true, 'OK',$_database_response);
		}

		return $this->gframeDatabase->returnAjaxResponseArray($_response,$_message,null);
	}
	
	function createUserRecordMeta ( $_userID,$_emailVerificationKey ) {
		$_sql_statement = "INSERT INTO `user_meta` (user_id,email_verification_key,email_verification_date) VALUES (?,?,now());";
		// $_sql_statement .= "INSERT INTO `user_info` (user_email,user_password,user_status,time_created) VALUES (?,?,1,now());";
		$_bind_parameters = array (
			array('type' => 'i','data' => $_userID),
			array('type' => 's','data' => $_emailVerificationKey )
		);
		$_database_response;
		$_response = false;
		$_message = 'unknown';
		
		$_database_response = $this->databaseSubmitQueryOnly($_sql_statement,$_bind_parameters);

		if($_database_response['response'] === FALSE ) {
			return $this->gframeDatabase->returnResponseArray(false, 'databaseError',$_database_response);
		} else if ($_database_response['response'] === TRUE ) {
			return $this->gframeDatabase->returnResponseArray(true, 'OK',$_database_response);
		}

		return $this->gframeDatabase->returnAjaxResponseArray($_response,$_message,null);
	}
	
	function sendOutVerificationEmail ( $_email,$_accountPassword,$_emailVerificationKey ) {
		$_header = EMAIL_HEADER;
		$_subject = WELCOME_MAIL_SUBJECT;
		$_body =  "Thank you for signing up!\n\n";
		$_body .= "Your email address (login id) : ".$_email."\n";
		$_body .= "Password : ".$_accountPassword."\n";
		$_body .= "Please access to the url below for verifying your e mail account.\n";
		$_body .= WELCOME_PAGE_URL."?key={$_emailVerificationKey}\n";
		$_body .= "Thank you!\n";
		mb_language("English");
		mb_internal_encoding("UTF-8");
		
		if(mb_send_mail($_email,$_subject,$_body,$_header)){
		   return true;
		}else{
		   return false;
		}
	}

	
	/* 
		low level routines
	*/
	
	/* database manipulation */
	function databaseSubmitQueryOnly ( $_sql_statement,$_bind_parameters,$_option = '' ) {
		$_query_request = $this->gframeDatabase->submit_database_query( $_sql_statement,$_bind_parameters );
	
		if($_query_request['result'] === FALSE) {
			// something wrong with the query submission (execution)
			return $this->gframeDatabase->returnAjaxResponseArray(false,'Failed @ databaseSubmitQueryOnly',$_query_request);
		} else {
			// fetch results
			if($_option === 'returnInsertID') {
				$_query_result = $this->gframeDatabase->statement->insert_id;
			} else {
				$_query_result = $this->gframeDatabase->fetch_query_results();
			}
	
			$this->gframeDatabase->statement->close();
	
			return array (
				'response'=> true,
				'message'=>(is_null($_query_result)) ? 'No record' : 'Success',
				'result'=>$_query_result
			);
		}
	}
	
	/*
		lower level utility routines
	*/
	
	function returnGeneratedPassword ($_passwordLength) {
		return substr(
			str_shuffle(
				strtolower(sha1(rand().time()."245kngr6poksalmqwytwdihixqFGXYFJJUNT3ZSUAP90EB17VCMZcBHVEord8L"))),0, $_passwordLength);
	}

	function queryResultVerification ( $_queryResult,$_stepID ) {
		if($_queryResult['response'] === FALSE) {
			// something wrong with the query submission (execution)
			return $this->gframeDatabase->returnAjaxResponseArray(false,'Failed @ userSignUp / '.$_stepID,$_queryResult);
		} else {
			return $this->gframeDatabase->returnAjaxResponseArray(
				true,
				'OK',
				null
			); // exit normally
		}
	}
}
