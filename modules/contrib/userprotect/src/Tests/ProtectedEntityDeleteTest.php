<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\ProtectedEntityDeleteTest.
 */

namespace Drupal\userprotect\Tests;

/**
 * Tests if protection rules are cleaned up upon entity deletion.
 *
 * @group userprotect
 */
class ProtectedEntityDeleteTest extends UserProtectWebTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = array('userprotect');

  /**
   * Tests reaction upon user deletion.
   *
   * Tests if an user based protection rule is cleaned up when the protected
   * user is deleted.
   */
  protected function testUserDelete() {
    // Create a user.
    $account = $this->drupalCreateUser();

    // Protect this user.
    $protection_rule = $this->createProtectionRule($account->id(), array(), 'user');
    $protection_rule->save();

    // Assert that the rule was saved.
    $protection_rule = entity_load('userprotect_rule', $protection_rule->id(), TRUE);
    $this->assertNotNull($protection_rule, 'The protection rule was saved.');

    // Now delete the account.
    $account->delete();

    // Assert that the rule no longer exists.
    $protection_rule = entity_load('userprotect_rule', $protection_rule->id(), TRUE);
    $this->assertNull($protection_rule, 'The protection rule was deleted.');
  }

  /**
   * Tests reaction upon role deletion.
   *
   * Tests if a role based protection rule is cleaned up when the protected
   * role is deleted.
   */
  protected function testRoleDelete() {
    // Create a role.
    $rid = $this->drupalCreateRole(array());

    // Protect this role.
    $protection_rule = $this->createProtectionRule($rid, array(), 'user_role');
    $protection_rule->save();

    // Assert that the rule was saved.
    $protection_rule = entity_load('userprotect_rule', $protection_rule->id(), TRUE);
    $this->assertNotNull($protection_rule, 'The protection rule was saved.');

    // Now delete the role.
    $role = entity_load('user_role', $rid);
    $role->delete();

    // Assert that the rule no longer exists.
    $protection_rule = entity_load('userprotect_rule', $protection_rule->id(), TRUE);
    $this->assertNull($protection_rule, 'The protection rule was deleted.');
  }

  /**
   * Tests UI for non-existent protected users.
   *
   * Tests if there are no PHP errors in the UI when a protection rule for a
   * non-existent user still exists.
   */
  protected function testNonExistentProtectedUser() {
    // Create a protection rule for a non-existent user.
    $fake_uid = 10;
    $protection_rule = $this->createProtectionRule($fake_uid, array(), 'user');
    $protection_rule->save();

    // Check user interface.
    $account = $this->drupalCreateUser(array('userprotect.administer'));
    $this->drupalLogin($account);
    $this->drupalGet('admin/config/people/userprotect');
    $this->assertText(t('Missing'));
  }

  /**
   * Tests UI for non-existent protected roles.
   *
   * Tests if there are no PHP errors in the UI when a protection rule for a
   * non-existent role still exists.
   */
  protected function testNonExistentProtectedRole() {
    // Create a protection rule for a non-existent user.
    $protection_rule = $this->createProtectionRule('non-existent role', array(), 'user_role');
    $protection_rule->save();

    // Check user interface.
    $account = $this->drupalCreateUser(array('userprotect.administer'));
    $this->drupalLogin($account);
    $this->drupalGet('admin/config/people/userprotect');
    $this->assertText(t('Missing'));
  }
}
