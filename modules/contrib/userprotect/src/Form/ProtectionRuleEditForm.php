<?php

/**
 * @file
 * Contains \Drupal\userprotect\ProtectionRuleEditForm.
 */

namespace Drupal\userprotect\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form controller for editing a protection rule.
 */
class ProtectionRuleEditForm extends ProtectionRuleFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form['#title'] = $this->t('Edit protection rule %name', array('%name' => $this->entity->label()));
    $form = parent::form($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    drupal_set_message(t('Updated protection rule %name.', array('%name' => $this->entity->label())));
    return $this->entity;
  }

}
