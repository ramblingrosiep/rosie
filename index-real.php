<?php

/*
	
	Welcome to The G-Frame, Super Portable PHP Framework
*/

/*
	load config file
*/
	require dirname(__FILE__) . '/config.php';

/*
	class loader ( spl_autoload_register )
	register classes
*/

	require'PhpClass/ClassLoader.php';
	$classLoader = new ClassLoader(array(dirname(__FILE__) . '/ajax', dirname(__FILE__) . '/PhpClass'));
	
/* variables default */
$csrf_token = GframeTokenSystem::getHalfToken();
$loginAuthenticationResult = -1;


/* login authentication */
if (array_key_exists('logOut',$_POST)) {
	GframeTokenSystem::setSessionByKey('user_access_state',-1);
} else if( 
		array_key_exists('userEmail',$_POST) && 
		array_key_exists('userPassword',$_POST) && 
		array_key_exists('csrf_token',$_POST) 
	) {
	if(GframeTokenSystem::verifyToken($_POST['csrf_token'])) {
		// enquire to the db
		$gframeLoginAuthentication = new GframeLoginAuthentication($db_info);
		$loginAuthenticationResult = $gframeLoginAuthentication->loginAuthentication($_POST['userEmail'],$_POST['userPassword']);
		/* -1: not logged in , 0: disabled , 1: waiting for activation, 2: activated (ok) , 9: banned */
		GframeTokenSystem::setSessionByKey('user_access_state',$loginAuthenticationResult);
	} else {
		GframeTokenSystem::discardTokenSession();
		$loginAuthenticationResult = -2;
	}
} else {
	$loginAuthenticationResult = GframeTokenSystem::getSessionByKey('user_access_state');
	if( !$loginAuthenticationResult ) $loginAuthenticationResult = -1;
}

/* CSRF_TOKEN constant is used in
	html.php
*/
define( 'CSRF_TOKEN', $csrf_token );

/* USER_ACCESS_STATE constant is used in
	html.php
	home.php
	
	code 
		-1: not logged in , 0: disabled , 1: waiting for activation, 2: activated (ok) , 9: banned
*/
define( 'USER_ACCESS_STATE', $loginAuthenticationResult );

/* main routine */
GframeMainClass::loadPage('html');



/* 
	Made in Tokyo, Japan. February 2014.
*/