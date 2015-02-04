<?php

class CategoryModel extends Model {
  private $catID;
  private $catName;
  private $items;
  private $subcategories;

  public function parse($row, $items = "", $subcategories = "") {
    $this->setCatID($row->catID);
    $this->setCatName($row->catName);

    if ($items === "get") {
      $values = $this->database->getValues("Product", "", [
        ['catID', '=', $row->catID]
      ]);
      if ($values) {
        $this->setItems($values);
      }
    }
    else if (!empty($items)) {
      $this->setItems($items);
    }
    if ($subcategories == "get") {
      $values = $this->database->getValues("SubCategory", "", [
        ['catID', '=', $row->catID]
      ]);
      if ($values) {
        $subcategories = array();
        foreach ($values as $raw) {
          $model = $this->model("SubCategoryModel");
          $model->parse($raw);
          array_push($subcategories, $model->extract());
        }
        $this->setSubCategories($subcategories);
      }
    }
    else if (!empty($subcategories)) {
      $this->setSubCategories($subcategories);
    }
  }

  public function extract() {
    $array = array(
      "id" => $this->getCatID(),
      "name" => $this->getCatName(),
      "url" => '/products/category/' . $this->getCatID() . '/' . Helpers::makeSlug($this->getCatName())
    );

    if (!empty($this->getItems())) {
      $array['items'] = $this->getItems();
    }

    if (!empty($this->getSubCategories())) {
      $array['subcategories'] = $this->getSubCategories();
    }

    return $array;
  }

  public function getCatID() {
    return $this->catID;
  }
  public function setCatID($catID) {
    $this->catID = $catID;
  }

  public function getCatName() {
    return $this->catName;
  }
  public function setCatName($catName) {
    $this->catName = $catName;
  }

  public function getItems() {
    return $this->items;
  }
  public function setItems($items) {
    $this->items = $items;
  }

  public function getSubCategories() {
    return $this->subcategories;
  }
  public function setSubCategories($subcategories) {
    $this->subcategories = $subcategories;
  }
}

?>
