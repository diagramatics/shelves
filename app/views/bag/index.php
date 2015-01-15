<section class="bag">
  <header>
    <h1 class="bag-title">Your Shopping Bag</h1>
    <div class="bag-action">
      <p class="bag-cost"><span class="bag-cost-pre">Total cost</span><span class="bag-cost-number">
        <?= '$'.number_format($data['totalCost'], 2) ?></span></p>
        <a class="bag-checkout" <?= !empty($data['products']) ?: 'disabled' ?> href="">Checkout</a>
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
          <form id="manipulateBag<?= $product->prodID ?>" action="?manipulateBag" method="post">
            <input type="hidden" name="prodID" value="<?= $product->prodID ?>">
            <input type="hidden" name="prodName" value="<?= $product->prodName ?>">
            <button class="button-icon bag-items-manipulate" title="Remove" name="removeItem">
              <svg class="icon-minus"><use xlink:href="#icon-minus"></use></svg>
            </button>
          </form>
        </td>
        <td class="bag-items-td-image"><img src="/img/products/<?= $product->image ?>" /></td>
        <td><?= $product->prodName ?></td>
        <td class="bag-items-td-qty">
          <?php if(!isset($_POST['editItemQty'])): ?>
          <?= $product->bagQty ?>
          <button href="#" class="button-icon bag-items-td-qty-edit" title="Edit Quantity" name="editItemQty" form="manipulateBag<?= $product->prodID ?>">
            <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
          </button>
          <?php else: ?>
          <input form="manipulateBag<?= $product->prodID ?>" type="number" name="editedQty" value="<?= $product->bagQty ?>" min="1" max="<?= $product->quantity ?>" />
          <button class="button-icon bag-items-td-qty-edit bag-items-td-qty-edit--confirm" title="Edit Quantity" name="confirmEditItemQty" form="manipulateBag<?= $product->prodID ?>">
            <svg class="icon-edit"><use xlink:href="#icon-edit"></use></svg>
          </button>
          <?php endif ?>
        </td>
        <td class="bag-items-td-price"><?= '$'.number_format($product->price, 2) ?></td>
        <td class="bag-items-td-price"><?= '$'.number_format($product->price * $product->bagQty, 2) ?></td>
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
        <td colspan="2">
          <?= '$'.number_format($data['totalCost'], 2) ?>
        </td>
      </tr>
    </tfoot>
  </table>

  <footer>
    <a class="bag-checkout" <?= !empty($data['products']) ?: 'disabled' ?> href="">Checkout</a>
  </footer>

</section>
