<table class="admin-table">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Products</th>
      <th>Parent Category</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['subcategories'] as $subcategory): ?>
      <tr>
        <td>
          <form id="" action="?adminDeleteSubCategory" method="POST">
            <input type="hidden" name="subCatID" value="<?= $subcategory->subCatID ?>" />
            <input type="hidden" name="subCatName" value="<?= $subcategory->subCatName ?>" />
            <input type="hidden" name="products" value="<?= empty($subcategory->products) ? "0" : $subcategory->products ?>" />
            <button class="button-icon admin-table-delete" title="Delete" name="delete">
              <svg class="icon-minus"><use xlink:href="#icon-minus"></use></svg>
            </button>
          </form>
          <form id="" action="/admin/subcategory/edit/<?= $subcategory->subCatID ?>" method="POST">
            <button class="button-icon admin-table-edit" title="Edit" name="edit">
              <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
            </button>
          </form>
        </td>
        <td><?= $subcategory->subCatName ?></td>
        <td><?= $subcategory->products ?></td>
        <td><?= $data['categories'][$subcategory->catID]->catName ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>
