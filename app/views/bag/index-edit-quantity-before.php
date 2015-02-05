<span class="bag-items-td-qty-amount"><?= $product['bagQty'] ?></span>
<button href="#" class="button-icon bag-items-td-qty-edit" title="Edit Quantity" name="editItemQty" form="manipulateBag<?= $product['model']->getID() ?>" value="<?= $product['model']->getID() ?>">
  <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
</button>
