<?php

class SpecialsModel {
  private $id;
  private $title;
  private $desc;
  private $startDate;
  private $endDate;
  private $now;
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

  public function extract() {
    return array(
      'id' => $this->getID(),
      'title' => $this->getTitle(),
      'desc' => $this->getDesc(),
      'shortDesc' => $this->getShortDesc(),
      'longDesc' => $this->getLongDesc(),
      'startDate' => $this->getStartDateFormattedSQL(),
      'endDate' => $this->getEndDateFormattedSQL(),
      'linksCount' => $this->countProducts(),
      'links' => $this->getProductLinksArray()
    );
  }

  public function linkProducts($raw) {
    $this->links = array();
    foreach ($raw as $product) {
      array_push($this->links, $product);
    }
  }

  public function countProducts() {
    return count($this->links);
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
  public function getStartDateFormattedSQL() {
    return $this->startDate->format('Y-m-d');
  }
  public function getEndDate() {
    return $this->endDate;
  }
  public function getEndDateFormatted() {
    return $this->endDate->format('d/m/Y');
  }
  public function getEndDateFormattedSQL() {
    return $this->endDate->format('Y-m-d');
  }
  public function getProductLinks() {
    return $this->links;
  }
  public function getProductLinksArray() {
    $t = array();
    if (!empty($this->links)) {
      foreach ($this->links as $link) {
        array_push($t, array(
          'id' => $link->prodID,
          'discount' => $link->discount
        ));
      }
    }
    return $t;
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
