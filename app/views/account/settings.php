<section class="settings container">
  <h1><?= $data['title'] ?></h1>

  <form id="" action="?changeAccountSettings" class="form-account-settings" method="POST">
    <fieldset>
      <legend>Credentials</legend>
      <div class="form-account-settings-group">
        <label for="email">Email</label>
        <input type="email" name="email" value="<?= $data['email'] ?>" class="form-account-settings-input" disabled="disabled" />
        <input type="submit" name="changeEmail" value="Change" class="form-button form-account-settings-button form-account-settings-button--inline" />
      </div>

      <div class="form-account-settings-group">
        <?php if (isset($_POST['changePassword'])): ?>
          <label for="changePassword">Password</label>
          <div class="form-account-settings-group form-account-settings-group--password">
            <input type="password" name="password" class="form-account-settings-input form-account-settings-input--password" placeholder="Current Password" />
            <input type="password" name="newPassword" class="form-account-settings-input form-account-settings-input--password" placeholder="New Password" />
            <input type="password" name="confirmPassword" class="form-account-settings-input form-account-settings-input--password" placeholder="Confirm Password" />
            <input type="submit" name="confirmChangePassword" value="Change Password" class="form-button form-account-settings-button" />
          </div>
        <?php else: ?>
          <label for="changePassword">Password</label>
          <input type="submit" name="changePassword" value="Change Password" class="form-button form-account-settings-button" />
        <?php endif; ?>
      </div>
    </fieldset>
    <fieldset>
      <legend>Personal</legend>
      <div class="form-account-settings-group">
        <label for="fName">First Name</label>
        <input type="text" name="fName" value="<?= $data['fName'] ?>" class="form-account-settings-input" />
      </div>
      <div class="form-account-settings-group">
        <label for="lName">Last Name</label>
        <input type="text" name="lName" value="<?= $data['lName'] ?>" class="form-account-settings-input" />
      </div>
    </fieldset>
    <fieldset>
      <legend>Addresses</legend>
      <div class="form-account-settings-addresses">
        <?php foreach($data['addresses'] as $address): ?>
          <div class="form-account-settings-address <?= $address->primaryAddress == 0 ? '' : '  primary' ?>">
            <input type="submit" class="form-account-settings-address-select" name="changeAddressPrimary" value="<?= $address->addressID ?>" />
            <div class="form-account-settings-address-content">
              <?= $address->unit ?> <br />
              <?= $address->streetNo ?> <?= $address->streetName ?> <?= $address->street ?> <br />
              <?= $address->city ?> <?= $address->state ?> <?= $address->postcode ?>
            </div>
            <button class="form-button form-input-block form-input-block-small button-delete-address" name="deleteAddress" value="<?= $address->addressID ?>">Delete</button>
          </div>
        <?php endforeach; ?>
      </div>
      <?php if (isset($_POST['addAddress'])): ?>
      <?php include 'settings-add-address.php' ?>
      <?php else: ?>
      <input type="submit" name="addAddress" value="Add New Address" class="form-button form-input-block form-input-block--small" />
      <?php endif; ?>
    </fieldset>

    <input type="submit" name="changeSettings" value="Update Changes" class="form-button form-input-block" />
  </form>
</section>
