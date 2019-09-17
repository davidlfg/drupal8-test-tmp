<?php

namespace Drupal\test_register;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides methods for create taxonomy terms of departments and cities.
 */
class PrepareData implements ContainerInjectionInterface {

  /**
   * The Entity Type Manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * The vacabulary.
   *
   * @var string
   */
  private $vocabulary;

  /**
   * Constructs the class instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function importCities($vid) {
    $this->vocabulary = $vid;
    $path = drupal_get_path('module', 'test_register');
    $json = file_get_contents($path . '/resources/colombia.json');
    if (!$json) {
      return;
    }
    $departments = Json::decode($json, TRUE);

    // Create departments terms.
    foreach ($departments as $department) {
      $taxonomy_term = $this->createCities($department['department']);
      if (isset($department['cities'])) {
        foreach ($department['cities'] as $citie) {
          $this->createCities($citie, $taxonomy_term->id());
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createCities($name, $parent = NULL) {
    $taxonomy_term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $taxonomy_term = $taxonomy_term_storage->create([
      'vid' => $this->vocabulary,
      'name' => $name,
    ]);
    if (!is_null($parent)) {
      $taxonomy_term->set('parent', $parent);
    }
    // Excludes errors.
    if ($taxonomy_term->validate()->count() === 0) {
      $taxonomy_term->save();
      return $taxonomy_term;
    }
  }

}
