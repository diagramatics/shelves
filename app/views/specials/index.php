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
    <?php $tempUpcoming = null ?>
    <?php foreach ($data['specials'] as $special): ?>
      <?php if ($special->isRunning()): ?>
        <div class="block-grid-item block-grid-item--big">
          <div class="specials-item">
            <h2><?= $special->getTitle() ?></h2>
            <p><?= $special->getDesc() ?></p>
          </div>
        </div>
      <?php elseif ($special->isNotStarted()): ?>
        <?php if ($tempUpcoming == null): ?>
          <?php $tempUpcoming = $special; ?>
        <?php else: ?>
          <div class="block-grid-item block-grid-item--normal">
            <div class="specials-item">
              <h2><?= $tempUpcoming->getTitle() ?></h2>
              <p><?= $special->getDesc() ?></p>
            </div>
            <div class="specials-item">
              <h2><?= $special->getTitle() ?></h2>
              <p><?= $special->getDesc() ?></p>
            </div>
          </div>
          <?php $tempUpcoming = null ?>
        <?php endif ?>
      <?php endif ?>
    <?php endforeach ?>
    <?php if ($tempUpcoming != null): ?>
      <div class="block-grid-item block-grid-item--normal">
        <div class="specials-item">
          <h2><?= $tempUpcoming->getTitle() ?></h2>
          <p><?= $special->getDesc() ?></p>
        </div>
      </div>
    <?php endif ?>
  </div>
</section>
