<div class="checkout-address">
  <input type="radio" name="address" value="<?= $address->addressID ?>" <?= $address->primaryAddress == true ? 'checked' : ($i++ >= $addressCount ? 'checked' : '') ?>/>
  <div class="checkout-address-container">
    <?= $address->unit ?> <br />
    <?= $address->streetNo ?> <?= $address->streetName ?> <?= $address->street ?> <br />
    <?= $address->city ?> <?= $address->state ?> <?= $address->postcode ?>
  </div>
</div>
