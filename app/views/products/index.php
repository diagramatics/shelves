<h1 class="product-categories-name">Products</h1>

<section class="block-grid">
  <div class="block-grid-item block-grid-item--normal">
    <div></div>
  </div>
  <div class="block-grid-item block-grid-item--normal">
    <div></div>
  </div>
  <div class="block-grid-item block-grid-item--small">
    <div></div>
    <div></div>
  </div>
  <div class="block-grid-item block-grid-item--normal">
    <div></div>
  </div>
</section>
<section class="product-categories">
  <?php foreach($data['categories'] as $category): ?>
  <div class="product-category">
    <h1 class="product-category-name"><a href="/products/category/<?= $category->catID . '/' . Helpers::makeSlug($category->catName) ?>"><?= $category->catName ?></a></h1>
    <?php if(!empty($category->subs)): ?>
    <ul class="product-category-subs">
      <?php foreach($category->subs as $sub): ?>
      <li><a href="/products/subcategory/<?= $sub->subCatID . '/' . Helpers::makeSlug($sub->subCatName) ?>"><?= $sub->subCatName ?></a></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</section>
