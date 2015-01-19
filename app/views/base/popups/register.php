<div id="popupRegister" class="popup <?= isset($_POST['register']) && $_POST['register'] === false ? 'popup--show' : '' ?>">
  <div class="popup-container">
    <a href="#" class="popup-close" data-popup-close="register"><svg class="icon-x"><use xlink:href="#icon-x"></use></svg></a>
    <h1 class="popup-register-title">Register</h1>

    <form id="" class="form-register" action="?register" method="POST">
      <div class="form-input-halfblock-container">
        <input class="form-input-halfblock" type="text" name="fname" value="<?= empty($_POST["fname"]) ? "" : $_POST["fname"] ?>" placeholder="First Name">
        <input class="form-input-halfblock" type="text" name="lname" value="<?= empty($_POST['lname']) ? "" : $_POST['lname'] ?>" placeholder="Last Name">
      </div>
      <input class="form-input-block" type="email" name="email" value="<?= empty($_POST['email']) ? "" : $_POST['email'] ?>" placeholder="Email">
      <div class="form-input-halfblock-container">
        <input class="form-input-halfblock" type="password" name="password" value="" placeholder="Password">
        <input class="form-input-halfblock" type="password" name="passwordConfirm" value="" placeholder="Confirm Password">
      </div>
      <input class="form-button form-input-block" type="submit" value="Register">
      <p>
        Additional information can be filled on your user account preferences or on your first checkout later.
      </p>
    </form>
  </div>
</div>
