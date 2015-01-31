$(function() {
  function Supplier() {
    Form.call(this);
    this.selector = '#adminAddSpecial';
    this.productSelectSelector = '.admin-special-add-product-select';

    var t = this;

    this.listener = $('body').on('submit', this.selector, function(event) {
      var e = [];
      // Check for empty values
      e.push(t.validate($(this.title), ['empty']));
      e.push(t.validate($(this.startDate), ['empty', 'date']));
      e.push(t.validate($(this.endDate), ['empty', 'date']));
      // Check if the values of the products are unique
      var selectsValues = ['-1']; // Null is the default value for "Select product..."
      $('.admin-special-add-product-select').each(function() {
        // Reset the error style if validation is run more than once
        $(this).parent().removeClass('error');
        if (selectsValues.indexOf($(this).val()) < 0) {
          selectsValues.push($(this).val());
          // Check if the discount is filled
          e.push(t.validate($(this).next('input'), ['empty', 'zeroString']));
        }
        else if (selectsValues.indexOf($(this).val()) !== 0) {
          $(this).parent().addClass('error');
          e.push(true);
        }
      });

      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
    });

    this.addProductListener = $('body').on('click', '#adminAddSpecialAddProduct', function(event) {
      var nextProduct = parseInt($('#adminAddSpecialAddProduct').val()) + 1;
      $('#adminAddSpecialAddProduct').val(nextProduct);

      // Select one of the existing product view as a template, clone it, and put the clone before the button
      var template = $($('.admin-special-add-product')[0]).clone();
      $('#adminAddSpecialAddProduct').before(template);

      // Cancel the form submission because we're not sending anything to the server
      // Why is this a submit function anyway? Just for PHP fallback
      event.preventDefault();
    });
  };
  Supplier.prototype = Object.create(Form.prototype);
  Supplier.prototype.constructor = Supplier;

  var supplier = new Supplier();
})
