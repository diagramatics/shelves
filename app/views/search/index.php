<section class="search">
  <header>
    <h1 class="product-categories-name">Search Results</h1>
    <p class="specials-desc">There <?= $data['count'] != 1 ? 'are' : 'is' ?> <?= $data['count'] ?> result<?= $data['count'] != 1 ? 's' : '' ?> from your search.</p>
    <p class="specials-desc">Showing <?= (20 * $data['n']) + 1 ?> – <?= min(((20 * ($data['n']+1))), $data['count']) ?></p>
  </header>

  <div class="block-grid">
    <?php foreach ($data['results'] as $product): ?>
      <div class="block-grid-item block-grid-item--normal search-item">
        <a href="<?= $product->getUrl() ?>">
          <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>" />
        </a>
        <div class="search-item-details">
          <a href="<?= $product->getUrl() ?>" class="search-item-details-name-container">
            <div class="search-item-details-name"><?= $product->getName() ?></div>
          </a>
          <form action="?addBag" class="search-item-details-add" method="POST">
            <input class="product-category-item-add-qty" type="number" name="qty" value="1" min="1" max="<?= $product->getQty() ?>">
            <input type="hidden" name="itemQty" value="<?= $product->getQty() ?>">
            <input type="hidden" name="itemID" value="<?= $product->getID() ?>">
            <button class="button-icon" title="Add to Bag"><svg class="icon-plus"><use xlink:href="#icon-plus"></use></svg></button>
          </form>
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <div class="pagination">
    <?php if ($data['n'] > 0): ?>
      <a class="pagination-previous" href="?q=<?= $data['q'] ?>&n=<?= $data['n'] - 1 ?>">Previous &laquo;</a>
    <?php endif ?>
    <?php if ($data['count'] > 20 + (20 * $data['n'])): ?>
      <a class="pagination-next" href="?q=<?= $data['q'] ?>&n=<?= $data['n'] + 1 ?>">Next &raquo;</a>
    <?php endif ?>
  </div>
</section>
