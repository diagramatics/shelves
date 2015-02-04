<div class="site-footer-directory">
  <?php foreach ($data['footerCategories'] as $category): ?>
    <div class="site-footer-directory-category">
      <p class="site-footer-directory-category-title"><a href="<?= $category['url'] ?>"><?= $category['name'] ?></a></p>
      <ul class="site-footer-directory-list">
        <?php foreach ($category['subcategories'] as $subcategory): ?>
          <li><a href="<?= $subcategory['url'] ?>"><?= $subcategory['name'] ?></a></li>
        <?php endforeach ?>
      </ul>
    </div>
  <?php endforeach ?>
</div>
