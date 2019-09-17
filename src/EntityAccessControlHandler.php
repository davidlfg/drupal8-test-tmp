<?php

namespace Drupal\test_register;

use Drupal\Core\Entity\EntityAccessControlHandler as OriginalAccessController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Abstract access controller for Core entities.
 */
abstract class EntityAccessControlHandler extends OriginalAccessController {

  /**
   * Returns the view permission name as string.
   *
   * @return string
   *   The permission needed to view content.
   */
  abstract protected function viewPermission();

  /**
   * Returns the update permission name as string.
   *
   * @return string
   *   The permission needed to update content.
   */
  abstract protected function updatePermission();

  /**
   * Returns the delete permission name as string.
   *
   * @return string
   *   The permission needed to delete content.
   */
  abstract protected function deletePermission();

  /**
   * Returns the create permission name as string.
   *
   * @return string
   *   The permission needed to create content.
   */
  abstract protected function createPermission();

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, $this->viewPermission());

      case 'update':
      case 'edit':
        return AccessResult::allowedIfHasPermission($account, $this->updatePermission());

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, $this->deletePermission());
    }

    // Unknown operation, leave as is.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, $this->createPermission());
  }

}
