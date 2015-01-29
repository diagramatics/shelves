<?php

class SpecialsModel {
  private $id;
  private $title;
  private $desc;
  private $startDate;
  private $endDate;
  private $now;
  private $products;
  private $links;

  public function parse($raw) {
    $this->id = $raw->promotionID;
    $this->title = $raw->promotionTitle;
    $this->desc = $raw->promotionDesc;
    $this->startDate = new DateTime($raw->startDate);
    $this->endDate = new DateTime($raw->endDate);

    // Get the current datetime
    $this->now = new DateTime(null);
  }

  public function linkProducts($raw) {
    $this->links = array();
    foreach ($raw as $product) {
      array_push($this->links, $product);
    }
  }

  public function getID() {
    return $this->id;
  }
  public function getTitle() {
    return $this->title;
  }
  public function getDesc() {
    return $this->desc;
  }
  public function getShortDesc() {
    return substr($this->desc, 0, 100);
  }
  public function getLongDesc() {
    return substr($this->desc, 0, 250);
  }
  public function getStartDate() {
    return $this->startDate;
  }
  public function getStartDateFormatted() {
    return $this->startDate->format('d/m/Y');
  }
  public function getEndDate() {
    return $this->endDate;
  }
  public function getEndDateFormatted() {
    return $this->endDate->format('d/m/Y');
  }
  public function getProductLinks() {
    return $this->links;
  }

  public function isNotStarted() {
    return $this->now < $this->startDate;
  }
  public function isRunning() {
    return ($this->now > $this->startDate) && ($this->now < $this->endDate);
  }
  public function isFinished() {
    return $this->now > $this->endDate;
  }
}

?>
