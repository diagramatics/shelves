<header id="siteHeader" class="site-header <?= !empty($_POST['needCred']) ? 'is-meta' : ''?> ">
  <div class="site-nav">
    <div class="site-logo">
      <a href="/" title="To Homepage"><img src="/img/shelves.png" alt="Shelves logo" /></a>
    </div>
    <div class="site-nav-item"><a href="/">Home</a></div>
    <div class="site-nav-item">
      <a href="/products">Products</a>
      <div class="site-nav-item-popover">
        <?php // TODO: Implement a product list or something here? ?>
      </div>
    </div>
    <div class="site-nav-item"><a href="/specials">Specials</a></div>
    <div class="site-nav-item"><a href="/about">About</a></div>
  </div>
  <?php if (!isset($_SESSION["email"])): ?>
  <div class="site-meta site-sign-in" aria-hidden="true">
    <a id="metaBackButton" href="#" class="site-sign-in-back"><svg class="icon-arrow-left"><use xlink:href="#icon-arrow-left"></use></svg></a>
    <form id="formLogin" class="site-sign-in-form" action="?login" method="POST">
      <input type="email" name="loginEmail" value="" placeholder="Email">
      <input type="password" name="loginPassword" value="" placeholder="Password">
      <button class="site-meta-buttons">Sign In</button>
      <a href="#" class="site-meta-buttons site-sign-in-register" data-popup="register">Register</a>
    </form>
  </div>
  <?php else: ?>
  <div class="site-meta site-account-more" aria-hidden="true">
    <a id="metaBackButton" href="#" class="site-sign-in-back"><svg class="icon-arrow-left"><use xlink:href="#icon-arrow-left"></use></svg></a>
    <a href="/account" class="site-meta-buttons">Overview</a>
    <a href="/account/settings" class="site-meta-buttons">Settings</a>
    <a href="?logout" class="site-meta-buttons">Logout</a>

  </div>
  <?php endif ?>
  <div class="site-account">
    <div class="site-header-buttons tooltip--bottom" data-tooltip="<?= isset($_SESSION['email']) ? 'Account' : 'Sign In'; ?>">
      <a id="metaButton" href="#"><svg class="icon-user"><use xlink:href="#icon-user"></use></svg></a>
    </div>
    <div class="site-header-buttons tooltip--bottom" data-tooltip="Bag">
      <a href="/bag"><svg class="icon-bag"><use xlink:href="#icon-bag"></use></svg></a>
    </div>
    <div class="site-header-buttons tooltip--bottom" data-tooltip="Menu">
      <a id="menuButton" href="#"><svg class="icon-menu"><use xlink:href="#icon-menu"></use></svg></a>
      <div id="menuButtonPopup" class="menu-button-popup">
        <?php if (isset($_SESSION['userLevel']) && $_SESSION['userLevel'] == 1): ?>
        <ul>
        <li><a href="/admin">Admin Panel</a></li>
        </ul>
        <?php endif ?>
      </div>
    </div>
  </div>
</header>
