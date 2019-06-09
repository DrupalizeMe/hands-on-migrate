<?php

namespace Drupal\baseball_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
/**
 * Source plugin for player appearances.
 *
 * @MigrateSource(
 *   id = "player_award"
 * )
 */
class PlayerAward extends SqlBase {

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
    $query = $this->select('awardsplayers', 'awp')
      ->fields('awp', array(
        'playerID',
        'awardID',
        'yearID',
        'lgID',
        'notes',
      ));
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
      'playerID' => $this->t('Player ID'),
      'awardID' => $this->t('Award ID'),
      'yearID' => $this->t('Year of award'),
      'lgID' => $this->t('League ID'),
      'notes' => $this->t('Notes')
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
      'playerID' => [
        // Type is from the schema array definition.
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 'awp',
      ],
      'awardID' => [
        // Type is from the schema array definition.
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 'awp',
      ],
      'yearID' => [
        // Type is from the schema array definition.
        'type' => 'text',
        // This is an optional key currently only used by SqlBase.
        'alias' => 'awp',
      ],
    ];
  }
}

