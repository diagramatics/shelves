<?php

class Database extends mysqli {
  private $host = 'localhost';
  private $user = 'root';
  private $password = 'root';
  private $database = 'shelves';

  public function __construct() {
    // If the getenv fails assume that it's in development and not on production server
    // and revert to development database connection values
    if (getenv("CLEARDB_DATABASE_URL") !== false) {
      $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
      $this->host = $url["host"];
      $this->user = $url["user"];
      $this->password = $url["pass"];
      $this->database = substr($url["path"], 1);
    }

    // Start connection
    parent::__construct($this->host, $this->user, $this->password, $this->database);

    if (mysqli_connect_error()) {
      die('Connect Error ('. mysqli_connect_errno() .')' . mysqli_connect_error());
    }
  }

  private function assembleSelectQuery($table, $values, $filters = []) {
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
        // Detect if the value is a string or not and add quotes to it if it is
        if (is_string($filter[2])) {
          $filter[2] = '"'.$this->real_escape_string($filter[2]).'"';
        }
        else $filter[2] = $this->real_escape_string($filter[2]);

        $filtersArrayFormatted[$i++] = $filter[0] . ' ' . $filter[1] . ' ' . $filter[2];
      }
      $filtersFormatted .= implode('AND ', $filtersArrayFormatted);
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
  public function getValue($table, $values, $filters = [], $others = []) {
    // Make the query
    $command = $this->assembleSelectQuery($table, $values, $filters);

    if (!empty($others)) {
      $command .= ' ' . implode(' ', $others);
    }

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
  public function getValues($table, $values, $filters = [], $others = []) {
    // Make the query
    $command = $this->assembleSelectQuery($table, $values, $filters);

    if (!empty($others)) {
      $command .= ' ' . implode(' ', $others);
    }

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

  private function assembleUpdateQuery($table, $values, $filters = []) {
    // UPDATE `shelves`.`Address` SET `unit`='aaaa' WHERE `addressID`='2' and`userID`='1';

    $valuesArrayFormatted = "";
    $i = 0;
    foreach($values as $value) {
      if (is_string($value[1])) {
        $value[1] = '"'.$this->real_escape_string($value[1]).'"';
      }
      else $value[1] = $this->real_escape_string($value[1]);

      $valuesArrayFormatted[$i++] = $value[0] . ' = ' . $value[1];
    }
    $valuesFormatted = implode(', ', $valuesArrayFormatted);


    // Do the same for filters IF the filters are passed in
    // Remember that $filters is an array
    $filtersFormatted = '';
    if (!empty($filters)) {
      $filtersFormatted = 'WHERE ';

      // Format the filters so it can be imploded to $filtersFormatted easily
      $filtersArrayFormatted = "";
      $i = 0;
      foreach ($filters as $filter) {
        // Detect if the value is a string or not and add quotes to it if it is
        if (is_string($filter[2])) {
          $filter[2] = '"'.$this->real_escape_string($filter[2]).'"';
        }
        else $filter[2] = $this->real_escape_string($filter[2]);

        $filtersArrayFormatted[$i++] = $filter[0] . ' ' . $filter[1] . ' ' . $filter[2];
      }
      $filtersFormatted .= implode('AND ', $filtersArrayFormatted);
    }

    $command = 'UPDATE '. $table .' SET '. $valuesFormatted . ' ' . $filtersFormatted;
    return $command;
  }

  public function updateValue($table, $values, $filters = []) {
    $command = $this->assembleUpdateQuery($table, $values, $filters);

    return $query = $this->query($command);
  }

  private function assembleInsertQuery($table, $values) {
    $valuesKeyArrayFormatted = array();
    $valuesArrayFormatted = array();
    $i = 0;
    foreach($values as $value) {
      $value[0] = $this->real_escape_string($value[0]);
      $valuesKeyArrayFormatted[$i] = $value[0];

      if (is_string($value[1])) {
        $value[1] = '"'.$this->real_escape_string($value[1]).'"';
      }
      else $value[1] = $this->real_escape_string($value[1]);
      $valuesArrayFormatted[$i++] = $value[1];
    }

    $command = 'INSERT INTO '. $table .' ('. implode(', ', $valuesKeyArrayFormatted) .') VALUES ('. implode(', ', $valuesArrayFormatted) .')';
    return $command;
  }

  public function insertValue($table, $values) {
    $command = $this->assembleInsertQuery($table, $values);
    
    return $query = $this->query($command);
  }

  private function assembleDeleteQuery($table, $filters) {
    // DELETE FROM `shelves`.`Product` WHERE `prodID`='2';$filtersFormatted = '';
    if (!empty($filters)) {
      $filtersFormatted = 'WHERE ';

      // Format the filters so it can be imploded to $filtersFormatted easily
      $filtersArrayFormatted = "";
      $i = 0;
      foreach ($filters as $filter) {
        // Detect if the value is a string or not and add quotes to it if it is
        if (is_string($filter[2])) {
          $filter[2] = '"'.$this->real_escape_string($filter[2]).'"';
        }
        else $filter[2] = $this->real_escape_string($filter[2]);

        $filtersArrayFormatted[$i++] = $filter[0] . ' ' . $filter[1] . ' ' . $filter[2];
      }
      $filtersFormatted .= implode('AND ', $filtersArrayFormatted);
    }
    else {
      die("The function Database->deleteValue() doesn't allow deleting without filters for now.");
    }

    $command = 'DELETE FROM ' . $table . ' ' . $filtersFormatted;
    return $command;
  }

  public function deleteValue($table, $filters) {
    $command = $this->assembleDeleteQuery($table, $filters);

    return $query = $this->query($command);
  }
}

?>
