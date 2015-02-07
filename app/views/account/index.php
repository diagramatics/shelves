<section class="account-overview">
  <header class="container">
    <h1>Account Overview</h1>
  </header>

  <div class="account-orders">
    <h2>Order History</h2>
    <p>Showing recents, <?= (20 * $data['oN']) + 1 ?> – <?= min(((20 * ($data['oN']+1))), $data['oCount']) ?></p>
    <table class="account-orders-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Items</th>
          <th>Total Cost</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1 ?>
        <?php foreach ($data['orders'] as $order): ?>
          <tr>
            <td><?= max(($data['oN'] * 20) + $i++, 1) ?></td>
            <td><?= $order['orderDate'] ?></td>
            <td><?= count($order['items']) ?> items</td>
            <td>$<?= number_format($order['totalCharge'], 2) ?></td>
            <td><button class="account-orders-more">Details</button></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <?php if ($data['oN'] > 0): ?>
      <a class="pagination-previous" href="?oN=<?= $data['oN'] - 1 ?>">Previous &laquo;</a>
    <?php endif ?>
    <?php if ($data['oCount'] > 20 + (20 * $data['oN'])): ?>
      <a class="pagination-next" href="?oN=<?= $data['oN'] + 1 ?>">Next &raquo;</a>
    <?php endif ?>
  </div>
</section>
