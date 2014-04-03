<?php 

if(
	USER_ACCESS_STATE == 2
) {
	GframeMainClass::loadPage('home.game');
} else {
	GframeMainClass::loadPage('rpgame');
}


