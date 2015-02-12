<section class="container">
  <h1>Add New Special</h1>
  <form id="adminAddSpecial" action="?adminAddSpecial" method="POST">
    <input type="text" name="title" value="<?= isset($_POST['title']) ? $_POST['title'] : "" ?>" placeholder="Special Title" class="form-input-block">
    <div class="form-input-halfblock-container">
      <div class="form-input-halfblock">
        <label for="startDate">Start Date</label>
        <input class="form-input-block" type="date" placeholder="Start Date (YYYY-mm-dd)" value="<?= isset($_POST['startDate']) ? $_POST['startDate'] : '' ?>" min="<?= (new DateTime())->format('Y-m-d') ?>" name="startDate" />
      </div>
      <div class="form-input-halfblock">
        <label for="endDate">End Date</label>
        <input class="form-input-block" type="date" placeholder="End Date (YYYY-mm-dd)" value="<?= isset($_POST['endDate']) ? $_POST['endDate'] : '' ?>" name="endDate" min="<?= (new DateTime())->format('Y-m-d') ?>" />
      </div>
    </div>
    <textarea name="description" rows="8" cols="40" placeholder="Description (optional)" class="form-input-block" resize="no"><?= isset($_POST['description']) ? $_POST['description'] : "" ?></textarea>

    <fieldset id="adminAddSpecialProducts">
      <legend>Products in Special</legend>
      <input type="hidden" id="finalProductsCount" name="finalProductsCount" value="<?= isset($_POST['productsCount']) ? $_POST['productsCount'] : 3 ?>" />
      <?php for ($i = 0; $i < (isset($_POST['productsCount']) ? $_POST['productsCount'] : 3); $i++): ?>
        <div class="form-input-halfblock-container admin-special-add-product">
          <select class="form-input-halfblock admin-special-add-product-select" name="product<?= $i+1 ?>">
            <option <?= isset($_POST['product'. ($i+1)]) && $_POST['product'. ($i+1)] == -1 ? 'selected' : '' ?> value="-1">Select product...</option>
            <?php foreach ($data['products'] as $product): ?>
              <option <?= isset($_POST['product'. ($i+1)]) && $_POST['product'. ($i+1)] == $product->getID() ? 'selected' : '' ?> value="<?= $product->getID() ?>"><?= $product->getName() ?></option>
            <?php endforeach ?>
          </select>
          <input class="form-input-halfblock" placeholder="Discount (%)" name="discount<?= $i+1 ?>" value="<?= isset($_POST['discount'. ($i+1)]) ? $_POST['discount'. ($i+1)] : '' ?>" />
        </div>
      <?php endfor ?>
      <button id="adminAddSpecialAddProduct" type="submit" name="productsCount" value="<?= isset($_POST['productsCount']) ? $_POST['productsCount'] + 1 : 3 + 1 ?>" class="form-input-block form-button">More Products</button>
    </fieldset>

    <input type="submit" name="confirmAddSpecial" value="Add Special" class="form-button form-input-block">
  </form>
</section>
