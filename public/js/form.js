var Form = function() {};

Form.prototype.validate = function(el, conditions, show) {
  // Assemble errors
  var placeholder = el.attr('placeholder');
  var e = [];
  var errors = [];
  if (show === undefined) {
    show = true;
  }
  if (conditions.indexOf('empty') > -1) {
    if (el.val() === '') {
      e.push('empty');
      errors.push('This field cannot be empty. <strong>('+placeholder+')</strong>');
    }
  }
  if (conditions.indexOf('number') > -1) {
    if (isNaN(el.val())) {
      e.push('number');
      errors.push('This field has to only have numbers. <strong>('+placeholder+')</strong>');
    }
  }
  if (conditions.indexOf('minus') > -1) {
    if (parseFloat(el.val()) < 0) {
      e.push('minus');
      errors.push('This field has to not have minus numbers. <strong>('+placeholder+')</strong>');
    }
  }
  if (conditions.indexOf('zeroString') > -1) {
    if (el.val() === '0') {
      e.push('zeroString');
      errors.push('This field cannot be filled with 0. <strong>('+placeholder+')</strong>');
    }
  }
  if (conditions.indexOf('date') > -1) {
    if (isNaN(Date.parse(el.val()))) {
      e.push('date');
      errors.push('This field contains an invalid date. <strong>('+placeholder+')</strong>');
    }
  }
  if (conditions.indexOf('email') > -1) {
    var regex = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if (el.val().search(regex) === -1) {
      e.push('email');
      errors.push('Invalid email');
    }
  }
  if (conditions.indexOf('size4') > -1) {
    if (el.val().length > 4) {
      e.push('size4');
      errors.push('This field can only have 4 characters or less. <strong>('+placeholder+')</strong>');
    }
  }

  // Create errors based on how many we've got
  this.createErrorBox(el, e, errors, show);

  // Return the length of the e. If it's bigger than 0 then there's an error and return true
  return e.length > 0;
};

Form.prototype.createErrorBox = function (el, e, errors, show) {
  if (show === true || show === undefined) {
    // Check if there's any errors accumulated
    if (e.length > 0) {
      // Check if there's any existing error box after the error input
      if (el.next('.form-error-box').length === 0) {
        // If there isn't any make a new one
        el.after('<div class="form-error-box"></div>');
      }
      // Get that element
      var errorBox = el.next('.form-error-box');
      // Empty the contents first to reset
      errorBox.empty();

      if (typeof e === 'string') {
        errorBox.append('<div class="error-' + e + '">' + errors + '</div>');
      }
      else {
        for (i = 0; i < e.length; i++) {
          errorBox.append('<div class="error-' + e[i] + '">' + errors[i] + '</div>');
        }
      }
    }
    else if (el.next('.form-error-box').length !== 0) {
      el.next('.form-error-box').remove();
    }
  }

  if (e.length > 0) {
    el.addClass('error');
  }
  else {
    el.removeClass('error');
  }
}
