<?php
	/* -1: not logged in , 0: disabled , 1: waiting for activation, 2: activated (ok) , 9: banned */
?>
<div class="contentBody white">
	<?php if(USER_ACCESS_STATE === -1 || USER_ACCESS_STATE === 2) : ?>
	<h1 class="color_orange">Something wrong occurred.</h1>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php elseif(USER_ACCESS_STATE === 1) : ?>
	<h1 class="color_orange">Please activate your account.</h1>
	<p>Please activate your account by following an instructions in a verification mail. If you are not being able to receive the verification mail, your verification mail might be in your spam box.</p>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php elseif(USER_ACCESS_STATE === 9) : ?>
	<h1 class="color_orange">You have been banned.</h1>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php elseif(USER_ACCESS_STATE === 0) : ?>
	<h1 class="color_orange">Your account is currently deactivated.</h1>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php else : ?>
	<h1 class="color_orange">Something totally wrong occurred.</h1>
	<p>If you believe this is an error with us, please contact with our customer support.</p>
	<?php endif; ?>
</div>
