$(function() {
  var Supplier = function() {
    this.selector = '#adminAddSpecial';
    this.productCountSelector = '#adminAddSpecialProductCount';
    this.productCountElement = $(this.productCountSelector);

    this.listener = $('body').on('submit', this.selector, function(event) {

    });

    this.addProductListener = $('body').on('submit', '#adminAddSpecialAddProduct', function(event) {
      var nextProduct = this.productCountElement.val() + 1;

      // Select one of the existing product view as a template
      var template = $($('.admin-special-add-product')[0]).clone();
      $('#adminAddSpecialProducts').add(template);

      // Cancel the form submission because we're not sending anything to the server
      event.preventDefault();
    })
  };

  var supplier = new Supplier();
})
