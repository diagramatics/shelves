<section class="single-product">
  <div class="single-product-info single-product-info--left">
    <img src="<?= $data['image'] ?>" class="product-image" />
  </div>
  <div class="single-product-info single-product-info--right">
    <header>
      <h1 class="single-product-name"><?= $data['name'] ?></h1>
      <p class="single-product-price">
        <span class="single-product-price-number">$<?= $data['price'] ?></span><span class="single-product-price-unit"><?= (!empty($data['priceUnit']) ? '/'.$data['priceUnit'] : '') ?></span>
      </p>
    </header>

    <form id="" action="" class="single-product-add" method="POST">
      <input type="number" name="" value="1" min="1" max="100">
      <button>
        <span>Add to Bag</span>
        <svg class="icon-bag"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-bag"></use></svg>
      </button>
    </form>

    <div class="single-product-info-desc">
    <?= $data['desc'] ?>
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
