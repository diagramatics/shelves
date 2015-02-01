<div class="container">
  <h1>Add New Category</h1>
  <form id="adminAddCategory" action="?adminAddCategory" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= Helpers::orEmpty($_POST['name']) ?>" placeholder="Name" class="form-input-block">
    <input type="submit" name="" value="Add Category" class="form-button form-input-block">
  </form>
</div>
