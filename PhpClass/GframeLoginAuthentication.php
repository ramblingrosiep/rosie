<?php

class GframeLoginAuthentication extends GframeDatabase {
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

	// routines
	/**
	 *  login authentication 
	 *
	 * @param $_user_email , $_user_password
	 * @return integer (-1: no match, 0: disabled , 1: waiting for activation, 2: activated (ok) , 9: banned)
	 */
	public function loginAuthentication ( $_post_user_email,$_post_user_password ) {
		// db
		$_userInfo;
		$_userInfo_update; // for updating the last login date
		
		// variables
		$_user_status = '';
		$_user_id = '';
		
		$authenticationCode = -1;
		
		// instantiate the db class
		$this->gframeDatabase = new GframeDatabase ($this->db_info);
		
		$_userInfo = $this->gframeDatabase->databaseGeneralRoutine(
			'SELECT `user_email`,`user_password`,`user_status`,`user_id` FROM `user_info` WHERE `user_email` = ? AND `user_password` = ? LIMIT 1;',
			array(
				array(
					'type'=>'s',
					'data'=>$_post_user_email
				),
				array(
					'type'=>'s',
					'data'=>$_post_user_password
				)
			)
		);
		
		// no match
		if( empty($_userInfo['json']) ) return -1;

		$_user_status = $_userInfo['json'][0]['user_status'];
		$_user_id = $_userInfo['json'][0]['user_id'];
		unset($_userInfo);
		
		if($_user_status === 2) {
			/*
				Update last login
			*/
			$_userInfo_update = $this->gframeDatabase->databaseGeneralRoutine(
				'UPDATE `user_info` SET `last_login` = now() WHERE `user_id` = ? LIMIT 1;',
				array(
					array(
						'type'=>'i',
						'data'=>$_user_id
					)
				)
			);
		}
		
		return $_user_status;
	}
	
	public function verifyKey ($_emailVerificationKey) {
		// database query
		$_verificationResult;
		$_userInfo;
		$_userInfo_update;
		
		// variable
		$_user_id; // int
		$_user_status;
		$_user_email;
	
		$this->gframeDatabase = new GframeDatabase ($this->db_info);
		
		// see if the verification key exists
		$_verificationResult = $this->gframeDatabase->databaseGeneralRoutine(
			'SELECT `email_verification_key`,`user_id` FROM `user_meta` WHERE `email_verification_key` = ? LIMIT 1;',
			array(
				array(
					'type'=>'s',
					'data'=>$_emailVerificationKey
				)
			)
		);
		
		// exception code 3 : exception
		if( $_verificationResult['response'] === FALSE ) {
			return 3; 
		}

		// exception code 2 : verification key not found
		if( empty( $_verificationResult['json']) ) {
			return 2; 
		}
		
		$_user_id = $_verificationResult['json'][0]['user_id'];
		
		unset($_verificationResult);
		
		// get user info
		$_userInfo = $this->gframeDatabase->databaseGeneralRoutine(
			'SELECT `user_email`,`user_id`,`user_status` FROM `user_info` WHERE `user_id` = ? LIMIT 1;',
			array(
				array(
					'type'=>'i',
					'data'=>$_user_id
				)
			)
		);
		
		// exception code 2 : exception. This should not happen. Since only meta exists while the user_info is missing.
		if( empty( $_userInfo['json']) ) {
			return 3;
		}
		
		/*
			note: user_status (0: disabled, 1: waiting for activation, 2: activated, 9: banned)
			We want 1: waiting for activation
		*/
		if( $_userInfo['json'][0]['user_status'] !== 1 ) {
			return 1;
		}
		
		$_user_email = $_userInfo['json'][0]['user_email'];
		unset($_userInfo);

		/*
			Finally update the user_status to 2: activated
		*/
		$_userInfo_update = $this->gframeDatabase->databaseGeneralRoutine(
			'UPDATE `user_info` SET `user_status` = 2 WHERE `user_id` = ? LIMIT 1;',
			array(
				array(
					'type'=>'i',
					'data'=>$_user_id
				)
			)
		);
		
		$this->sendOutConfirmationEmail($_user_email);

		return 0;
	}
}