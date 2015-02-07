<?php

class OrderModel extends Model {
  private $id;
  private $userID;
  private $totalCharge;
  private $orderDate;
  private $deliverDate;
  private $addressID;
  private $notes;
  private $items;

  public function parse($raw) {
    $this->setID($raw->orderBagID);
    $this->setUserID($raw->userID);
    $this->setTotalCharge($raw->totalCharge);
    $this->setOrderDate($raw->dateMade);
    $this->setDeliverDate($raw->dateDelivered);
    $this->setAddressID($raw->addressID);
    $this->setNotes($raw->orderBagNotes);
    $this->parseItems();
  }
  public function extract() {
    return array(
      'id' => $this->getID(),
      'userID' => $this->getUserID(),
      'totalCharge' => $this->getTotalCharge(),
      'orderDate' => $this->getOrderDate(),
      'deliverDate' => $this->getDeliverDate(),
      'address' => $this->getAddress(),
      'notes' => $this->getNotes(),
      'items' => $this->getItems()
    );
  }


  public function getID() {
    return $this->id;
  }
  public function setID($id) {
    $this->id = $id;
  }


  public function getUserID() {
    return $this->userID;
  }
  public function setUserID($userID) {
    $this->userID = $userID;
  }


  public function getTotalCharge() {
    return $this->totalCharge;
  }
  public function setTotalCharge($totalCharge) {
    $this->totalCharge = $totalCharge;
  }


  public function getOrderDate() {
    return $this->orderDate;
  }
  public function setOrderDate($orderDate) {
    $this->orderDate = $orderDate;
  }


  public function getDeliverDate() {
    return $this->deliverDate;
  }
  public function setDeliverDate($deliverDate) {
    $this->deliverDate = $deliverDate;
  }


  public function getAddressID() {
    return $this->addressID;
  }
  public function setAddressID($addressID) {
    $this->addressID = $addressID;
  }
  public function getAddress() {
    $address = $this->database->getValue("Address", "", array(
      ['userID', '=', $this->getUserID()]
    ));
    if ($address) {
      $model = $this->model("AddressModel");
      $model->parse($address);
      return $model->extract();
    }
    else {
      return NULL;
    }
  }


  public function getNotes() {
    return $this->notes;
  }
  public function setNotes($notes) {
    $this->notes = $notes;
  }


  private function parseItems() {
    $items = array();
    $itemsRaw = $this->database->getValues("OrderBagList", "", array(
      ['orderBagID', '=', $this->getID()]
    ));

    if ($itemsRaw) {
      foreach ($itemsRaw as $itemRaw) {
        $model = $this->model("ProductModel");
        $itemRawQuery = $this->database->getValue("Product", "", array(
          ['prodID', '=', $itemRaw->prodID]
        ));
        if ($itemRawQuery) {
          $model->parse($itemRawQuery);
        }
        array_push($items, array(
          'model' => $model,
          'qty' => $itemRaw->quantity
        ));
      }
    }
    $this->items = $items;
  }
  public function getItems() {
    return $this->items;
  }
}

?>
