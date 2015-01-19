<table class="admin-table">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['categories'] as $category): ?>
      <tr>
        <td>
          <form id="" action="/admin/category/edit/<?= $category->catID ?>" method="POST">
            <button class="button-icon admin-table-edit" title="Edit" name="edit">
              <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
            </button>
          </form>
        </td>
        <td><?= $category->catName ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>
