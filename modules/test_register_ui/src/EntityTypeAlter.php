<?php

namespace Drupal\test_register_ui;

use Drupal\Core\Entity\EntityTypeInterface;

/**
 * A base class for altering entities.
 */
abstract class EntityTypeAlter {

  /**
   * Returns an array of wished modifications.
   *
   * @return array
   *   an array of modifications. Can contain list_builder settings,
   *   properties, link_templates, form_classes and handlers.
   */
  abstract protected function settings();

  /**
   * Sets the List builder if requested in $settings.
   *
   * @param array $settings
   *   The settings set by the class.
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to alter.
   */
  protected function addListBuilder(array $settings, EntityTypeInterface $entity_type) {
    if (isset($settings['list_builder'])) {
      $entity_type->setListBuilderClass($settings['list_builder']);
    }
  }

  /**
   * Sets properties if requested in $settings.
   *
   * @param array $settings
   *   The settings set by the class.
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to alter.
   */
  protected function addProperties(array $settings, EntityTypeInterface $entity_type) {
    if (isset($settings['properties'])) {
      foreach ($settings['properties'] as $key => $value) {
        $entity_type->set($key, $value);
      }
    }
  }

  /**
   * Sets Link templates if requested in $settings.
   *
   * @param array $settings
   *   The settings set by the class.
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to alter.
   */
  protected function addLinkTemplates(array $settings, EntityTypeInterface $entity_type) {
    if (isset($settings['link_templates'])) {
      foreach ($settings['link_templates'] as $key => $value) {
        $entity_type->setLinkTemplate($key, $value);
      }
    }
  }

  /**
   * Sets form classes if requested in $settings.
   *
   * @param array $settings
   *   The settings set by the class.
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to alter.
   */
  protected function addFormClasses(array $settings, EntityTypeInterface $entity_type) {
    if (isset($settings['form_classes'])) {
      foreach ($settings['form_classes'] as $key => $value) {
        $entity_type->setFormClass($key, $value);
      }
    }
  }

  /**
   * Sets handlers if requested in $settings.
   *
   * @param array $settings
   *   The settings set by the class.
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to alter.
   */
  protected function addHandlers(array $settings, EntityTypeInterface $entity_type) {
    if (isset($settings['handlers'])) {
      foreach ($settings['handlers'] as $key => $value) {
        $entity_type->setHandlerClass($key, $value);
      }
    }
  }

  /**
   * Sets main class if requested in $settings.
   *
   * @param array $settings
   *   The settings set by the class.
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to alter.
   */
  protected function addMainClass(array $settings, EntityTypeInterface $entity_type) {
    if (isset($settings['main_class'])) {
      $entity_type->setClass($settings['main_class']);
    }
  }

  /**
   * Alters information for an entity type.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type to alter.
   */
  public function alterEntityType(EntityTypeInterface $entity_type) {
    $settings = $this->settings();

    // Append list builder.
    $this->addListBuilder($settings, $entity_type);

    // Add properties.
    $this->addProperties($settings, $entity_type);

    // Add link templates.
    $this->addLinkTemplates($settings, $entity_type);

    // Add form classes.
    $this->addFormClasses($settings, $entity_type);

    // Add handlers.
    $this->addHandlers($settings, $entity_type);

    // Add main class.
    $this->addMainClass($settings, $entity_type);

  }

}
