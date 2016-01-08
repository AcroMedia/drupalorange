<?php

/**
 * @file
 * Contains \Drupal\userprotect\Plugin\UserProtection\Roles.
 */

namespace Drupal\userprotect\Plugin\UserProtection;

use Drupal\Core\Form\FormStateInterface;

/**
 * Protects user's roles.
 *
 * @UserProtection(
 *   id = "user_roles",
 *   label = @Translation("Roles"),
 *   weight = -6
 * )
 */
class Roles extends UserProtectionBase {
  /**
   * {@inheritdoc}
   */
  public function applyAccountFormProtection(array &$form, FormStateInterface $form_state) {
    if (isset($form['account']['roles'])) {
      $form['account']['roles']['#disabled'] = TRUE;
      return TRUE;
    }
    return FALSE;
  }
}
