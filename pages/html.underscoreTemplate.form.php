<!-- forms - widgets -->

<script id="token-test-result-template" type="text/template">
    <div>
	    <p>
	    	<%- response %>
	    </p>
	    <p>
	    	<%- message %>
	    </p>
	     <p>
	     	<%- userName %>
	     </p>
	     <p>
	     	<%- userNickname %>
	     </p>
	     <p>
	     	<%- userProfile %>
	     </p>
    </div>
</script>

<script id="template-form_halfToken" type="text/template">
	<input type="hidden" id="form_halfToken" value="<?php echo GframeTokenSystem::getHalfToken(); ?>" />
</script>

<script id="template-form_userLogin" type="text/template">
	<div id="userLogin">
		<div class="form_widget">
			<form name="form_userLogin" action="<?php echo SITEURL; ?>" method="POST">
				<input type="text" class="defaultText selectAllOnFocus" name="userEmail" id="userEmail" title="Your nickname" value="" />
				<input type="password" class="defaultText selectAllOnFocus" name="userPassword" id="userPassword" title="Your password" value="" />
				<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo CSRF_TOKEN; ?>" />
				<button class="green round login_width medium_size_font">Login</button>
			</form>
		</div>
		<p class="userLogin_signupLink">For lost password, <a href="#popup" class="inline_popup" data-content="form_lostPassword" data-effect="mfp-move-from-top" onclick="return false;">click here</a>. Not registered yet? <a href="#popup" class="inline_popup" data-content="form_userSignup" data-effect="mfp-zoom-out" onclick="return false;">Sign up!</a></p>
		<p class="form_message"></p>
	</div> <! end of userLogin -->
</script>

<script id="template-form_userSignup" type="text/template">
	<div id="userSignup">
		<h2 class="color_green">Sign up for Givling!</h2>
		<div class="form_widget">
			<form name="form_userSignup" action="" onsubmit="return false;" method="POST">
				<div style="margin-bottom:0.3em;">
					<span>
						<input type="text" name="email" id="email" class="wide defaultText selectAllOnFocus" data-parentelmselector="div#userSignup" title="Your email address" value="" />
					</span>
					<span id="">
						
					</span>
				</div>
				<div class="" style="display:block;width:100%;text-align:left;">
					<p class="form_message  " style="width:280px;display:inline-table;text-align:left;margin-top:1em;font-style: italic;">Please enter your e mail address.</p>
					<button class="round narrow green medium_size_font float_right" style="margin-top:1em;width:100px;" disabled>Sign Up</button>
				</div>
			</form>
		</div>
		
		<p class="margin_top_medium">
			Play exciting game .... to play you need to register once. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec magna purus, elementum non tempor non, fermentum eget orci. Praesent varius a massa suscipit accumsan. Nulla tempor tincidunt metus, iaculis bibendum elit. Praesent varius laoreet porta. Ut quis laoreet massa. Cras sed tincidunt nisi, ut sagittis neque. Donec mauris nibh, congue ut eros id, eleifend luctus mi. Vivamus tristique, dui sit amet bibendum porttitor, arcu risus sodales metus, nec semper ligula ipsum quis neque. Praesent interdum lobortis velit et varius.
		</p>
	</div>
</script>

<script id="template-form_logOut" type="text/template">
	<div id="logOut">
		<p class="form_message">
		</p>
		<div class="form_widget">
			<form name="form_userInfo" action="<?php echo SITEURL; ?>" method="POST">
				<input type="hidden" name="logOut" id="logOut" value="logOut" />
				<button class="round narrow orange float_right medium_size_font">Logout</button>
			</form>
		</div>
	</div>
</script>

<script id="template-form_lostPassword" type="text/template">
	<div id="lostPassword">
		<h3 class="color_orange">Resetting your Password</h3>
		<p>
		Please enter your email address you have used for signing up. You will soon receive an e mail message for letting you reset your password.
		</p>
		<p class="form_message margin_top_medium" style="font-style: italic;"></p>
		<div class="form_widget">
			<form name="form_lostPassword" action="" method="POST">
				<input type="text" class="defaultText selectAllOnFocus wide" name="email" id="email" value="" title="Enter your email address." data-parentelmselector="div#lostPassword" />
				<button class="medium_width orange round float_right medium_size_font" onclick="return false;" disabled>Reset Password</button>
			</form>
		</div>
	</div>
</script>

<script id="template-form_errorPrompt" type="text/template">
	<div id="form_errorPrompt">
		<h3 class="errorHeading color_orange"></h3>
		<p class="form_message">
		</p>
	</div>
</script>

<!-- popup window -->

<script id="template-popup" type="text/template">
	<div id="popup" class="white-popup mfp-with-anim mfp-hide roundcorners-popup"></div>
</script>