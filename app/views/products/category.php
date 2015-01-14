<h1 class="product-categories-name"><?= $data['catName'] ?></h1>

<section class="block-grid">
  <?php foreach($data['items'] as $item): ?>
  <div class="block-grid-item block-grid-item--normal product-category-item">
    <a class="product-category-item-link" href="/products/product/<?= $item->prodID . '/' . Helpers::makeSlug($item->prodName) ?>">
      <img class="product-category-item-image" src="/img/products/<?= $item->image ?>" alt="<?= $item->prodName ?>" />
      <div class="product-category-item-details">
        <div class="product-category-item-name"><?= $item->prodName ?></div>
        <div class="product-category-item-add"><a href="#" title="Add to Cart"><svg class="icon-plus"><use xlink:href="#icon-plus"></use></svg></a></div>
      </div>
    </a>
  </div>
  <?php endforeach ?>
</section>
