<?php if(!empty($data['product'])): ?>
<section class="single-product">
  <div class="single-product-info single-product-info--left">
    <img src="<?= $data['product']->getImage() ?>" class="product-image" />
  </div>
  <div class="single-product-info single-product-info--right">
    <header>
      <h1 class="single-product-name"><?= $data['product']->getName() ?></h1>
      <p class="single-product-price">
        <span class="single-product-price-number">$<?= number_format($data['product']->getPrice(), 2) ?></span><span class="single-product-price-unit"><?= (!empty($data['product']->getPriceUnit()) ? '/'.$data['product']->getPriceUnit() : '') ?></span>
      </p>
      <?php if ($data['product']->getDiscount() != 0): ?>
        <p>Promotion! Discounted <?= $data['product']->getDiscount() ?>% from $<?= number_format($data['product']->getBasePrice(), 2) ?>.</p>
      <?php endif ?>
    </header>

    <form id="" action="?addBag" class="single-product-add" method="POST">
      <input type="number" name="qty" value="1" min="1" max="<?= $data['product']->getQty() ?>">
      <input type="hidden" name="itemQty" value="<?= $data['product']->getQty() ?>">
      <input type="hidden" name="itemID" value="<?= $data['product']->getID() ?>">
      <button>
        <span>Add to Bag</span>
        <svg class="icon-bag"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-bag"></use></svg>
      </button>
    </form>

    <?php if(!empty($data['product']->getDesc())): ?>
    <div class="single-product-info-desc">
    <?= $data['product']->getDesc() ?>
    <?php endif ?>
    </div>
  </div>
</section>

<section class="related-products">
  <h1>Related Products</h1>
  <div class="related-product">
    <img src="/img/products/default.jpg" />
    <h2>Product</h2>
    <p class="related-product-price">$12.34</p>
  </div>
  <div class="related-product">
    <img src="/img/products/default.jpg" />
    <h2>Product</h2>
    <p class="related-product-price">$12.34</p>
  </div>
  <div class="related-product">
    <img src="/img/products/default.jpg" />
    <h2>Product</h2>
    <p class="related-product-price">$12.34</p>
  </div>
  <div class="related-product">
    <img src="/img/products/default.jpg" />
    <h2>Product</h2>
    <p class="related-product-price">$12.34</p>
  </div>
</section>
<?php else: ?>
<div class="single-product-info-empty">
  Sorry, we can't find the product. <br />
  <a href="javascript:history.back()">Go Back</a>
</div>
<?php endif ?>
