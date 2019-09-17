<?php

namespace Drupal\test_register_ui;

use Drupal\test_register_ui\Form\RegisterForm;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;

/**
 * A class for altering the Register entity.
 */
class RegisterEntityTypeAlter extends EntityTypeAlter {

  /**
   * {@inheritdoc}
   */
  protected function settings() {
    return [
      'properties' => [
        'admin_permission' => 'administer register',
        'field_ui_base_route' => 'entity.register.collection',
      ],
      'form_classes' => [
        'default' => RegisterForm::class,
        'delete' => ContentEntityDeleteForm::class,
      ],
      'link_templates' => [
        'collection' => '/admin/structure/register',
        'add-form' => '/admin/structure/register/add',
        'edit-form' => '/admin/structure/register/{register}',
        'delete-form' => '/admin/structure/register/{register}/delete',
      ],
      'handlers' => [
        'list_builder' => EntityListBuilder::class,
        'route_provider' => [
          'html' => AdminHtmlRouteProvider::class,
        ],
      ],
    ];
  }

}
