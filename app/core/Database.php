<?php

class Database extends mysqli {
  private $host = 'localhost';
  private $user = 'root';
  private $password = 'root';
  private $database = 'scotchbox';

  public function __construct() {
    parent::__construct($this->host, $this->user, $this->password, $this->database);

    if (mysqli_connect_error()) {
      die('Connect Error ('. mysqli_connect_errno() .')'
      . mysqli_connect_error());
    }
  }

  /**
   * Gets a value from the database table the simpler way
   *
   * @param     string $table The table name
   * @param     array $values The value(s)
   * @param     array $filters The filter(s) in SQL query format
   * @return    array The result
   */
  public function getValue($table, $values, $filters = []) {
    // Break the values with comma separation and escape it for security measures
    $valuesFormatted = $this->real_escape_string(implode(', ', $values));

    // Do the same for filters IF the filters are passed in
    if (empty($filters)) {
      $filters = ' WHERE '. $this->real_escape_string(implode(', ', $filters));
    }

    // Then query the whole thing and save it into a variable to be returned later
    $result = $this->query('SELECT '. $valuesFormatted .' FROM '. $table . $filters);

    // Before returning, free memory
    $result->free();
    return $result;
  }

  /**
  * Gets a value from the database table the simpler way, but this time close it after
  *
  * @param     string $table The table name
  * @param     array $values The value(s)
  * @param     array $filters The filter(s) in SQL query format
  * @return    array The result
  */
  public function getValueAndClose($table, $values, $filters = []) {
    $result = $this->getValue($table, $values, $filters);
    $this->close();
    return $result;
  }

  public function close() {
    $this->close();
  }
}

?>
