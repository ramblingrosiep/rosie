/* jQuery */
$(function(){
	"use strict";

});

/* classes */

/* 
	User registration
*/

$(function(){
	"use strict";
	
	// MODEL
	var Model_ajax_result = Backbone.Model.extend({
		defaults: function() {
			return {
				response: false,
				message: 'Default',
				json : ''
			};
		},
		url: 'ajaxTest.php',
		initialize: function() {
		}
	});
	
	
	// VIEW for popup / UI
	var View_userAccess = Backbone.View.extend({
		el: $("#userAccess"),
		template: _.template($("#template-form_userSignup").html()),
		model: Model_ajax_result,
		events: {
			'fillPopup': 'fillPopup',
			'fillUserAccess': 'fillUserAccess',
		},
		initialize: function (options) {
			var classElement = this.$el;
			var classInstance = this;
			
			// variables
			var user_access_state = $("meta[name=user-access-state]").attr("content");
			var userAccess_panel = 'form_userLogin';
			
			this.model = options.model;
			
			// events driven 
			$('div#userAccess, div#popup').on('click', 'input.selectAllOnFocus', function () {
				this.select();
			});
			
			// Sign up button
			$('div#userAccess, div#popup').on('click', 'div#userSignup button', function () {
				classInstance.signUp_submit($(this));
			});
			// sign up text field
			$('div#userAccess, div#popup').on('keyup', 'input#email', function () {
				classInstance.emailInputField_keyup($(this));
			});
			
			$('div#userAccess, div#popup').on('change', 'input#email', function () {
				classInstance.emailInputField_keyup($(this));
			});
			
			$('div#userAccess, div#popup').defaultText();
			
			// Inline popups
			$('div#userAccess, div#popup').magnificPopup({
				delegate: 'a.inline_popup',
				removalDelay: 500,
				//delay removal by X to allow out-animation
				callbacks: {
					beforeOpen: function() {
						var popupContentName = this.st.el.attr('data-content');
						$("#userAccess").trigger('fillPopup', popupContentName);
						this.st.mainClass = this.st.el.attr('data-effect');
					}
				},
				midClick: true
			});
			
			// for the big button in the home landing page (little bit hack alike)
			$('#wrap').magnificPopup({
				delegate: 'button.button_form_userSignup',
				removalDelay: 500,
				//delay removal by X to allow out-animation
				callbacks: {
					beforeOpen: function() {
						$("#userAccess").trigger('fillPopup', 'form_userSignup');
						this.st.mainClass = this.st.el.attr('data-effect');
					}
				},
				midClick: true
			});

			if (user_access_state !== '-1') {
				userAccess_panel = 'form_logOut';
			}

			this.fillUserAccess(null,userAccess_panel);
		},
		render: function() {
		},
		fillPopup: function (obj, popupContentName) {
			$('div#popup').html(_.template($('#template-'+popupContentName).html()) );
			$('div#popup .defaultText').blur();
		},
		fillUserAccess: function (obj, popupContentName) {
			this.$el.html(_.template($('#template-'+popupContentName).html()) );
			this.$el.find('.defaultText').blur();
		},
		signUp_submit : function (_button_elm) {
			console.log(_button_elm);
			console.log(this.model.get('response'));
		},
		emailInputField_keyup : function (_input_elm ) {
			var _parentElmSelector = _input_elm.attr('data-parentelmselector');
			var _button_elm = $('div#popup '+_parentElmSelector+' button');
			var _form_message_elm = $('div#popup '+_parentElmSelector+' p.form_message');
			var _textField_value = _input_elm.val();
			
			var _emailValidationFilter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			switch (true) {
				case _textField_value == '' :
					_button_elm.attr("disabled", "disabled");
					_form_message_elm.html('Please enter your e mail address.');
					break;
				case _emailValidationFilter.test(_textField_value) :
					_button_elm.removeAttr('disabled');
					_form_message_elm.html('The email address appears to be valid but, please make sure that you have entered your e mail address correctly.');
					break;
				default :
					_button_elm.attr("disabled", "disabled");
					_form_message_elm.html('Please enter a valid e mail address.');
					break;
			} 
		}
	});
	
	var model_ajax_result = new Model_ajax_result();
	var view_userAccess = new View_userAccess({model:model_ajax_result});
});

// User login
// User logout
// Lost password
