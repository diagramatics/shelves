<?php // Login fail states ?>
<?php if(isset($_POST['login']) && $_POST['login'] === false): ?>
<div id="alertAccount" class="alert">
  Wrong username and/or password. Please try again.
  <a href="#" class="alert-close" data-alert-close="account"><svg class="icon-x"><use xlink:href="#icon-x"></use></svg></a>
</div>
<?php endif ?>

<header id="siteHeader" class="site-header">
  <div class="site-nav">
    <img src="/img/shelves.png" class="site-logo" />
    <div class="site-nav-item"><a href="/">Home</a></div>
    <div class="site-nav-item">
      <a href="/products">Products</a>
      <div class="site-nav-item-popover">

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
      <button>Sign In</button>
      <a href="#" class="site-sign-in-facebook"><svg class="icon-facebook"><use xlink:href="#icon-facebook"></use></svg></a>
      <a href="#" class="site-sign-in-register">Register</a>
    </form>
  </div>
  <?php else: ?>
  <div class="site-meta site-account-more" aria-hidden="true">
    <a id="metaBackButton" href="#" class="site-sign-in-back"><svg class="icon-arrow-left"><use xlink:href="#icon-arrow-left"></use></svg></a>
    <a href="?logout" class="site-sign-in-register">Logout</a>

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
      <svg class="icon-menu"><use xlink:href="#icon-menu"></use></svg>
    </span>
  </div>
</header>
