<div class="container">
  <h1>Add New Product</h1>
  <form id="adminAddProduct" action="?adminAddProduct" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= Helpers::orEmpty($_POST['name']) ?>" placeholder="Name" class="form-input-block">
    <div class="form-input-halfblock-container">
      <input type="text" name="price" value="<?= Helpers::orEmpty($_POST['price']) ?>" placeholder="Price" class="form-input-halfblock">
      <input type="text" name="priceUnit" value="<?= Helpers::orEmpty($_POST['priceUnit']) ?>" placeholder="Price Unit (optional)" class="form-input-halfblock">
    </div>
    <label for="quantity">Quantity</label><input type="number" name="quantity" value="<?= empty(Helpers::orEmpty($_POST['quantity'])) ? '100' : Helpers::orEmpty($_POST['quantity']) ?>" placeholder="Stock (Quantity)" min="0" class="form-input-block">
    <label for="category">Category:</label>
    <select name="category" class="form-input-block">
      <?php foreach ($data['categories'] as $category): ?>
      <option value="<?= $category->catID ?>" <?= $category->catID != Helpers::orEmpty($_POST['category']) ?: 'selected' ?>><?= $category->catName ?></option>
      <?php endforeach ?>
    </select>
    <label for="subcategory">Subcategory:</label>
    <select name="subcategory" class="form-input-block admin-product-subcategory">
      <option value="0-0">None</option>
      <?php foreach ($data['categories'] as $category): ?>
      <optgroup label="<?= $category->catName ?>">
        <?php foreach ($data['subcategories'] as $subcategory): ?>
        <?php if ($subcategory->catID == $category->catID): ?>
        <option value="<?= $category->catID . '-' . $subcategory->subCatID ?>" <?= $category->catID . '-' . $subcategory->subCatID != Helpers::orEmpty($_POST['subcategory']) ?: 'selected' ?>><?= $subcategory->subCatName ?></option>
        <?php endif ?>
        <?php endforeach ?>
      </optgroup>
      <?php endforeach ?>
    </select>
    <label for="image">Upload Image</label>
    <input type="file" name="image" value="" placeholder="Upload Image">
    <textarea name="description" rows="8" cols="40" placeholder="Description (optional)" class="form-input-block" resize="no"><?= Helpers::orEmpty($_POST['description']) ?></textarea>
    <input type="submit" name="" value="Add Product" class="form-button form-input-block">
  </form>
</div>
