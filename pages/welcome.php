<?php
	global $db_info;
	$gframeVefiryKey = new GframeVefiryKey($db_info);
	$verificationResult = $gframeVefiryKey->verifyKey($_GET['key']);
?>
<div class="contentBody white">
	<?php if($verificationResult === 0) : ?>
	<script type="text/javascript">
		$(function(){
			"use strict";
	
			$('div#userLogin').after(_.template($("#template-welcomeTip").html()) );
			
			$('#welcomeTip').showBalloon({
				tipSize: 24,
				position: "bottom",
				css: {
				border: 'solid 4px #5baec0',
				padding: '10px',
				fontSize: '150%',
				fontWeight: 'bold',
				lineHeight: '3',
				backgroundColor: '#666',
				color: '#fff'}
			});
			
			$('#userAccess').on( 'click', function () {
				$('#welcomeTip').fadeOut('slow');
			});
		});
	</script>
	<script id="template-welcomeTip" type="text/template">
	    <div id="welcomeTip" class="arrow_top boxShadow">
			<p>Please login with your email address you used for signing up and the password included in the verification email.</p>
	    </div>
	</script>
	<h1 class="color_green">Welcome to Givling</h1>
	<p>Thank you for signing up. Please login with your email address you used for signing up and the password included in the verification email.</p>
	<?php elseif($verificationResult === 1) : ?>
	<h1 class="color_green">You have already activated your account with the verification key.</h1>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php elseif($verificationResult === 2) : ?>
	<h1 class="color_green">The verification key is invalid.</h1>
	<p>Please make sure that you have accessed to this page with the full URL sent with the verification email. If you have copied and pasted from the email into the address bar, please make sure that you have copied a full URL without any missing letters.</p>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php else : ?>
	<h1 class="color_green">Something totally wrong occurred.</h1>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php endif; ?>
</div>
