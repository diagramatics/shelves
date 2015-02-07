<section class="checkout">
  <h1>Checkout</h1>

  <form id="formCheckout" class="checkout-form" action="?processCheckout" method="POST">
    <fieldset class="checkout-addresses">
      <legend>Select your address:</legend>
      <div class="checkout-addresses-list">
        <?php
          $addressCount = count($data['addresses']);
          $i = 1;
        ?>
        <?php foreach($data['addresses'] as $address): ?>
          <div class="checkout-address">
            <input type="radio" name="address" value="<?= $address->addressID ?>" <?= $address->primaryAddress == true ? 'checked' : ($i++ >= $addressCount ? 'checked' : '') ?>/>
            <div class="checkout-address-container">
              <?= $address->unit ?> <br />
              <?= $address->streetNo ?> <?= $address->streetName ?> <?= $address->street ?> <br />
              <?= $address->city ?> <?= $address->state ?> <?= $address->postcode ?>
            </div>
          </div>
        <?php endforeach ?>
        <?php if (!isset($_POST['addAddress'])): ?>
          <?php include 'checkout-add-address-before.php' ?>
        <?php endif ?>
      </div>
      <?php if (isset($_POST['addAddress'])): ?>
        <?php include 'checkout-add-address.php' ?>
      <?php endif ?>
    </fieldset>
    <fieldset class="checkout-payment">
      <legend>Your Payment Method:</legend>
      <label><input type="radio" name="payment" value="paypal" checked />PayPal</label>
      <button class="checkout-paypal form-button form-input-block" name="doCheckout">Checkout with PayPal</button>
      <p class="checkout-total">Paying $<?= number_format($data['bag']->getTotalCost(), 2) ?></p>
    </fieldset>
  </form>
</section>
