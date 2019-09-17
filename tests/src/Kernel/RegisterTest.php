<?php

namespace Drupal\Tests\test_register\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Verifies and tests the Resgister entity.
 *
 * @group test_register
 */
class RegisterTest extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'datetime',
    'options',
    'taxonomy',
    'telephone',
    'test_register',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('register');
    $this->installEntitySchema('taxonomy_term');
  }

  /**
   * Validates existance of the Register entity.
   */
  public function testRegister() {
    // Create city.
    $term_storage = $this->container->get('entity.manager')->getStorage('taxonomy_term');
    $city = $term_storage->create([
      'name' => 'Medellin',
      'vid' => 'city',
    ]);
    $city->save();

    // Create the Register.
    $register_storage = $this->container->get('entity.manager')->getStorage('register');

    $register = $register_storage->create([
      'first_name' => 'Juan',
      'last_name' => 'Lopez Zuluaga',
      'gender' => 'male',
      'birthdate' => gmdate('Y-m-d', mktime(0, 0, 0, 10, 12, 1992)),
      'city' => [$city->id()],
      'phone_number' => '3219385421',
    ]);

    $register->save();

    // Test whether Register belongs to the expected module.
    $this->assertEqual(get_class($register), 'Drupal\test_register\Entity\Register');

    // Load the Register again.
    $id = $register->id();
    $register = $register_storage->load($id);

    // Retrieve the values and test.
    $this->assertEqual($register->get('first_name')->value, 'Juan');
    $this->assertEqual($register->get('last_name')->value, 'Lopez Zuluaga');
    $this->assertEqual($register->get('gender')->value, 'male');
    $this->assertEqual($register->get('phone_number')->value, '3219385421');
  }

}
