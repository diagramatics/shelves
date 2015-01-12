<h1 class="product-categories-name"><?= $data['catName'] ?></h1>

<section class="block-grid">
  <?php foreach($data['items'] as $item): ?>
  <div class="block-grid-item block-grid-item--normal">
    <a href="/products/product/<?= $item->prodID . '/' . Helpers::makeSlug($item->prodName) ?>">
      <img src="/img/products/<?= $item->image ?>" alt="<?= $item->prodName ?>" />

    </a>
  </div>
  <?php endforeach ?>
</section>
