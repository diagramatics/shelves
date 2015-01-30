<section>
  <header class="container">
    <h1>Specials</h1>
  </header>

  <table class="admin-table">
    <thead>
      <tr>
        <th></th>
        <th>Name</th>
        <th>Description</th>
        <th># Products</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['specials'] as $special): ?>
        <tr>
          <td>
            <form id="" action="?deleteSpecial" method="POST">
              <input type="hidden" name="promotionID" value="<?= $special->getID() ?>" />
              <input type="hidden" name="promotionName" value="<?= $special->getTitle() ?>" />
              <button class="button-icon admin-table-delete" title="Delete" name="delete">
                <svg class="icon-minus"><use xlink:href="#icon-minus"></use></svg>
              </button>
            </form>
            <form id="" action="/admin/specials/edit/<?= $special->getID() ?>" method="POST">
              <button class="button-icon admin-table-edit" title="Edit" name="edit">
                <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
              </button>
            </form>
          </td>
          <td><?= $special->getTitle() ?></td>
          <td><?= $special->getShortDesc() ?></td>
          <td><?= $special->countProducts() ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</section>
