<?php

class SpecialsModel {
  private $title;
  private $desc;
  private $startDate;
  private $endDate;
  private $now;

  public function parse($raw) {
    $this->title = $raw->promotionTitle;
    $this->desc = $raw->promotionDesc;
    $this->startDate = new DateTime($raw->startDate);
    $this->endDate = new DateTime($raw->endDate);

    // Get the current datetime
    $this->now = new DateTime(null);
  }

  public function getTitle() {
    return $this->title;
  }
  public function getDesc() {
    return $this->desc;
  }
  public function getStartDate() {
    return $this->startDate;
  }
  public function getEndDate() {
    return $this->endDate;
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
