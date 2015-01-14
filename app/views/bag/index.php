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
        <td><img src="/img/products/<?= $product->image ?>" /></td>
        <td><?= $product->prodName ?></td>
        <td><?= $product->bagQty ?></td>
        <td><?= '$'.number_format($product->price, 2) ?></td>
        <td><?= '$'.number_format($product->price * $product->bagQty, 2) ?></td>
      </tr>
      <?php endforeach ?>
      <?php else: ?>
      <tr>
        <td class="bag-empty" colspan="5">You have nothing on your shopping bag yet. <br/>Add some things to buy!</td>
      <?php endif ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2"></td>
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
