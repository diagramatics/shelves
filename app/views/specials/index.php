<section class="specials">
  <header>
    <h1 class="specials-title">Specials</h1>
    <p class="specials-desc">We list a variety of discounts and promotions of new or existing items. Check back here often to find more<?php echo ($data['subscribed'] == 0) ? ', or you can subscribe to our newsletter for an easier way to stay updated with us' : '' ?>.</p>
  </header>

  <!-- Put either newsletter subscription form for non-members or a simple big checkbox for members -->
  <?php if ($data['subscribed'] == 0): ?>
    <?php if ($data['logged']): ?>
      <form class="form-subscribe" action="?subscribe" method="POST">
        <input class="form-button form-input-block" type="submit" value="Notify me by email" />
      </form>
    <?php else: ?>
      <form class="form-subscribe" id="" action="?subscribe" method="POST">
        <input type="email" name="" value="" placeholder="Your email">
        <input class="form-button form-subscribe-button" type="submit" name="" value="Subscribe">
      </form>
    <?php endif ?>
  <?php endif ?>

  <div class="block-grid">
    <div class="block-grid-item block-grid-item--big">
    </div>
    <div class="block-grid-item block-grid-item--big">
    </div>
    <div class="block-grid-item block-grid-item--normal">
      <div></div>
      <div></div>
    </div>
  </div>
</section>
