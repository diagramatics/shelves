<table class="admin-table">
  <thead>
    <tr>
      <th></th>
      <th colspan="2">Name</th>
      <th>Price</th>
      <th>Unit</th>
      <th>Stock</th>
      <th>Category</th>
      <th>Subcategory</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['products'] as $product): ?>
      <tr>
        <td>
          <form id="" action="?deleteProduct" method="POST">
            <input type="hidden" name="prodID" value="<?= $product->prodID ?>" />
            <input type="hidden" name="prodName" value="<?= $product->prodName ?>" />
            <button class="button-icon admin-table-delete" title="Delete" name="delete">
              <svg class="icon-minus"><use xlink:href="#icon-minus"></use></svg>
            </button>
          </form>
          <form id="" action="/admin/product/edit/<?= $product->prodID ?>" method="POST">
            <button class="button-icon admin-table-edit" title="Edit" name="edit">
              <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
            </button>
          </form>
        </td>
        <td><img src="/img/products/<?= $product->image ?>" /></td>
        <td><?= $product->prodName ?></td>
        <td><?= '$'.number_format($product->price, 2) ?></td>
        <td><?= $product->priceUnit ?></td>
        <td><?= $product->quantity ?></td>
        <td><?= $data['categories'][$product->catID]->catName ?></td>
        <td><?= Helpers::orEmpty($data['subcategories'][$product->subCatID]->subCatName) ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>
