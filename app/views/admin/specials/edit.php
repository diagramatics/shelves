<?php if ($data['special']['id']): ?>

<section class="container">
  <h1>Edit Special</h1>
  <form id="adminEditSpecial" action="?adminEditSpecial" method="POST">
    <input type="text" name="title" value="<?= Helpers::orEmpty($_POST['title'], $data['special']['title']) ?>" placeholder="Special Title" class="form-input-block">
    <div class="form-input-halfblock-container">
      <div class="form-input-halfblock">
        <label for="startDate">Start Date</label>
        <input class="form-input-block" type="date" placeholder="Start Date (YYYY-mm-dd)" value="<?= Helpers::orEmpty($_POST['startDate'], $data['special']['startDate']) ?>" name="startDate" min="<?= (new DateTime())->format('Y-m-d') ?>" />
      </div>
      <div class="form-input-halfblock">
        <label for="endDate">End Date</label>
        <input class="form-input-block" type="date" placeholder="End Date (YYYY-mm-dd)" value="<?= Helpers::orEmpty($_POST['endDate'], $data['special']['endDate']) ?>" name="endDate" min="<?= (new DateTime())->format('Y-m-d') ?>" />
      </div>
    </div>
    <textarea name="description" rows="8" cols="40" placeholder="Description (optional)" class="form-input-block" resize="no"><?= Helpers::orEmpty($_POST['description'], $data['special']['description']) ?></textarea>

    <fieldset id="adminEditSpecialProducts">
      <legend>Products in Special</legend>
      <input type="hidden" id="finalProductsCount" name="finalProductsCount" value="<?= Helpers::orEmpty($_POST['productsCount'], $data['special']['linksCount']) !== "" ?: 3 ?>" />
      <?php for ($i = 0; $i < (Helpers::orEmpty($_POST['productsCount'], $data['special']['linksCount']) ?: 3); $i++): ?>
        <div class="form-input-halfblock-container admin-special-add-product">
          <select class="form-input-halfblock admin-special-add-product-select" name="product<?= $i+1 ?>">
            <option <?= isset($_POST['product'. ($i+1)]) && $_POST['product'. ($i+1)] == -1 ? 'selected' : '' ?> value="-1">Select product...</option>
            <?php foreach ($data['products'] as $product): ?>
              <option <?= (Helpers::orEmpty($_POST['product'. ($i+1)]) == $product->getID() || (isset($data['special']['links'][$i]['id']) && $data['special']['links'][$i]['id'] == $product->getID())) ? 'selected' : '' ?> value="<?= $product->getID() ?>"><?= $product->getName() ?></option>
            <?php endforeach ?>
          </select>
          <input class="form-input-halfblock" placeholder="Discount (%)" name="discount<?= $i+1 ?>" value="<?= Helpers::orEmpty($_POST['discount'. ($i+1)], $data['special']['links'][$i]['discount']) ?>" />
        </div>
      <?php endfor ?>
      <button id="adminEditSpecialAddProduct" type="submit" name="productsCount" value="<?= (Helpers::orEmpty($_POST['productsCount'], $data['special']['linksCount']) !== "" ?: 3)+1 ?>" class="form-input-block form-button">More Products</button>
    </fieldset>

    <input type="submit" name="confirmEditSpecial" value="Edit Special" class="form-button form-input-block">
  </form>
</section>

<?php else: ?>
<section class="container">
  <h1>We can't find that special.</h1>
  <p>Are you sure you selected a valid special? <a href="/admin/specials">Go back to the list.</a></p>
</section>
<?php endif ?>
