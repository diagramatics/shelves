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
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non autem suscipit aliquam accusamus unde, esse veniam nesciunt quaerat eligendi, iste distinctio assumenda fugit necessitatibus veritatis!</p>
      <p>Repellat commodi doloribus aut consectetur, rem sapiente eos aspernatur dolor. Facere ipsa nostrum saepe voluptatibus consequuntur blanditiis provident, minus quia incidunt sit modi laboriosam iure.</p>
      <p>Nostrum facilis nobis, enim eum ut natus, accusantium quisquam rem vel quasi voluptatem, corporis. Beatae facere, molestias dolorum nostrum tempora commodi corporis temporibus nobis sequi?</p>
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
