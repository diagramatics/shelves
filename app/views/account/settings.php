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
        <label for="changePassword">Password</label>
        <input type="submit" name="changePassword" value="Change Password" class="form-button form-account-settings-button" />
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
          <div class="form-account-settings-address">
            <input type="radio" name="address" value="<?= $address->addressID ?>" <?= $address->primary == 0 ? '' : ' checked' ?>>
            <div class="form-account-settings-address-content">
              <?= $address->unit ?> <br />
              <?= $address->streetNo ?> <?= $address->streetName ?> <?= $address->street ?> <br />
              <?= $address->city ?> <?= $address->state ?> <?= $address->postcode ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </fieldset>

    <input type="submit" name="" value="Update Changes" class="form-button form-input-block">
  </form>
</section>
