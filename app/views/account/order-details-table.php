<tr class="account-orders-table-details-row" id="accountOrderDetailsRow<?= $order['id'] ?>">
  <td colspan="5" class="account-orders-table-details">
    <table>
      <thead>
        <tr>
          <th></th>
          <th>Name</th>
          <th>Price per Item</th>
          <th>Quantity</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order['items'] as $item): ?>
          <tr>
            <td><img src="<?= $item['model']['image'] ?>" alt="<?= $item['model']['name'] ?>"></td>
            <td><?= $item['model']['name'] ?></td>
            <td>$<?= number_format($item['model']['price'], 2) ?></td>
            <td><?= $item['qty'] ?></td>
            <td>$<?= number_format($item['model']['price'] * $item['qty'], 2) ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </td>
</tr>
