<table class="admin-table">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Products</th>
      <th>Subcategories</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['categories'] as $category): ?>
      <tr>
        <td>
          <form id="" action="?adminDeleteCategory" method="POST">
            <input type="hidden" name="catID" value="<?= $category->catID ?>" />
            <input type="hidden" name="catName" value="<?= $category->catName ?>" />
            <input type="hidden" name="products" value="<?= empty($category->products) ? "0" : $category->products ?>" />
            <input type="hidden" name="subCats" value="<?= empty($category->subCats) ? "0" : $category->subCats ?>" />
            <button class="button-icon admin-table-delete" title="Delete" name="delete">
              <svg class="icon-minus"><use xlink:href="#icon-minus"></use></svg>
            </button>
          </form>
          <form id="" action="/admin/category/edit/<?= $category->catID ?>" method="POST">
            <button class="button-icon admin-table-edit" title="Edit" name="edit">
              <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
            </button>
          </form>
        </td>
        <td><?= $category->catName ?></td>
        <td><?= empty($category->products) ? "0" : $category->products ?></td>
        <td><?= empty($category->subCats) ? "0" : $category->subCats ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>
