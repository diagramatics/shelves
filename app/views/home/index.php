<div class="homepage-dash">
  <?php if (isset($_SESSION["fName"])): ?>
  <h1 class="homepage-dash-greet">Welcome, <?= $_SESSION["fName"] ?></h1>
  <?php endif ?>
</div>

<?php include_once 'promo-grid.php'; ?>
