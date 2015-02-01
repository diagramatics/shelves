<?php if ($_POST['adminEditSubCategory'] === 'stumbled'): ?>
  <div class="container">
    <h1>Whoops.</h1>
    <p>
      Seems like you come to this page from nowhere. Please go back to the <a href="/admin/category">category list page</a> and select the one you're editing again.
    </p>
  </div>
<?php else: ?>
  <div class="container">
    <h1>Edit Category</h1>
    <form id="adminEditSubCategory" action="?adminEditSubCategory" method="POST" enctype="multipart/form-data">
      <label for="name">Category Name:</label>
      <input type="text" name="name" value="<?= Helpers::orEmpty($_POST['name'], $data['name']) ?>" placeholder="Name" class="form-input-block">
      <select name="category" class="form-input-block">
        <?php foreach ($data['categories'] as $category): ?>
          <option value="<?= $category->catID ?>" <?= $category->catID != Helpers::orEmpty($_POST['category'], $data['category']) ?: 'selected' ?>><?= $category->catName ?></option>
        <?php endforeach ?>
      </select>
      <input type="submit" name="" value="Edit Category" class="form-button form-input-block">
    </form>
  </div>
<?php endif; ?>
