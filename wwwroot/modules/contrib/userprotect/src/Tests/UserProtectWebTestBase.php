<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\Controller\UserProtectWebTestBase.
 */

namespace Drupal\userprotect\Tests;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\userprotect\Entity\ProtectionRuleInterface;
use Drupal\simpletest\WebTestBase;

/**
 * Base class for User protect web tests.
 */
abstract class UserProtectWebTestBase extends WebTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = array('userprotect');

  /**
   * Creates a protected role.
   *
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   *
   * @return string
   *   The ID of the created role.
   */
  protected function createProtectedRole(array $protections = array()) {
    // Create a role.
    $rid = $this->drupalCreateRole(array());

    // Protect this role.
    $protection_rule = $this->createProtectionRule($rid, $protections);
    $protection_rule->save();
    // Reset available permissions.
    drupal_static_reset('checkPermissions');

    return $rid;
  }

  /**
   * Creates a protected user.
   *
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   *
   * @return object
   *   The created user.
   */
  protected function createProtectedUser(array $protections = array()) {
    // Create a user.
    $account = $this->drupalCreateUser();

    // Protect this user.
    $protection_rule = $this->createProtectionRule($account->id(), $protections, 'user');
    $protection_rule->save();
    // Reset available permissions.
    drupal_static_reset('checkPermissions');

    return $account;
  }

  /**
   * Creates an user with a protected role.
   *
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   *
   * @return object
   *   The created user.
   */
  protected function createUserWithProtectedRole(array $protections = array()) {
    // Create a protected role.
    $rid = $this->createProtectedRole($protections);

    // Create an account with this protected role.
    $protected_account = $this->drupalCreateUser();
    $protected_account->addRole($rid);
    $protected_account->save();

    return $protected_account;
  }

  /**
   * Creates protection rule.
   *
   * @param int|string $entity_id
   *   The id of the entity to protect.
   * @param array $protections
   *   (optional) The active protections.
   *   Defaults to an empty array.
   * @param string $entity_type
   *   (optional) The protected entity type.
   *   Defaults to "user_role".
   * @param array $values
   *   (optional) Extra values of the protection rule.
   *
   * @return \Drupal\userprotect\Entity\ProtectionRuleInterface
   *   An instance of ProtectionRuleInterface.
   */
  protected function createProtectionRule($entity_id, array $protections = array(), $entity_type = 'user_role', array $values = array()) {
    // Setup default values.
    $values += array(
      'name' => 'dummy',
      'label' => 'Dummy',
      'protections' => array(),
      'protectedEntityTypeId' => $entity_type,
      'protectedEntityId' => $entity_id,
    );
    // Define protections.
    foreach ($protections as $key) {
      $values['protections'][$key] = array(
        'status' => TRUE,
      );
    }

    // Create protection rule.
    $protection_rule = entity_create('userprotect_rule', $values);
    $this->assertTrue($protection_rule instanceof ProtectionRuleInterface, SafeMarkup::format('Created protection rule %rule.', array('%rule' => $protection_rule->id())));
    return $protection_rule;
  }

  /**
   * Executes a form submission, but does not require all fields to be present.
   *
   * @param NULL|string $path
   *   Location of the post form.
   * @param array $edit
   *   Field data in an associative array.
   * @param string $submit
   *   Value of the submit button whose click is to be emulated.
   * @param array $options
   *   (optional) Options to be forwarded to the url generator.
   * @param array $headers
   *   (optional) An array containing additional HTTP request headers.
   * @param string $form_html_id
   *   (optional) HTML ID of the form to be submitted.
   *
   * @return NULL|string
   *   Result of CURL in case form could be posted.
   *   NULL otherwise.
   */
  protected function userprotectPostForm($path, $edit, $submit, array $options = array(), array $headers = array(), $form_html_id = NULL) {
    if (isset($path)) {
      $this->drupalGet($path, $options);
    }
    if ($this->parse()) {
      $edit_save = $edit;
      // Let's iterate over all the forms.
      $xpath = "//form";
      if (!empty($form_html_id)) {
        $xpath .= "[@id='" . $form_html_id . "']";
      }
      $forms = $this->xpath($xpath);
      foreach ($forms as $form) {
        // We try to set the fields of this form as specified in $edit.
        $edit = $edit_save;
        $post = array();
        $upload = array();
        $submit_matches = $this->handleForm($post, $edit, $upload, $submit, $form);
        $action = isset($form['action']) ? $this->getAbsoluteUrl((string) $form['action']) : $this->getUrl();

        if ($submit_matches) {
          $post = array_merge($post, $edit);
          $out = $this->curlExec(array(
            CURLOPT_URL => $action,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => $headers,
          ));

          $verbose = 'POST request to: ' . $path;
          $verbose .= '<hr />Ending URL: ' . $this->getUrl();
          if ($this->dumpHeaders) {
            $verbose .= '<hr />Headers: <pre>' . SafeMarkup::checkPlain(var_export(array_map('trim', $this->headers), TRUE)) . '</pre>';
          }
          $verbose .= '<hr />Fields: ' . highlight_string('<?php ' . var_export($post, TRUE), TRUE);
          $verbose .= '<hr />' . $out;

          $this->verbose($verbose);
          return $out;
        }
      }
      $this->fail(SafeMarkup::format('Found the requested form fields at @path', array('@path' => $path)));
    }
  }
}
