<?php

/**
 * @file
 * Contains \Drupal\userprotect\Form\ProtectionRuleAddForm.
 */

namespace Drupal\userprotect\Form;

use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides a form controller for adding a protection rule.
 */
class ProtectionRuleAddForm extends ProtectionRuleFormBase {
  /**
   * Overrides EntityFormController::buildForm().
   *
   * Sets protected entity type on protection rule entity.
   *
   * @param string $protected_entity_type_id
   *   (optional) The entity type to protect.
   *   Defaults to 'user_role'.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $protected_entity_type_id = 'user_role') {
    // Only allow entity types 'user' and 'user_role'.
    switch ($protected_entity_type_id) {
      case 'user':
      case 'user_role':
        break;

      default:
        throw new NotFoundHttpException();
    }

    $this->entity->setProtectedEntityTypeId($protected_entity_type_id);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    drupal_set_message($this->t('Added protection rule %name.', array('%name' => $this->entity->label())));
  }

}
