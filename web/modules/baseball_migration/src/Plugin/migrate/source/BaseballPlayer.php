<?php

namespace Drupal\baseball_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for baseball players.
 *
 * @MigrateSource(
 *   id = "baseball_player"
 * )
 */
class BaseballPlayer extends SqlBase {

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
    $query = $this->select('master', 'm')
      ->fields('m', array(
        'playerID',
        'birthYear',
        'birthMonth',
        'birthDay',
        'deathYear',
        'deathMonth',
        'deathDay',
        'nameFirst',
        'nameLast',
        'nameGiven',
        'weight',
        'height',
        'bats',
        'throws',
        'finalGame',
        'debut'
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
      'birthYear' => $this->t('Birth year'),
      'birthMonth' => $this->t('Birth month'),
      'birthDay' => $this->t('Birth day'),
      'deathYear' => $this->t('Death year'),
      'deathMonth' => $this->t('Death month'),
      'deathDay' => $this->t('Death day'),
      'nameFirst' => $this->t('First name'),
      'nameLast' => $this->t('Last name'),
      'nameGiven' => $this->t('Given name'),
      'weight' => $this->t('Weight'),
      'height' => $this->t('Height'),
      'bats' => $this->t('Bats'),
      'throws' => $this->t('Throws'),
      'debut' => $this->t('Date of debut'),
      'finalGame' => $this->t('Date of final game'),
      'name_full' => $this->t('Name of college(s)'),
      'yearID' => $this->t('Year attended college'),
    );

    // If using prepareRow() to create computed fields you can describe them
    // here as well.

    return $fields;
  }

  /**
   * {@inheritdoc}
   */

  public function prepareRow(Row $row) {
    $query = $this->select('collegeplaying', 'cp');
    $query->join('schools', 's', 's.schoolID = cp.schoolID');
    $query->join('master', 'm', 'm.playerID = cp.playerID');
    $query->addField('s', 'name_full');
    $query->addField('cp', 'yearID');
    $query->condition('cp.playerID', $row->getSourceProperty('playerID'));

    $colleges_results = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);

    $colleges = [];
    foreach ($colleges_results as $value) {
      $colleges[] = $value['name_full'] . ' (' . $value['yearID'] . ')';
    }

    $row->setSourceProperty('name_full', $colleges);

    return parent::prepareRow($row);
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
        'alias' => 'm',
      ],
    ];
  }
}

