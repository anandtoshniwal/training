<?php

namespace Drupal\d8_training;

/**
 * Provides an interface for node access grant storage.
 */
interface d8TrainingDatabaseStorageInterface {

  /**
   * Creates the default node access grant entry.
   *
   * @param User input $first_name
   * @param User input $last_name
   */
  public function write($first_name, $last_name);

  /**
   * @return Last inserted data in d8_demo table.
   */
  public function fetch();

}
