<?php

namespace Drupal\d8_training;

use Drupal\Core\Database\Connection;

/**
 * Defines a storage handler class that handles the database operations.
 *
 * This is used to perform db operations related to d8_demo table.
 */
class d8TrainingDatabaseStorage implements d8TrainingDatabaseStorageInterface {


  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a GrantDatabaseStorage object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Inserts the form values in database.
   *
   * @param user input $first_name
   * @param user input $last_name
   */
  public function write($first_name, $last_name) {
    $this->database->insert('d8_demo')
      ->fields([
        'FirstName' => $first_name,
        'LastName' => $last_name,
      ])
      ->execute();
  }

  /**
   * Retrieves the last record from the database.
   */
  public function fetch() {
    $query = $this->database->query('(SELECT @row_number:=@row_number+1 AS RowNumber, FirstName,LastName FROM d8_demo, (SELECT @row_number:=0) AS t) ORDER BY RowNumber DESC');
    return $query->fetchAssoc();
  }

}
