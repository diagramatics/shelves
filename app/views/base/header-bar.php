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
  <div class="site-sign-in" aria-hidden="true">
    <a id="signInBackButton" href="#" class="site-sign-in-back"><svg class="icon-arrow-left"><use xlink:href="#icon-arrow-left"></use></svg></a>
    <form id="" class="site-sign-in-form" action="" method="POST">
      <input type="email" name="" value="" placeholder="Email">
      <input type="password" name="" value="" placeholder="Password">
      <button>Sign In</button>
      <a href="#" class="site-sign-in-facebook"><svg class="icon-facebook"><use xlink:href="#icon-facebook"></use></svg></a>
      <a href="#" class="site-sign-in-register" data-popup="register">Register</a>
    </form>
  </div>
  <div class="site-account">
    <!-- TODO: Change "user" to PHP echo "sign in" or "user" or something -->
    <span class="site-header-buttons tooltip--bottom" data-tooltip="<?= $data['user'] ? 'Account' : 'Sign In'; ?>">
      <a id="signInButton" href="#"><svg class="icon-user"><use xlink:href="#icon-user"></use></svg></a>
    </span>
    <span class="site-header-buttons tooltip--bottom" data-tooltip="Bag">
      <a href="/bag"><svg class="icon-bag"><use xlink:href="#icon-bag"></use></svg></a>
    </span>
    <span class="site-header-buttons tooltip--bottom" data-tooltip="Menu">
      <svg class="icon-menu"><use xlink:href="#icon-menu"></use></svg>
    </span>
  </div>
</header>
