<?php

namespace Drupal\baseball_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
/**
 * Source plugin for player appearances.
 *
 * @MigrateSource(
 *   id = "appearance"
 * )
 */
class PlayerAppearance extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Write a query using the standard Drupal query builder that will be run
    // against the {source} database to retrieve information about players. Each
    // row returned from the query should represent one item that we would like
    // to import. So, basically, one row per player.
    //
    // In this case, we can just select all rows from the master table in the
    // {source} database, and limit to just the fields we plan to migrate.
    $query = $this->select('appearances', 'a')
      ->fields('a', array(
        'yearID',
        'teamID',
        'playerID',
      ))
      ->condition('playerID', 'barnero01')
      ->range(0, 10);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    // Provide documentation about source fields that are available for
    // mapping as key/value pairs. The key is the name of the field from the
    // database, and the value is the human readable description of the field.
    $fields = array(
      'yearID' => $this->t('Year'),
      'teamID' => $this->t('Team ID'),
      'playerID' => $this->t('Player ID'),
    );

    // If using prepareRow() to create computed fields you can describe them
    // here as well.

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    // Use a Schema Definition array to describe the field from the source row
    // that will be used as the unique ID for each row.
    //
    // @link https://www.drupal.org/node/146939 - Schema array docs.
    //
    // @see \Drupal\migrate\Plugin\migrate\source\SqlBase::initializeIterator
    // for more about the 'alias' key.
    return [
      // Key is the name of the field from the fields() method above that we
      // want to use as the unique ID for each row.
      'yearID' => [
        // Type is from the schema array definition.
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 'a',
      ],
      'teamID' => [
        // Type is from the schema array definition.
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 'a',
      ],
      'playerID' => [
        // Type is from the schema array definition.
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 'a',
      ],
    ];
  }
}

