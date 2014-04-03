(function($) {
	$.fn.stickyFooter = function(options) {
		var defaults = {
			'footerElement': 'div#footer',
			'modifyBody': true,
		}
		
		var setting = $.extend(defaults,options);
		
		var footerElement = $(setting.footerElement);

		if(setting.modifyBody) {
			$('body').css('margin','0').css('padding','0');
		}
		
		var bodyHeight = $("body").height();  
		var vwptHeight = window.innerHeight;  

		if (vwptHeight > bodyHeight) {
			footerElement.css("position", "absolute").css("bottom", 0);  
		}
	}
})(jQuery);