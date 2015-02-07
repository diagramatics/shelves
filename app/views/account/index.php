<section class="container">
  <h1>Your Account</h1>

  <div class="account-orders">
    <h2>Order History</h2>
    <table>
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
            <td><?= $i++ ?></td>
            <td><?= var_dump($order) ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</section>
