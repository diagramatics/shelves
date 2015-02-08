<section class="bag">
  <header>
    <h1 class="bag-title">Your Shopping Bag</h1>
    <div class="bag-action">
      <p class="bag-cost">
        <span class="bag-cost-pre">Total cost</span>
        <span class="bag-cost-number bag-total-price"><?= '$'.number_format($data['totalCost'], 2) ?></span>
      </p>
      <a class="bag-checkout" <?= !empty($data['products']) ? '' : 'disabled' ?> href="/bag/checkout">Checkout</a>
    </div>
  </header>

  <table class="bag-items">
    <thead>
      <tr>
        <th rowspan="2">
        </th>
        <th colspan="2" rowspan="2">
          Item
        </th>
        <th rowspan="2">
          <abbr title="Quantity">Qty.</abbr>
        </th>
        <th colspan="2">
          Price
        </th>
      </tr>
      <tr>
        <th>
          Per Unit
        </th>
        <th>
          Total
        </th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($data['products'])): ?>
      <?php foreach($data["products"] as $product): ?>
      <tr>
        <td>
          <form id="manipulateBag<?= $product['model']->getID() ?>" class="form-bag-item-manipulate" action="?manipulateBag" method="post">
            <input type="hidden" name="prodID" value="<?= $product['model']->getID() ?>">
            <input type="hidden" name="prodName" value="<?= $product['model']->getName() ?>">
            <button class="button-icon bag-items-manipulate bag-items-remove" title="Remove" name="removeItem">
              <svg class="icon-minus"><use xlink:href="#icon-minus"></use></svg>
            </button>
          </form>
        </td>
        <td class="bag-items-td-image"><img src="<?= $product['model']->getImage() ?>" alt="<?= $product['model']->getName() ?>" /></td>
        <td><?= $product['model']->getName() ?></td>
        <td class="bag-items-td-qty">
          <?php if(!isset($_POST['editItemQty']) || $_POST['editItemQty'] != $product['model']->getID()): ?>
            <?php require 'index-edit-quantity-before.php'; ?>
          <?php else: ?>
            <?php require 'index-edit-quantity.php'; ?>
          <?php endif ?>
        </td>
        <td class="bag-items-td-price"><?= '$'.number_format($product['model']->getPrice(), 2) ?></td>
        <td class="bag-items-td-price-total"><?= '$'.number_format($product['model']->getPrice() * $product['bagQty'], 2) ?></td>
      </tr>
      <?php endforeach ?>
      <?php else: ?>
      <tr>
        <td class="bag-empty" colspan="100%">You have nothing on your shopping bag yet. <br/>Add some things to buy!</td>
      </tr>
      <?php endif ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3"></td>
        <td>
          Total cost:
        </td>
        <td colspan="2" class="bag-total-price"><?= '$'.number_format($data['totalCost'], 2) ?></td>
      </tr>
    </tfoot>
  </table>

  <footer>
    <a class="bag-checkout" <?= !empty($data['products']) ? '' : 'disabled' ?> href="/bag/checkout">Checkout</a>
  </footer>

</section>
