$(function() {
  function Settings() {
    Form.call(this);
    this.selector = '#changeAccountSettings';
    this.addressSelector = '.form-account-settings-address';
    this.addAddressContainerSelector = '#accountSettingsAddAddressContainer';
    this.addAddressSelector = '#accountSettingsAddAddress';
    this.confirmAddAddressSelector = '#accountSettingsConfirmAddAddress';
    this.deleteAddressSelector = '.button-delete-address';
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

      // Disable the button to prevent multiple submission
      $(this).attr('disabled', '').html('Loading...');

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
      e.push(t.validate($(form.addressNumber), ['empty']));
      e.push(t.validate($(form.addressName), ['empty']));
      e.push(t.validate($(form.addressType), ['empty']));
      e.push(t.validate($(form.addressCity), ['empty']));
      e.push(t.validate($(form.addressState), ['empty']));
      e.push(t.validate($(form.addressPostcode), ['empty', 'number', 'size4']));
      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
    });

    this.changePasswordListener = $('body').on('click', this.changePasswordSelector, function(event) {
      // Prevent form submission
      // The button is submitting for PHP fallbacks
      event.preventDefault();

      $(this).attr('disabled', '');

      $.ajax('/account/ajaxChangePassword').done(function(data) {
        $(t.changePasswordContainerSelector)
          .empty()
          .html(data);
      });
    });

    this.confirmChangePasswordListener = $('body').on('click', this.confirmChangePasswordSelector, function(event) {
      // Cancel it first because the AJAX has to take over the form submission
      event.preventDefault();
      // Disable the button to prevent multiple submission
      $(this).attr('disabled', '');
      var self = this;
      var e = [];
      var form = $(t.selector)[0];
      e.push(t.validate($(form.password), ['empty', 'password']));
      e.push(t.validate($(form.newPassword), ['empty', 'password']));
      e.push(t.validate($(form.confirmPassword), ['empty', 'password']));
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
            $(self).removeAttr('disabled');
          }
          else if (data === 'error') {
            e.push(true);
            t.createErrorBox($(form.password), data, 'There is a problem. Please try again.');
            $(self).removeAttr('disabled');
          }
          // If it's fine then do the rest
          else if (data === 'ok'){
            // Check if the new password is confirmed
            if ($(form.confirmPassword).val() !== $(form.newPassword).val()) {
              e.push(true);
              t.createErrorBox($(form.confirmPassword), data, 'The new password doesn\'t match.');
              $(self).removeAttr('disabled');
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
                  $(self).removeAttr('disabled');
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
      event.preventDefault();
      // Disable the button
      $(this).attr('disabled', '');

      var id = $(this).val();
      var self = this;
      $.ajax({
        url: '/account/ajaxChangePrimaryAddress',
        type: 'POST',
        data: {
          changeAddressPrimary: id
        }
      }).done(function(data) {
        $(self).removeAttr('disabled');
        if (data === 'ok') {
          Helpers.makeAlert("accountSettings", "Address has been set to primary.");
          $(t.addressSelector).removeClass('primary');
          $(self).parent().addClass('primary');
        }
        else if (data === 'error') {
          Helpers.makeAlert("accountSettings", "There is something wrong in updating your primary address. Please try again.");
        }
      });
    });

    this.deleteAddressListener = $('body').on('click', this.deleteAddressSelector, function(event) {
      // Prevent form submission
      // The button is submitting for PHP fallbacks
      event.preventDefault();

      // Disable the button
      $(this).attr('disabled', '');
      var id = $(this).val();
      var self = this;

      $.ajax({
        url: '/account/ajaxDeleteAddress',
        type: 'POST',
        data: {
          deleteAddress: id
        }
      }).done(function(data) {
        if (data === 'ok') {
          Helpers.makeAlert('accountSettings', 'Address deleted.');
          // Now remove the view altogether
          $(self).parents(t.addressSelector).remove();
        }
        else if (data === 'error') {
          Helpers.makeAlert("accountSettings", "There's something wrong when deleting the address. Please try again.");
          $(self).removeAttr('disabled');
        }
      });
    });
  }
  Settings.prototype = Object.create(Form.prototype);
  Settings.prototype.constructor = Settings;

  var settings = new Settings();

  // ---
  // Login form at the navbar
  function Login() {
    Form.call(this);
    this.selector = '#formLogin';

    var t = this;

    this.listener = $('body').on('submit', this.selector, function(event) {
      var e = [];
      // Check validations
      e.push(t.validate($(this.loginEmail), ['empty', 'email'], false));
      e.push(t.validate($(this.loginPassword), ['empty', 'password'], false));

      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
    });
  }
  Login.prototype = Object.create(Form.prototype);
  Login.prototype.constructor = Login;

  var login = new Login();


  // -----
  // Registering validations
  function Register() {
    Form.call(this);
    this.selector = '#formRegister';

    var t = this;

    this.listener = $('body').on('submit', this.selector, function(event) {
      var e = [];
      // Check validations
      e.push(t.validate($(this.fname), ['empty']));
      e.push(t.validate($(this.lname), ['empty']));
      e.push(t.validate($(this.email), ['empty', 'email']));
      e.push(t.validate($(this.password), ['empty', 'password']));
      e.push(t.validate($(this.passwordConfirm), ['empty', 'password']));
      if ($(this.password).val() !== $(this.passwordConfirm).val()) {
        e.push(true);
        t.createErrorBox($(this.passwordConfirm), 'password', 'The password doesn\'t match.');
      }

      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
    });
  }
  Register.prototype = Object.create(Form.prototype);
  Register.prototype.constructor = Register;

  var register = new Register();


  // -----
  // Account overview details
  var AccountOrders = function() {
    this.formSubmitting = false;
    this.selector = '.account-order-details-form';
    this.closeSelector = '.account-order-details-form-close';
    this.detailsRowSelector = '.account-orders-table-details-row';

    var t = this;

    // Get templates for the before and after button form view
    $.ajax({
      url: 'account/ajaxOrderDetailsBefore',
      type: 'POST',
      data: {
        detailsID: '%detailsID%'
      }
    }).done(function(data) {
      t.detailsBeforeView = data;
    });
    $.ajax({
      url: 'account/ajaxOrderDetailsAfter',
      type: 'POST',
      data: {
        detailsID: '%detailsID%'
      }
    }).done(function(data) {
      t.detailsAfterView = data;
    });
    // Set up functions to generate the views
    this.makeDetailsBeforeView = function(id) {
      var view = this.detailsBeforeView;
      view = view.replace(/%detailsID%/g, id);
      return view;
    }
    this.makeDetailsAfterView = function(id) {
      var view = this.detailsAfterView;
      view = view.replace(/%detailsID%/g, id);
      return view;
    }


    this.listener = $('body').on('submit', this.selector, function(event) {
      // Prevent submit execution
      // The submission is only for PHP fallback
      event.preventDefault();

      // Disable the button so there will not be multiple submissions
      $(this.oDetails).attr('disabled', '').html('Loading...');

      var id = $(this.oDetails).val();
      var parent = $(this).parent();
      var parentRow = $(this).parents('tr');
      $.ajax({
        url: 'account/ajaxOrderDetailsTable',
        type: 'POST',
        data: {
          detailsID: id
        }
      }).done(function(data) {
        parentRow.after(data);
        parent.html(t.makeDetailsAfterView(id));
      });
    });

    this.closeListener = $('body').on('submit', this.closeSelector, function(event) {
      // Prevent submit execution
      // The submission is only for PHP fallback
      event.preventDefault();

      // Disable the button so there will not be multiple submissions
      $(this.oDetailsClose).attr('disabled', '').html('Loading...');

      var id = $(this.oDetailsClose).val();
      var parent = $(this).parent();

      // Close the detail row
      $('#accountOrderDetailsRow' + id).remove();

      parent.html(t.makeDetailsBeforeView(id));
    });
  };
  var accountOrders = new AccountOrders();


  // -----
  // Subscribe to specials email validation
  function Subscribe() {
    this.selector = '#formSubscribeEmail';

    var t = this;

    this.listener = $('body').on('submit', this.selector, function(event) {
      if (t.validate($(this.email), ['empty', 'email'], false)) {
        event.preventDefault();
      }
    })
  }
  Subscribe.prototype = Object.create(Form.prototype);
  Subscribe.prototype.constructor = Subscribe;

  var subscribe = new Subscribe();

});
