<?php

class Database extends mysqli {
  private $host = 'localhost';
  private $user = 'root';
  private $password = 'root';
  private $database = 'shelves';

  public function __construct() {
    // If the getenv fails assume that it's in development and not on production server
    // and revert to development database connection values
    if ($this->host = getenv('CLEARDB_DATABASE_URL') === false) {
      $this->host = 'localhost';
    }
    if ($this->user = getenv('DB_USER') === false) {
      $this->user = 'root';
    }
    if ($this->password = getenv('DB_PASSWORD') === false) {
      $this->password = 'root';
    }
    if ($this->database = getenv('DB_DATABASE') === false) {
      $this->database = 'shelves';
    }

    // Start connection
    parent::__construct($this->host, $this->user, $this->password, $this->database);

    if (mysqli_connect_error()) {
      die('Connect Error ('. mysqli_connect_errno() .')' . mysqli_connect_error());
    }
  }

  private function assembleQuery($table, $values, $filters = []) {
    // If values are empty set $valuesFormatted to all
    if ($values === "") {
      $valuesFormatted = "*";
    }
    else {
      // Break the values with comma separation and escape it for security measures
      $valuesFormatted = $this->real_escape_string(implode(', ', $values));
    }

    // Do the same for filters IF the filters are passed in
    // Remember that $filters is an array
    $filtersFormatted = '';
    if (!empty($filters)) {
      $filtersFormatted = 'WHERE ';

      // Format the filters so it can be imploded to $filtersFormatted easily
      $filtersArrayFormatted = "";
      $i = 0;
      foreach ($filters as $filter) {
        $filtersArrayFormatted[$i++] = $filter[0] . ' ' . $filter[1] . ' ' . $this->real_escape_string($filter[2]);
      }
      $filtersFormatted .= implode(', ', $filtersArrayFormatted);
    }

    $command = 'SELECT '. $valuesFormatted .' FROM '. $table . ' ' . $filtersFormatted;
    return $command;
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
    // Make the query
    $command = $this->assembleQuery($table, $values, $filters);

    // Then query the whole thing and save it into a variable
    $query = $this->query($command . " LIMIT 1");


    if ($query === false) {
      return $query;
    }
    else {
      // Now get only one row of result
      $result = $query->fetch_object();
      $query->close();
      return $result;
    }
  }

  /**
  * Gets all values from the database table the simpler way
  * Use this when you expect two or more rows returned from the query, otherwise
  * see getValue()
  *
  * @param     string $table The table name
  * @param     array $values The value(s)
  * @param     array $filters The filter(s) in SQL query format
  * @return    array The result
  */
  public function getValues($table, $values, $filters = []) {
    // Make the query
    $command = $this->assembleQuery($table, $values, $filters);

    // Then query the whole thing and save it into a variable to be returned later
    $query = $this->query($command);

    // Now get rows of result
    if ($query === false) {
      return $query;
    }
    else {
      // TODO: Have a look at optimising this particular way of getting rows
      for ($result = array(); $tmp = $query->fetch_object();) $result[] = $tmp;
      $query->close();
      return $result;
    }
  }

  /**
  * Gets a value from the database table the simpler way, but this time close it after
  * Note: only use this to dynamically fetch content from AJAX requests, not on
  * initial PHP execution.
  *
  * @param     string $table The table name
  * @param     array $values The value(s)
  * @param     array $filters The filter(s) in SQL query format
  * @return    array The result
  */
  public function getValueAndClose($table, $values, $filters = []) {
    // FIXME: Still unusable
    $result = $this->getValue($table, $values, $filters);
    $this->close();
    return $result;
  }
}

?>
