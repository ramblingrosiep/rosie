(function($) {
	$.fn.defaultText = function(options) {
		var defaults = {
			'tagetClass': '.defaultText',
			'activeCssClassName': 'defaultTextActive',
		}
		
		var setting = $.extend(defaults, options);
		var footerElement = $(setting.footerElement);
		
		$(document).on('focus', setting.tagetClass , function(srcc) {
			if ($(this).val() == $(this)[0].title) {
				$(this).removeClass(setting.activeCssClassName);
				$(this).val("");
			}
		});
		
		$(document).on('blur', setting.tagetClass , function() {
			if ($(this).val() == "") {
				$(this).addClass(setting.activeCssClassName);
				$(this).val($(this)[0].title);
			}
		});
		
		$(setting.tagetClass).blur();
	}
})(jQuery);