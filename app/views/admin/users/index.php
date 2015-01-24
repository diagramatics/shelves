<section>
  <div class="container">
    <h1>Users</h1>
  </div>
  <table class="admin-table">
    <thead>
      <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data['users'] as $user): ?>
        <tr>
          <td><?= $user->fName ?></td>
          <td><?= $user->lName ?></td>
          <td><?= $user->email ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
