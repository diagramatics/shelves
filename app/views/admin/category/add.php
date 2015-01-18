<?php
if (isset($_POST['adminAddCategory'])) {
  if ($_POST['adminAddCategory'] === true) {
    Helpers::makeAlert('adminCategory', "Successfully added to category list.");
  }
  elseif ($_POST['adminAddCategory'] === false) {
    Helpers::makeAlert('adminCategory', "There's something wrong with adding the category. Please try again.");
  }
}
?>

<div class="container">
  <h1>Add New Category</h1>
  <form id="" action="?adminAddCategory" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= Helpers::orEmpty($_POST['name']) ?>" placeholder="Name" class="form-input-block">
    <input type="submit" name="" value="Add Category" class="form-button form-input-block">
  </form>
</div>
