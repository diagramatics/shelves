$(function() {
  function Settings() {
    Form.call(this);
    this.selector = '#changeAccountSettings';
    this.addAddressContainerSelector = '#accountSettingsAddAddressContainer';
    this.addAddressSelector = '#accountSettingsAddAddress';
    this.confirmAddAddressSelector = '#accountSettingsConfirmAddAddress';
    this.changePasswordContainerSelector = '#accountSettingsChangePasswordContainer';
    this.changePasswordSelector = '#accountSettingsChangePassword';
    this.confirmChangePasswordSelector = '#accountSettingsConfirmChangePassword';
    this.selectAddressSelector = '.form-account-settings-address-select';

    var t = this;

    this.listener = $('body').on('submit', this.selector, function(event) {
      var e = [];
      // Check validations
      e.push(t.validate($(this.fName), ['empty']));
      e.push(t.validate($(this.lName), ['empty']));

      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
    });

    this.addAddressListener = $('body').on('click', this.addAddressSelector, function(event) {
      // Prevent form submission
      // The button is submitting for PHP fallbacks
      event.preventDefault();

      // Get the add address content
      $.ajax('/account/ajaxAddAddress').done(function(data) {
        $(t.addAddressContainerSelector)
          .empty()
          .html(data);
      });
    });

    this.confirmAddAddressListener = $('body').on('click', this.confirmAddAddressSelector, function(event) {
      var e = [];
      var form = $(t.selector)[0];
      e.push(t.validate($(form.addressNumber), ['empty', 'number']));
      e.push(t.validate($(form.addressName), ['empty']));
      e.push(t.validate($(form.addressType), ['empty']));
      e.push(t.validate($(form.addressCity), ['empty']));
      e.push(t.validate($(form.addressState), ['empty']));
      e.push(t.validate($(form.addressPostcode), ['empty', 'number']));
      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
      // TODO: AJAX request here?
    });

    this.changePasswordListener = $('body').on('click', this.changePasswordSelector, function(event) {
      // Prevent form submission
      // The button is submitting for PHP fallbacks
      event.preventDefault();

      $.ajax('/account/ajaxChangePassword').done(function(data) {
        $(t.changePasswordContainerSelector)
          .empty()
          .html(data);
      });
    });

    this.confirmChangePasswordListener = $('body').on('click', this.confirmChangePasswordSelector, function(event) {
      // Cancel it first because the AJAX has to take over the form submission
      event.preventDefault();
      var e = [];
      var form = $(t.selector)[0];
      e.push(t.validate($(form.password), ['empty']));
      e.push(t.validate($(form.newPassword), ['empty']));
      e.push(t.validate($(form.confirmPassword), ['empty']));
      // Check if password is correct
      if ($(form.password).val() !== '') {
        $.ajax({
          url: '/account/ajaxCorrectPassword',
          type: 'POST',
          data: {
            password: $(form.password).val()
          }
        }).done(function(data) {
          if (data === 'mismatch') {
            e.push(true);
            t.createErrorBox($(form.password), data, 'You have typed a wrong password.');
          }
          else if (data === 'error') {
            e.push(true);
            t.createErrorBox($(form.password), data, 'There is a problem. Please try again.');
          }
          // If it's fine then do the rest
          else if (data === 'ok'){
            // Check if the new password is confirmed
            if ($(form.confirmPassword).val() !== $(form.newPassword).val()) {
              e.push(true);
              t.createErrorBox($(form.confirmPassword), data, 'The new password doesn\'t match.');
            }
            // Now see if there's any errors
            if (e.indexOf(true) === -1) {
              // If there isn't any validation problems use AJAX to update password
              $.ajax({
                url: '/account/ajaxConfirmChangePassword',
                type: 'POST',
                data: {
                  password: $(form.newPassword).val()
                }
              }).done(function(data) {
                if (data === 'ok') {
                  Helpers.makeAlert('accountSettings', 'Password successfully updated.');
                  // Change the form back to the button
                  $.ajax('/account/ajaxChangePasswordButton').done(function(data) {
                    $(t.changePasswordContainerSelector)
                      .empty()
                      .html(data);
                  });
                }
                else {
                  Helpers.makeAlert('accountSettings', 'There is something wrong in updating your password. Please try again.');
                }
              });
            }
          }
        });
      }
    });

    this.selectAddressListener = $('body').on('click', this.selectAddressSelector, function(event) {
      // Prevent form submission
      // The button is submitting for PHP fallbacks
      // event.preventDefault();
    });
  }
  Settings.prototype = Object.create(Form.prototype);
  Settings.prototype.constructor = Settings;

  var settings = new Settings();


  function Login() {
    Form.call(this);
    this.selector = '#formLogin';

    var t = this;

    this.listener = $('body').on('submit', this.selector, function() {
      var e = [];
      // Check validations
      e.push(t.validate($(this.loginEmail), ['empty', 'email'], false));
      e.push(t.validate($(this.loginPassword), ['empty'], false));

      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
    });
  }
  Login.prototype = Object.create(Form.prototype);
  Login.prototype.constructor = Login;

  var login = new Login();

});
