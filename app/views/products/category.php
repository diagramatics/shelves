<h1 class="product-categories-name"><?= $data['catName'] ?></h1>

<section class="block-grid">
  <?php foreach($data['items'] as $item): ?>
  <div class="block-grid-item block-grid-item--normal product-category-item">

    <a class="product-category-item-link" href="/products/product/<?= $item->prodID . '/' . Helpers::makeSlug($item->prodName) ?>">
      <img class="product-category-item-image" src="/img/products/<?= $item->image ?>" alt="<?= $item->prodName ?>" />
    </a>
    <div class="product-category-item-details">
      <a class="product-category-item-name-link" href="/products/product/<?= $item->prodID . '/' . Helpers::makeSlug($item->prodName) ?>">
        <div class="product-category-item-name"><?= $item->prodName ?></div>
      </a>
      <form action="?addBag" class="product-category-item-add" method="POST">
        <input class="product-category-item-add-qty" type="number" name="qty" value="1" min="1" max="<?= $item->quantity ?>">
        <input type="hidden" name="itemQty" value="<?= $item->quantity ?>">
        <input type="hidden" name="itemID" value="<?= $item->prodID ?>">
        <button class="button-icon" title="Add to Bag"><svg class="icon-plus"><use xlink:href="#icon-plus"></use></svg></button>
      </form>
    </div>
  </div>
  <?php endforeach ?>
</section>
