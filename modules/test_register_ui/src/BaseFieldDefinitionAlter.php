<?php

namespace Drupal\test_register_ui;

use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * A class for altering entity fields.
 */
final class BaseFieldDefinitionAlter {

  /**
   * Process string fields.
   */
  protected function stringField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process string_long fields.
   */
  protected function stringLongField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'string_textarea',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process text_long fields.
   */
  protected function textLongField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'text_textarea',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process file fields.
   */
  protected function fileField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'file_generic',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process datetime fields.
   */
  protected function datetimeField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'datetime_default',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process integer fields.
   */
  protected function integerField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'number',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process boolean fields.
   */
  protected function booleanField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'boolean_checkbox',
      'label' => 'above',
      'weight' => 0,
      'settings' => [
        'display_label' => TRUE,
      ],
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process entity reference fields.
   */
  protected function entityReferenceField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'entity_reference_autocomplete',
      'label' => 'above',
      'weight' => 0,
      'settings' => [
        'match_operator' => 'CONTAINS',
        'size' => '60',
        'placeholder' => '',
      ],
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process list_integer fields.
   */
  protected function listIntegerField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'options_buttons',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process image fields.
   */
  protected function imageField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'image_image',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process list_string fields.
   */
  protected function listStringField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'options_select',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process daterange fields.
   */
  protected function daterangeField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'daterange_default',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process link fields.
   */
  protected function linkField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'link_default',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process color fields.
   */
  protected function colorFieldTypeField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'color_field_widget_defaults',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Process commercePrice fields.
   */
  protected function commercePriceField($field) {
    $field->setDisplayOptions('form', [
      'type' => 'commerce_price_default',
      'label' => 'above',
      'weight' => 0,
    ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
  }

  /**
   * Verifies whether the field should be processed.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field
   *   The field to alter.
   *
   * @return bool
   *   TRUE if should be processed, FALSE otherwise.
   */
  protected function applies(FieldDefinitionInterface $field) {
    $options = $field->getDisplayOptions('form');
    if (!isset($options['type']) || $options['type'] != 'hidden') {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Utility function converts underscores to Uppercase.
   *
   * @param string $string
   *   The string to process.
   *
   * @return string
   *   The string with all underscores replaced to uppercase.
   */
  public static function underscoresToCamelCase($string) {
    $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    $str[0] = strtolower($str[0]);
    return $str;
  }

  /**
   * Modifies a field.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field
   *   The field to alter.
   */
  public function processField(FieldDefinitionInterface &$field) {
    if (!$this->applies($field)) {
      return;
    }
    $method_name = self::underscoresToCamelCase($field->getType()) . 'Field';
    if (method_exists($this, $method_name)) {
      call_user_func([$this, $method_name], $field);
    }
  }

}
