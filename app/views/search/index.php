<section class="search">
  <header>
    <h1 class="product-categories-name">Search Results</h1>
    <p class="specials-desc">There <?= count($data['results']) != 1 ? 'are' : 'is' ?> <?= count($data['results']) ?> result<?= count($data['results']) != 1 ? 's' : '' ?> from your search.</p>
  </header>

  <div class="block-grid">
    <?php foreach ($data['results'] as $product): ?>
      <div class="block-grid-item block-grid-item--normal search-item">
        <a href="<?= $product->getUrl() ?>">
          <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>" />
          <p><?= $product->getName() ?></p>
        </a>
      </div>
    <?php endforeach ?>
  </div>

  <?php if (count($data['results']) > 20 + (20 * $data['n'])): ?>
    <a href="?q=<?= $data['q'] ?>&n=<?= $data['n'] + 1 ?>">Next</a>
  <?php endif ?>
  <?php if ($data['n'] > 0): ?>
    <a href="?q=<?= $data['q'] ?>&n=<?= $data['n'] - 1 ?>">Previous</a>
  <?php endif ?>
</section>
