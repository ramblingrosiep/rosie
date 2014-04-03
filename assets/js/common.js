$(function(){
	"use strict";
	
	// sticky footer
	$('html').stickyFooter();
	
	// adjust contentBody wrap height
	$('.contentBody').css('height', ($('div#footer').position().top - 160) +'px');
	
	$('div#footer').append(_.template($("#template-popup").html()) );

	// header logo linked to the home
	$('#gameHeader_logo, #regularHeader_logo').on('click', function () {
		location.href = $('html').attr('data-homepage_url');
	});
	
	$('div.sns_facebook, div.sns_twitter').click( function (e){
		location.href = $(this).attr('data-url');
	});
});
