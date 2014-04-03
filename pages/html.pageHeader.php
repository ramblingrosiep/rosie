<?php $pageName = GframeMainClass::returnPageNameFromPath( $_SERVER['REQUEST_URI'] ); ?>
	    	<?php if ( ($pageName == 'home' || $pageName === '' ) && USER_ACCESS_STATE === 2) :?>
				<div id="header" class="extendedBackground gameHeader noSelect">
					<img id="gameHeader_logo" class="no_noSelect" src="assets/images/Givling_gameLogo.png" />
					<div id="userAccess"><!-- login panel / logout button--></div>
				</div>
			<?php else : ?>
				<div id="header" class="extendedBackground regularHeader noSelect">
					<img id="regularHeader_logo" class="no_noSelect" src="assets/images/Givling_logo plus 25percent.png" />
					<div id="userAccess"><!-- login panel / logout button--></div>
				</div>
			<?php endif; ?>

        <div id="header_bottom" class="extendedBackground"></div>