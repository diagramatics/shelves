<div class="block-grid">
  <?php
    $swaps = 2;
    $specialsCount = 0;
    $specialsCountLimit = 3;
    $productsCount = 0;
    $productsCountLimit = 26;
    $tempProduct = null;
  ?>
  <?php while($swaps > 0): ?>
    <?php $rand = rand(1, $swaps); ?>
    <?php if ($rand == 1 && ($specialsCount < $specialsCountLimit && $specialsCount < count($data['specials']))): ?>
      <div class="block-grid-item block-grid-item--big">
        <div class="specials-item">
          <small>Ends at <?= $data['specials'][$specialsCount]->getEndDateFormatted() ?></small>
          <h2><?= $data['specials'][$specialsCount]->getTitle() ?></h2>
          <p><?= $data['specials'][$specialsCount]->getLongDesc() ?></p>
          <div class="specials-item-products">
            <?php foreach ($data['specials'][$specialsCount]->getProductLinks() as $product): ?>
              <a href="/products/product/<?= $product->prodID . '/' . Helpers::makeSlug($data['productsAssoc'][$product->prodID]->getName()) ?>">
                <img src="<?= $data['productsAssoc'][$product->prodID]->getImage() ?>" alt="<?= $data['productsAssoc'][$product->prodID]->getName() ?>" />
              </a>
            <?php endforeach ?>
          </div>
        </div>
      </div>
      <?php
        $specialsCount++;
        if ($specialsCount >= $specialsCountLimit || $specialsCount >= count($data['specials'])) {
          $swaps--;
        }
      ?>
    <?php elseif (!($productsCount > $productsCountLimit || $productsCount > count($data['products']))): ?>
      <?php if ($tempProduct == null): ?>
        <?php isset($data['products'][$productsCount]) ? $tempProduct = $data['products'][$productsCount] : ''  ?>
      <?php else: ?>
        <div class="block-grid-item block-grid-item--normal">
          <div class="promo-item">
            <a href="<?= $tempProduct->getUrl() ?>">
              <img src="<?= $tempProduct->getImage() ?>" alt="<?= $tempProduct->getName() ?>" />
            </a>
            <div class="promo-item-details">
              <a href="<?= $tempProduct->getUrl() ?>" class="promo-item-details-name-container">
                <div class="promo-item-details-name"><?= $tempProduct->getName() ?></div>
              </a>
              <form action="?addBag" class="promo-item-details-add" method="POST">
                <input class="promo-item-details-add-input" type="number" name="qty" value="1" min="1" max="<?= $tempProduct->getQty() ?>">
                <input type="hidden" name="itemQty" value="<?= $tempProduct->getQty() ?>">
                <input type="hidden" name="itemID" value="<?= $tempProduct->getID() ?>">
                <button class="button-icon" title="Add to Bag"><svg class="icon-plus"><use xlink:href="#icon-plus"></use></svg></button>
              </form>
            </div>
          </div>
          <div class="promo-item">
            <a href="<?= $data['products'][$productsCount]->getUrl() ?>">
              <img src="<?= $data['products'][$productsCount]->getImage() ?>" alt="<?= $data['products'][$productsCount]->getName() ?>" />
            </a>
            <div class="promo-item-details">
              <a href="<?= $data['products'][$productsCount]->getUrl() ?>" class="promo-item-details-name-container">
                <div class="promo-item-details-name"><?= $data['products'][$productsCount]->getName() ?></div>
              </a>
              <form action="?addBag" class="promo-item-details-add" method="POST">
                <input class="promo-item-details-add-input" type="number" name="qty" value="1" min="1" max="<?= $data['products'][$productsCount]->getQty() ?>">
                <input type="hidden" name="itemQty" value="<?= $data['products'][$productsCount]->getQty() ?>">
                <input type="hidden" name="itemID" value="<?= $data['products'][$productsCount]->getID() ?>">
                <button class="button-icon" title="Add to Bag"><svg class="icon-plus"><use xlink:href="#icon-plus"></use></svg></button>
              </form>
            </div>
          </div>
        </div>
        <?php $tempProduct = null ?>
      <?php endif ?>
      <?php
        $productsCount++;
        if ($productsCount >= $productsCountLimit || $productsCount >= count($data['products'])) {
          $swaps--;
        }
      ?>
    <?php else: ?>
      <?php $swaps--; ?>
    <?php endif ?>
  <?php endwhile ?>
  <?php if ($tempProduct != null): ?>
    <div class="block-grid-item block-grid-item--normal">
      <div class="promo-item">
        <a href="<?= $tempProduct->getUrl() ?>">
          <img src="<?= $tempProduct->getImage() ?>" alt="<?= $tempProduct->getName() ?>" />
        </a>
        <div class="promo-item-details">
          <a href="<?= $tempProduct->getUrl() ?>" class="promo-item-details-name-container">
            <div class="promo-item-details-name"><?= $tempProduct->getName() ?></div>
          </a>
          <form action="?addBag" class="promo-item-details-add" method="POST">
            <input class="promo-item-details-add-input" type="number" name="qty" value="1" min="1" max="<?= $tempProduct->getQty() ?>">
            <input type="hidden" name="itemQty" value="<?= $tempProduct->getQty() ?>">
            <input type="hidden" name="itemID" value="<?= $tempProduct->getID() ?>">
            <button class="button-icon" title="Add to Bag"><svg class="icon-plus"><use xlink:href="#icon-plus"></use></svg></button>
          </form>
        </div>
      </div>
    </div>
  <?php endif ?>
</div>
<p class="promo-note"><em><a href="/">Refresh the page</a> for more great products from us.</em></p>
