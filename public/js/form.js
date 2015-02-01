var Form = function() {};

Form.prototype.validate = function(el, conditions) {
  // Assemble errors
  var placeholder = el.attr('placeholder');
  var e = [];
  var errors = [];
  if (conditions.indexOf('empty') > -1) {
    if (el.val() === '') {
      e.push('empty');
      errors.push('This field cannot be empty. <strong>('+placeholder+')</strong>');
    }
  }
  if (conditions.indexOf('number') > -1) {
    if (!isNaN(el.val())) {
      e.push('number');
      errors.push('This field has to only have numbers. <strong>('+placeholder+')</strong>');
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

  // Check if there's any errors accumulated
  if (errors.length > 0) {
    // Now check if there's any existing error box after the error input
    if (el.next('.form-error-box').length === 0) {
      // If there isn't any make a new one
      el.after('<div class="form-error-box"></div>');
    }
    // Get that element
    var errorBox = el.next('.form-error-box');
    // Empty the contents first to reset
    errorBox.empty();

    // And add the errors
    for (i = 0; i < e.length; i++) {
      errorBox.append('<div class="error-' + e[i] + '">' + errors[i] + '</div>');
    }
  }

  // Return the length of the e. If it's bigger than 0 then there's an error and return true
  return e.length > 0;
};
