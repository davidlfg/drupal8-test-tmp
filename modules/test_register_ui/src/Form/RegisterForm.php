<?php

namespace Drupal\test_register_ui\Form;

use Drupal\Core\Entity\ContentEntityForm as OriginalContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;

/**
 * Extends original content form and adds convenience redirection.
 */
class RegisterForm extends OriginalContentEntityForm {

  use MessengerTrait;

  /**
   * {@inheritdoc}
   */
  protected $step = 1;

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    // Get entity.
    $register = $this->entity;

    if ($this->step == 1) {
      $form['city']['#access'] = FALSE;
      $form['phone_number']['#access'] = FALSE;
      $form['address']['#access'] = FALSE;
    }
    if ($this->step == 2) {
      $form['first_name']['#access'] = FALSE;
      $form['last_name']['#access'] = FALSE;
      $form['gender']['#access'] = FALSE;
      $form['birthdate']['#access'] = FALSE;

      // Ajax for city management.
      $form['city']['#attributes']['id'][] = 'city-wrapper';

      $default_department_id = NULL;
      $department_id = $form_state->getValue('department');

      $city_id = $register->get('city')->target_id;

      // Edit form.
      if ($city_id && !$form_state->getValue('department')) {
        $term = $this->entityTypeManager->getStorage('taxonomy_term')->load($city_id);
        $parent = $term->get('parent')->target_id;
        $default_department_id = $parent;
        $options_cities = $this->getCityOptions($parent, 2);
        $form['city']['widget']['#options'] = $options_cities;
        $form['city']['widget']['#default_value'] = $city_id;
      }
      elseif ($form_state->getValue('department')) {
        // Update cities.
        $options_cities = $this->getCityOptions($department_id, 2);
        $form['city']['widget']['#options'] = $options_cities;
        $default_department_id = $department_id;
      }
      else {
        $form['city']['widget']['#options'] = ['_none' => '- None -'];
      }
      $options_departments = $this->getCityOptions(0, 1);
      $form['department'] = [
        '#type' => 'select',
        '#title' => $this->t('Departments'),
        '#options' => $options_departments,
        '#default_value' => $default_department_id,
        '#ajax' => [
          'callback' => '::updateCity',
          'wrapper' => 'city-wrapper',
        ],
      ];
    }
    // Resume.
    if ($this->step == 3) {
      $form['city']['#access'] = FALSE;
      $form['phone_number']['#access'] = FALSE;
      $form['address']['#access'] = FALSE;
      $form['first_name']['#access'] = FALSE;
      $form['last_name']['#access'] = FALSE;
      $form['gender']['#access'] = FALSE;
      $form['birthdate']['#access'] = FALSE;

      $form['data'] = array(
        '#type' => 'table',
        '#header' => array(t('First Name'), t('Last Name'), t('Telephone')),
      );
      $form['data'][0]['first_name'] = array(
        '#markup' => $register->get('first_name')->value,
      );
      $form['data'][0]['last_name'] = array(
        '#markup' => $register->get('last_name')->value,
      );
      $form['data'][0]['gender'] = array(
        '#markup' => $register->get('phone_number')->value,
      );
    }
    return $form;
  }

  /**
   * Ajax callback for the city.
   */
  public function updateCity(array $form, FormStateInterface $form_state) {
    return $form['city'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    if ($this->step < 3) {
      $form['actions']['submit']['#value'] = $this->t('Next');
    }

    return $form;
  }

  /**
   * Get City options by department.
   */
  public function getCityOptions($parent, $level) {
    $tree = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree('city', $parent, $level, TRUE);
    $options = ['_none' => $this->t('Select')];
    foreach ($tree as $term) {
      $options[$term->id()] = $term->getName();
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    if ($this->step < 3) {
      $form_state->setRebuild();
      $this->step++;
    }
    else {
      $messenger = $this->messenger();
      $entity_type = $this->entity;
      $status = $entity_type->save();
      $message_params = [
        '%label' => $entity_type->label(),
        '%content_entity_id' => $entity_type->getEntityType()->getBundleOf(),
      ];

      // Provide message for the user and redirect them back to the collection.
      switch ($status) {
        case SAVED_NEW:
          $messenger->addMessage($this->t('Created the %label %content_entity_id entity type.', $message_params));
          break;

        default:
          $messenger->addMessage($this->t('Saved the %label %content_entity_id entity type.', $message_params));
      }

      $form_state->setRedirectUrl($entity_type->toUrl('collection'));
    }
  }

}
