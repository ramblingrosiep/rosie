 <?php $pageName = GframeMainClass::returnPageNameFromPath( $_SERVER['REQUEST_URI'] ); ?>
	   <?php if ( ($pageName == 'home' || $pageName === '' ) && USER_ACCESS_STATE === 2) :?>


		
			<div id="headcontainer">
				<div style="background-color: white" class="gameHeader noSelect extendedBackground">
					<header class="group">
					<img id="gameHeader_logo" class="no_noSelect" src="assets/images/Givling_gamelogo.png" />
					<div id="userAccess"></div>
					</header>
				</div>
	
		</div>	
		
<?php else : ?>


		<div id="headcontainer">
				<div style="background-color: white" class="regularHeader noSelect extendedBackground">
					<header class="group">
					<img id="regularHeader_logo" class="no_noSelect" src="assets/images/Givling_logo plus 25percent.png" />
					<div id="userAccess"></div>
					</header>
				</div>
	
		</div>
	<?php endif; ?>

		
	  <div id="header_bottom" class="extendedBackground"></div>
		

      	
