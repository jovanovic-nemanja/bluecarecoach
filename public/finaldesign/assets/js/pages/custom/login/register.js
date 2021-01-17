"use strict";

// Class Definition
var KTAddUser = function () {
	// Private Variables
	var _login;
	var _avatar;

	var _showForm = function(form) {
        var cls = 'login-' + form + '-on';
        var form = 'kt_login_' + form + '_form';

        _login.removeClass('login-signup-on');

        _login.addClass(cls);

        KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
    }

	var _initValidations = function () {
		var validation;
        var form = KTUtil.getById('kt_login_register_form');

        validation = FormValidation.formValidation(
            form,
            {
                fields: {
                    profile_logo: {
						validators: {
							notEmpty: {
								message: 'Avatar is required'
							}
						}
					},
					firstname: {
						validators: {
							notEmpty: {
								message: 'First Name is required'
							}
						}
					},
					lastname: {
						validators: {
							notEmpty: {
								message: 'Last Name is required'
							}
						}
					},
					username: {
						validators: {
							notEmpty: {
								message: 'UserName is required'
							}
						}
					},
					phone_number: {
						validators: {
							notEmpty: {
								message: 'Phone Number is required'
							}
						}
					}
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );

        $('#kt_login_register_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    swal.fire({
                        text: "All is cool! Now you submit this form",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        $('#kt_login_register_form').submit();
                    });
                } else {
                    swal.fire({
                        text: "Sorry, looks like there are some errors detected, please try again.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        KTUtil.scrollTop();
                    });
                }
            });
        });

        // Handle cancel button
        $('#kt_login_register_cancel').on('click', function (e) {
            e.preventDefault();

            _showForm('register');
        });
	}

	var _initAvatar = function () {
		_avatar = new KTImageInput('kt_user_add_avatar');
	}

	return {
		// public functions
		init: function () {
			_login = $('#kt_login');

			_initValidations();
			_initAvatar();
		}
	};
}();

jQuery(document).ready(function () {
	KTAddUser.init();
});
