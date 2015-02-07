<section class="settings container">
  <h1><?= $data['title'] ?></h1>

  <form id="changeAccountSettings" action="?changeAccountSettings" class="form-account-settings" method="POST">
    <fieldset>
      <legend>Credentials</legend>
      <div class="form-account-settings-group">
        <label for="settingsEmail">Email</label>
        <input id="settingsEmail" type="email" name="email" value="<?= $data['email'] ?>" class="form-account-settings-input" disabled="disabled" />
      </div>

      <div class="form-account-settings-group">
        <label>Password</label>
        <div id="accountSettingsChangePasswordContainer" class="form-account-settings-group--inner">
          <?php if (isset($_POST['changePassword'])): ?>
            <?php include 'settings-change-password.php' ?>
          <?php else: ?>
            <?php include 'settings-change-password-button.php' ?>
          <?php endif; ?>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <legend>Personal</legend>
      <div class="form-account-settings-group">
        <label for="settingsFName">First Name</label>
        <input id="settingsFName" type="text" name="fName" placeholder="First Name" value="<?= Helpers::orEmpty($_POST['fName'], $data['fName']) ?>" class="form-account-settings-input" />
      </div>
      <div class="form-account-settings-group">
        <label for="settingsLName">Last Name</label>
        <input id="settingsLName" type="text" name="lName" placeholder="Last Name" value="<?= Helpers::orEmpty($_POST['lName'], $data['lName']) ?>" class="form-account-settings-input" />
      </div>
    </fieldset>
    <fieldset>
      <legend>Addresses</legend>
      <div class="form-account-settings-addresses">
        <?php foreach($data['addresses'] as $address): ?>
          <div class="form-account-settings-address <?= $address->primaryAddress == 0 ? '' : 'primary' ?>">
            <input type="submit" class="form-account-settings-address-select" name="changeAddressPrimary" value="<?= $address->addressID ?>" />
            <div class="form-account-settings-address-content">
              <?= $address->unit ?> <br />
              <?= $address->streetNo ?> <?= $address->streetName ?> <?= $address->street ?> <br />
              <?= $address->city ?> <?= $address->state ?> <?= $address->postcode ?>
            </div>
            <button id="accountSettingsDeleteAddress<?= $address->addressID ?>" class="form-button form-input-block form-input-block-small button-delete-address" name="deleteAddress" value="<?= $address->addressID ?>">Delete</button>
          </div>
        <?php endforeach; ?>
      </div>
      <div id="accountSettingsAddAddressContainer">
        <?php if (isset($_POST['addAddress'])): ?>
          <?php include 'settings-add-address.php' ?>
        <?php else: ?>
          <input type="submit" id="accountSettingsAddAddress" name="addAddress" value="Add New Address" class="form-button form-input-block form-input-block--small" />
        <?php endif; ?>
      </div>
    </fieldset>

    <input type="submit" name="changeSettings" value="Update Changes" class="form-button form-input-block" />
  </form>
</section>
