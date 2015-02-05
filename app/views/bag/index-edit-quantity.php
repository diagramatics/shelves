<input form="manipulateBag<?= $product['model']->getID() ?>" type="number" name="editedQty" value="<?= $product['bagQty'] ?>" min="1" max="<?= $product['model']->getQty() ?>" />
<button class="button-icon bag-items-td-qty-edit bag-items-td-qty-edit--confirm" title="Confirm Quantity Edit" name="confirmEditItemQty" form="manipulateBag<?= $product['model']->getID() ?>">
  <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
</button>
