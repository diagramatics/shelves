$(function() {
  function Bag(selector) {
    Form.call(this);
    this.classSelector = '.form-bag-item-manipulate';
    this.selector = selector;
    this.changeQuantitySelector = '.bag-items-td-qty-edit';

    var t = this;

    this.changeQuantityListener = $('body').on('click', this.changeQuantitySelector, function(event) {
      // Cancel the form submission
      // The form submission is used just for PHP fallback
      event.preventDefault();

      // If we're not confirming the quantity change then toggle the view
      if (!($(this).hasClass(t.changeQuantitySelector.substr(1) + '--confirm'))) {

        var self = this;
        var productID = $(this).attr('form').substr("manipulateBag".length);
        var qty = $('.bag-items-td-qty-amount', $(this).parent()).text();

        $.ajax({
          url: '/bag/ajaxChangeItemQuantity',
          type: 'POST',
          data: {
            editItemQty: productID,
            editItemQtyAmount: qty
          }
        }).done(function(data) {
          $(self).parent()
            .empty()
            .html(data);
        });
      }
      else {
        // Check if the quantity is 0 or empty
        var form = $('form[id="' + $(this).attr('form') + '"]')[0];
        var self = this;
        if (!t.validate($(form.editedQty), ['empty', 'zeroString'])) {
          $.ajax({
            url: '/bag/ajaxConfirmChangeItemQuantity',
            type: 'POST',
            data: {
              confirmEditItemQty: $(form).attr('id').substr("manipulateBag".length),
              editedQty: $(form.editedQty).val()
            }
          }).done(function(data) {
            // Break the script execution if it's an error
            if (data === 'error') {
              return Helpers.makeAlert('bag', 'There is a problem. Please try again.');
            }
            // If not the rest will revert the view to the previous state
            if (data === 'ok') {
              Helpers.makeAlert('bag', 'Quantity edited.');
            }
            else if (data === 'same') {
              Helpers.makeAlert('bag', 'The quantity hasn\'t changed.');
            }
            $.ajax({
              url: '/bag/ajaxChangeItemQuantityBefore',
              type: 'POST',
              data: {
                confirmEditItemQty: $(form).attr('id').substr("manipulateBag".length),
                editedQty: $(form.editedQty).val()
              }
            }).done(function(data) {
              $(self).parent()
                .empty()
                .html(data);
            });
          });
        }
      }
    });
  };
  Bag.prototype = Object.create(Form.prototype);
  Bag.prototype.constructor = Bag;

  var bag = new Bag();
});
