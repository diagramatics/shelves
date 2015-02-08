<div id="popupRegister" class="popup <?= isset($_POST['register']) && $_POST['register'] === false ? 'popup--show' : '' ?>">
  <div class="popup-container">
    <a href="#" class="popup-close" data-popup-close="register"><svg class="icon-x"><use xlink:href="#icon-x"></use></svg></a>
    <h1 class="popup-register-title">Register</h1>

    <form id="formRegister" class="form-register" action="?register" method="POST">
      <div class="form-input-halfblock-container">
        <div class="form-input-halfblock">
          <input class="form-input-block" type="text" name="fname" value="<?= Helpers::orEmpty($_POST["fname"]) ?>" placeholder="First Name">
        </div>
        <div class="form-input-halfblock">
          <input class="form-input-block" type="text" name="lname" value="<?= Helpers::orEmpty($_POST['lname']) ?>" placeholder="Last Name">
        </div>
      </div>
      <input class="form-input-block" type="email" name="email" value="<?= Helpers::orEmpty($_POST['email']) ?>" placeholder="Email">
      <div class="form-input-halfblock-container">
        <div class="form-input-halfblock">
          <input class="form-input-block" type="password" name="password" value="" placeholder="Password">
        </div>
        <div class="form-input-halfblock">
          <input class="form-input-block" type="password" name="passwordConfirm" value="" placeholder="Confirm Password">
        </div>
      </div>
      <input class="form-button form-input-block" type="submit" value="Register">
      <p>
        Additional information can be filled on your user account preferences or on your first checkout later.
      </p>
    </form>
  </div>
</div>
