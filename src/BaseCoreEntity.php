<?php

namespace Drupal\test_register;

use Drupal\Core\Entity\ContentEntityBase;

/**
 * Base class all core entities extend.
 */
class BaseCoreEntity extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $route_parameters = parent::urlRouteParameters($rel);
    // We can't do Dependency Injection from a Drupal entity class.
    \Drupal::service('module_handler')->alter('route_parameters', $route_parameters, $this);
    return $route_parameters;
  }

}
