<?php

namespace Drupal\test_register_ui;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder as OriginalListBuilder;

/**
 * Defines a class to build a listing of entities.
 */
class EntityListBuilder extends OriginalListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header = [];
    $header['full_name'] = $this->t('Full Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row = [];
    $row['full_name'] = $entity->get('first_name')->value . ' ' . $entity->get('last_name')->value;
    return $row + parent::buildRow($entity);
  }

}
