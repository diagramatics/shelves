<table class="admin-table">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Parent Category</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['subcategories'] as $subcategory): ?>
      <tr>
        <td>
          <form id="" action="/admin/subcategory/edit/<?= $subcategory->subCatID ?>" method="POST">
            <button class="button-icon admin-table-edit" title="Edit" name="edit">
              <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
            </button>
          </form>
        </td>
        <td><?= $subcategory->subCatName ?></td>
        <td><?= $data['categories'][$subcategory->catID]->catName ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>
