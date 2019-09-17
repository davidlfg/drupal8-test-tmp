<?php

namespace Drupal\test_register;

/**
 * Access controller for the "Register" entity.
 */
class RegisterAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function viewPermission() {
    return 'view register';
  }

  /**
   * {@inheritdoc}
   */
  protected function updatePermission() {
    return 'update register';
  }

  /**
   * {@inheritdoc}
   */
  protected function deletePermission() {
    return 'delete register';
  }

  /**
   * {@inheritdoc}
   */
  protected function createPermission() {
    return 'create register';
  }

}
