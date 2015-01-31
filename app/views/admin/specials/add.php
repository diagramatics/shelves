<section class="container">
  <h1>Add New Special</h1>
  <form id="adminAddSpecial" action="?adminAddSpecial" method="POST">
    <input type="text" name="title" value="<?= isset($_POST['title']) ? $_POST['title'] : "" ?>" placeholder="Special Title" class="form-input-block">
    <textarea name="description" rows="8" cols="40" placeholder="Description (optional)" class="form-input-block" resize="no"><?= isset($_POST['description']) ? $_POST['description'] : "" ?></textarea>

    <fieldset id="adminAddSpecialProducts">
      <legend>Products in Special</legend>
      <input type="hidden" id="adminAddSpecialProductCount" value="<?= isset($_POST['productsCount']) ? $_POST['productsCount'] : 3 ?>" />
      <?php for ($i = 0; $i < (isset($_POST['productsCount']) ? $_POST['productsCount'] : 3); $i++): ?>
        <div class="form-input-halfblock-container">
          <select class="form-input-halfblock admin-special-add-product" name="product<?= $i+1 ?>">
            <option value="" disabled selected>Product</option>
            <?php foreach ($data['products'] as $product): ?>
              <option value="<?= $product->getID() ?>"><?= $product->getName() ?></option>
            <?php endforeach ?>
          </select>
          <input class="form-input-halfblock" placeholder="Discount (%)" name="discount<?= $i+1 ?>" />
        </div>
      <?php endfor ?>
      <button id="adminAddSpecialAddProduct" type="submit" name="productsCount" value="<?= isset($_POST['productsCount']) ? $_POST['productsCount'] + 1 : 3 + 1 ?>" class="form-input-block form-button">More Products</button>
    </fieldset>

    <input type="submit" name="confirmAddSpecial" value="Add Special" class="form-button form-input-block">
  </form>
</section>
