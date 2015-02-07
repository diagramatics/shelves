<div class="form-account-settings-address <?= $address->primaryAddress == 0 ? '' : 'primary' ?>">
  <input type="submit" class="form-account-settings-address-select" name="changeAddressPrimary" value="<?= $address->addressID ?>" />
  <div class="form-account-settings-address-content">
    <?= $address->unit ?> <br />
    <?= $address->streetNo ?> <?= $address->streetName ?> <?= $address->street ?> <br />
    <?= $address->city ?> <?= $address->state ?> <?= $address->postcode ?>
  </div>
  <button id="accountSettingsDeleteAddress<?= $address->addressID ?>" class="form-button form-input-block form-input-block-small button-delete-address" name="deleteAddress" value="<?= $address->addressID ?>">Delete</button>
</div>
