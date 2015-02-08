<form id="editItemQuantityBag<?= $product['model']->getID() ?>" action="?manipulateBag" method="post">
  <input type="hidden" name="prodID" value="<?= $product['model']->getID() ?>">
  <input type="hidden" name="prodName" value="<?= $product['model']->getName() ?>">
  <input type="number" name="editedQty" value="<?= $product['bagQty'] ?>" min="1" max="<?= $product['model']->getQty() ?>" />
  <button class="button-icon bag-items-td-qty-edit bag-items-td-qty-edit--confirm" title="Confirm Quantity Edit" name="confirmEditItemQty">
    <svg class="icon-checkmark"><use xlink:href="#icon-checkmark"></use></svg>
  </button>
</form>
