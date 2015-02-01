<div class="container">
  <h1>Add New Subcategory</h1>
  <form id="adminAddSubCategory" action="?adminAddSubCategory" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= Helpers::orEmpty($_POST['name']) ?>" placeholder="Name" class="form-input-block">
    <label for="category">Parent category:</label>
    <select name="category" class="form-input-block">
      <?php foreach ($data['categories'] as $category): ?>
        <option value="<?= $category->catID ?>" <?= $category->catID != Helpers::orEmpty($_POST['category']) ?: 'selected' ?>><?= $category->catName ?></option>
      <?php endforeach ?>
    </select>
    <input type="submit" name="" value="Add Subcategory" class="form-button form-input-block">
  </form>
</div>
