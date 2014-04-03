<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
	$homepage_URL = 'http://'.str_replace('index.php','',$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
	$identifier_URL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="identifier_URL_home" content="<?php echo $homepage_URL; ?>">
    <meta name="identifier-URL" content="<?php echo $identifier_URL; ?>">
    <meta name="csrf-token" content="<?php echo CSRF_TOKEN; ?>" />
    <meta name="user-access-state" content="<?php echo USER_ACCESS_STATE; ?>" />

    <title>Givling</title>
    <!-- Web Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Balthazar' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <!-- CSS -->
    <link rel="STYLESHEET" href="assets/css/magnific/magnific-popup.css" type="text/css"> 
    <link rel="STYLESHEET" href="assets/css/style.css" type="text/css">
    <link rel="STYLESHEET" href="assets/css/style_rosie.css" type="text/css">
    <link rel="STYLESHEET" href="assets/css/style_shu.css" type="text/css">
    <link rel="STYLESHEET" href="assets/css/style_game.css" type="text/css">
    <?php GframeMainClass::loadPage('html.common_js'); ?>
</head>

<body>
<div id="wrapper">
    
    <?php GframeMainClass::loadPage('html.underscoreTemplate.form'); ?>
	<?php if( USER_ACCESS_STATE === -2 ) GframeMainClass::loadPage('html.loginError'); ?>
	
    	<?php GframeMainClass::loadPage('html.pageHeaderRP'); ?>


        	<?php switch (USER_ACCESS_STATE) {
        		case -2 :
        		case -1 : // not logged in
        			GframeMainClass::printAccessedPage($_SERVER['REQUEST_URI']) ; 
        			break;
				case 2 : // logged in
					GframeMainClass::printAccessedPage($_SERVER['REQUEST_URI']) ; 
        			break;
        		default:
        			GframeMainClass::printAccessedPage('error') ;
        			break;		        
	        }
			?>
 
	<div id="footer" class="">
        <div id="container_footer_notice">
			©2014 Givling, Inc. All Rights Reserved. 
        	<a href="<?php echo $homepage_URL; ?>faq">FAQ</a>
        	<a href="<?php echo $homepage_URL; ?>legal">LEGAL</a>
        	<a href="<?php echo $homepage_URL; ?>contact">CONTACT US</a>
			<div class="sns_twitter" data-url="https://twitter.com/givling" "twitter"></div>

        	<div class="sns_facebook" data-url="<?php echo $homepage_URL; ?>facebook"></div>
        </div>
    </div>

 </div> 

</body>
</html>
