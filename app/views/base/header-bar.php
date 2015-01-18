<?php // Login fail states ?>
<?php
  if(isset($_POST['login']) && $_POST['login'] === false) {
    Helpers::makeAlert('account', 'Wrong username and/or password. Please try again.');
  }
  else if (isset($_POST['register']) && $_POST['register'] === true) {
    Helpers::makeAlert('register', 'Successfully registered! Welcome '.$_POST["registerName"].'!');
  }
?>

<header id="siteHeader" class="site-header">
  <div class="site-nav">
    <img src="/img/shelves.png" class="site-logo" />
    <div class="site-nav-item"><a href="/">Home</a></div>
    <div class="site-nav-item">
      <a href="/products">Products</a>
      <div class="site-nav-item-popover">
        <?php // TODO: Implement a product list or something here? ?>
      </div>
    </div>
    <div class="site-nav-item"><a href="/specials">Specials</a></div>
    <div class="site-nav-item"><a href="/">About</a></div>
  </div>
  <?php if (!isset($_SESSION["email"])): ?>
  <div class="site-meta site-sign-in" aria-hidden="true">
    <a id="metaBackButton" href="#" class="site-sign-in-back"><svg class="icon-arrow-left"><use xlink:href="#icon-arrow-left"></use></svg></a>
    <form id="" class="site-sign-in-form" action="?login" method="POST">
      <input type="email" name="loginEmail" value="" placeholder="Email">
      <input type="password" name="loginPassword" value="" placeholder="Password">
      <button class="site-meta-buttons">Sign In</button>
      <a href="#" class="site-meta-buttons site-sign-in-facebook"><svg class="icon-facebook"><use xlink:href="#icon-facebook"></use></svg></a>
      <a href="#" class="site-meta-buttons site-sign-in-register" data-popup="register">Register</a>
    </form>
  </div>
  <?php else: ?>
  <div class="site-meta site-account-more" aria-hidden="true">
    <a id="metaBackButton" href="#" class="site-sign-in-back"><svg class="icon-arrow-left"><use xlink:href="#icon-arrow-left"></use></svg></a>
    <a href="/account/settings" class="site-meta-buttons">Settings</a>
    <a href="?logout" class="site-meta-buttons">Logout</a>

  </div>
  <?php endif ?>
  <div class="site-account">
    <span class="site-header-buttons tooltip--bottom" data-tooltip="<?= isset($_SESSION['email']) ? 'Account' : 'Sign In'; ?>">
      <a id="metaButton" href="#"><svg class="icon-user"><use xlink:href="#icon-user"></use></svg></a>
    </span>
    <span class="site-header-buttons tooltip--bottom" data-tooltip="Bag">
      <a href="/bag"><svg class="icon-bag"><use xlink:href="#icon-bag"></use></svg></a>
    </span>
    <span class="site-header-buttons tooltip--bottom" data-tooltip="Menu">
      <a id="menuButton" href="#"><svg class="icon-menu"><use xlink:href="#icon-menu"></use></svg></a>
      <div id="menuButtonPopup" class="menu-button-popup">
        <?php if (isset($_SESSION['userLevel']) && $_SESSION['userLevel'] == 1): ?>
        <li><a href="/admin">Admin Panel</a></li>
        <?php endif ?>
      </div>
    </span>
  </div>
</header>
