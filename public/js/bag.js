$(function() {
  function Bag(selector) {
    Form.call(this);
    this.classSelector = '.form-bag-item-manipulate';
    this.selector = selector;
    this.changeQuantitySelector = '.bag-items-td-qty-edit';
    this.removeItemSelector = '.bag-items-remove';
    this.itemPriceSelector = '.bag-items-td-price';
    this.totalItemPriceSelector = '.bag-items-td-price-total';
    this.totalBagPriceSelector = '.bag-total-price';
    this.addBagSelector = 'form[action="?addBag"]';
    this.checkoutFormSelector = '#formCheckout';
    this.checkoutAddAddressSelector = '#checkoutAddAddress';

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
        var maxQuantity = $(form.editedQty).attr('max');
        var self = this;
        var e = [];
        e.push(t.validate($(form.editedQty), ['empty', 'zeroString', 'minus']));
        if (parseInt($(form.editedQty).val(), 10) > maxQuantity) {
          e.push(true);
          t.createErrorBox($(form.editedQty), 'minus', 'We only have '+maxQuantity+' in stock.');
        }
        if (e.indexOf(true) === -1) {
          $.ajax({
            url: '/bag/ajaxConfirmChangeItemQuantity',
            type: 'POST',
            data: {
              confirmEditItemQty: $(form).attr('id').substr("manipulateBag".length),
              editedQty: parseInt($(form.editedQty).val(), 10)
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
                editedQty: parseInt($(form.editedQty).val(), 10)
              }
            }).done(function(data) {
              // Update the item total price and the total bag price
              var row = $(form).parents('tr');
              var itemPrice = parseFloat(row.children(t.itemPriceSelector).text().slice(1));
              var itemTotalPrice = parseFloat(row.children(t.totalItemPriceSelector).text().slice(1));
              var bagTotalPrice = parseFloat($(t.totalBagPriceSelector).text().slice(1));


              var diffPrice = itemPrice * parseInt($(form.editedQty).val(), 10) - itemTotalPrice;
              row.children(t.totalItemPriceSelector).text('$' + (itemTotalPrice + diffPrice).toFixed(2));
              $(t.totalBagPriceSelector).text('$' + (bagTotalPrice + diffPrice).toFixed(2));

              // Replace the view
              $(self).parent()
                .empty()
                .html(data);
            });
          });
        }
      }
    });

    this.removeItemListener = $('body').on('click', this.removeItemSelector, function(event) {
      // Cancel the form submission
      // The form submission is used just for PHP fallback
      event.preventDefault();

      var self = this;
      var form = $(this).parents('form')[0];

      // Remove the bag item with AJAX
      $.ajax({
        url: '/bag/ajaxRemoveItem',
        type: 'POST',
        data: {
          prodID: $(form.prodID).val()
        }
      }).done(function(data) {
        if (data === 'error') {
          Helpers.makeAlert('bag', 'There is a problem in removing it. Please try again.')
        }
        else if (data === 'ok') {
          Helpers.makeAlert('bag', 'Item removed from your bag.');
          // Get the total price of the item removed and subtract it from the
          // total price
          var row = $(form).parents('tr');
          var itemTotalPrice = parseFloat(row.children(t.totalItemPriceSelector).text().slice(1));
          var bagTotalPrice = parseFloat($(t.totalBagPriceSelector).text().slice(1));
          $(t.totalBagPriceSelector).text('$' + (bagTotalPrice - itemTotalPrice).toFixed(2));

          // Now remove the whole row from view
          row.remove();
        }
      });
    });

    this.addBagListener = $('body').on('submit', this.addBagSelector, function(event) {
      // Prevent submission
      // Submission is for PHP fallback only
      event.preventDefault();

      var qty = $(this.qty).val();
      var itemQty = $(this.itemQty).val();
      if (parseInt(qty, 10) > parseInt($(this.itemQty).val(), 10)) {
        return Helpers.makeAlert('bag', 'Sorry, we don\'t have that much in stock. We currently have ' + $(this.itemQty).val() + ' left.');
      }
      // If quantity passes then let's add it
      $.ajax({
        url: '/bag/ajaxAddItem',
        type: 'POST',
        data: {
          qty: qty,
          itemQty: itemQty,
          itemID: $(this.itemID).val()
        }
      }).done(function(data) {
        if (data == 'nostock') {
          Helpers.makeAlert('bag', 'Sorry, we don\'t have that much in stock. We currently have ' + itemQty + ' left.');
        }
        else if (data == 'add') {
          Helpers.makeAlert('bag', 'Added item to bag.');
        }
        else if (data == 'error') {
          Helpers.makeAlert('bag', 'There is a problem. Please try again.');
        }
        else {
          Helpers.makeAlert('bag', 'Added '+ qty +' more item to bag. There is now '+data+' of the product in your bag.');
        }
      });
    });

    this.checkoutFormListener = $('body').on('submit', this.checkoutFormSelector, function(event) {
      var e = [];
      if ($('input[name="address"]:checked', this).val() === undefined) {
        // There is no address selected.
        e.push(true);
        t.createErrorBox($('.checkout-addresses-list'), 'not-selected', 'You haven\'t selected any addresses yet. Add one if you don\'t have any.');
      }
      if (e.indexOf(true) > -1) {
        event.preventDefault();
      }
    });

    this.checkoutAddAddressListener = $('body').on('click', this.checkoutAddAddressSelector, function(event) {
      // Prevent submission, submission is PHP fallback
      event.preventDefault();

      if ($('.checkout-add-new-address').length <= 0) {
        // Disable button to prevent duplicate events
        $(this).attr('disabled', '');
        var self = this;

        $.ajax('/bag/ajaxCheckoutAddAddress').done(function(data) {
          // Remove any occurences of the form if there's any
          // Just for precautions
          $('.checkout-add-new-address').remove();
          $('.checkout-addresses-list').after(data);
          $(self).removeAttr('disabled').addClass('toggle');
        });
      }
      else {
        // Remove the form
        $('.checkout-add-new-address').remove();
        $(this).removeClass('toggle')
      }
    });
  };
  Bag.prototype = Object.create(Form.prototype);
  Bag.prototype.constructor = Bag;

  var bag = new Bag();
});
