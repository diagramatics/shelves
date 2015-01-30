<form id="search-bar" class="site-search-form" action="search-bar.php" method="GET">
  <div>
    <input type="text" name="q" placeholder="Search" value="<?= empty($_GET['q']) ? '' : $_GET['q']  ?>">
    <button type="submit"><svg class="icon-search"><use xlink:href="#icon-search"></use></svg></button>
  </div>
</form>
