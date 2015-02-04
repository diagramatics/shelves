<section class="search">
  <header>
    <h1 class="product-categories-name">Search Results</h1>
    <p class="specials-desc">There <?= $data['count'] != 1 ? 'are' : 'is' ?> <?= $data['count'] ?> result<?= $data['count'] != 1 ? 's' : '' ?> from your search.</p>
    <p class="specials-desc">Showing <?= (20 * $data['n']) + 1 ?> – <?= min((20 * (max($data['n'], 1) + 1)), $data['count']) ?></p>
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

  <?php if ($data['n'] > 0): ?>
    <a class="search-result-previous" href="?q=<?= $data['q'] ?>&n=<?= $data['n'] - 1 ?>">Previous</a>
  <?php endif ?>
  <?php if ($data['count'] > 20 + (20 * $data['n'])): ?>
    <a class="search-result-next" href="?q=<?= $data['q'] ?>&n=<?= $data['n'] + 1 ?>">Next</a>
  <?php endif ?>
</section>
