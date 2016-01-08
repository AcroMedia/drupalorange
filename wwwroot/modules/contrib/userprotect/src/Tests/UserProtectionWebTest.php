<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\UserProtectionWebTest.
 */

namespace Drupal\userprotect\Tests;

/**
 * Tests each UserProtection plugin in action.
 *
 * @group userprotect
 * @todo Assert protection messages.
 */
class UserProtectionWebTest extends UserProtectWebTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = array('userprotect');

  /**
   * The operating account.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->account = $this->drupalCreateUser(array('administer users', 'administer permissions'));
    $this->drupalLogin($this->account);
  }

  /**
   * Tests if the user's name field has the expected protection.
   */
  protected function testNameProtection() {
    $protected_account = $this->createProtectedUser(array('user_name'));

    // Remember the user's name.
    $expected_name = $protected_account->getUsername();

    $edit = array(
      'name' => $this->randomMachineName(),
    );
    $this->userprotectPostForm('user/' . $protected_account->id() . '/edit', $edit, t('Save'));

    // Re-load the user and check the user name didn't change.
    $protected_account = entity_load('user', $protected_account->id(), TRUE);
    $this->assertEqual($expected_name, $protected_account->getUsername());
  }

  /**
   * Tests if the user's mail address field has the expected protection.
   */
  protected function testMailProtection() {
    $protected_account = $this->createProtectedUser(array('user_mail'));

    // Remember the user's mail address.
    $expected_mail = $protected_account->getEmail();

    $edit = array(
      'mail' => $this->randomMachineName() . '@example.com',
    );
    $this->userprotectPostForm('user/' . $protected_account->id() . '/edit', $edit, t('Save'));

    // Re-load the user and check the user mail address didn't change.
    $protected_account = entity_load('user', $protected_account->id(), TRUE);
    $this->assertEqual($expected_mail, $protected_account->getEmail());
  }

  /**
   * Tests if the user's password field has the expected protection.
   */
  protected function testPassProtection() {
    $protected_account = $this->createProtectedUser(array('user_pass'));

    // Remember the user's pass.
    $expected_pass = $protected_account->pass_raw;

    $new_pass = $this->randomMachineName();
    $edit = array(
      'pass[pass1]' => $new_pass,
      'pass[pass2]' => $new_pass,
    );
    $this->userprotectPostForm('user/' . $protected_account->id() . '/edit', $edit, t('Save'));

    // Try to login as this user with the expected password.
    $protected_account = entity_load('user', $protected_account->id(), TRUE);
    $protected_account->pass_raw = $expected_pass;
    $this->drupalLogout();
    $this->drupalLogin($protected_account);
  }

  /**
   * Tests if the user's status field has the expected protection.
   */
  protected function testStatusProtection() {
    $protected_account = $this->createProtectedUser(array('user_status'));

    // Try to deactivate user's status.
    $edit = array(
      'status' => '0',
    );
    $this->userprotectPostForm('user/' . $protected_account->id() . '/edit', $edit, t('Save'));

    // Re-load the user and check the user is still active.
    $protected_account = entity_load('user', $protected_account->id(), TRUE);
    $this->assertTrue($protected_account->isActive());
  }

  /**
   * Tests if the user's roles field has the expected protection.
   */
  protected function testRolesProtection() {
    $protected_account = $this->createProtectedUser(array('user_roles'));

    // Add a role to the protected account.
    $rid1 = $this->drupalCreateRole(array());
    $protected_account->addRole($rid1);
    $protected_account->save();

    // Add another role. We try to add this role to the user form later.
    $rid2 = $this->drupalCreateRole(array());

    // Re-load the user and check its roles.
    $protected_account = entity_load('user', $protected_account->id(), TRUE);
    // Assert the protected account's roles.
    $this->assertTrue($protected_account->hasRole($rid1));
    $this->assertFalse($protected_account->hasRole($rid2));

    // Try to add the second role to this user.
    $edit = array(
      'roles[' . $rid2 . ']' => $rid2,
    );
    $this->userprotectPostForm('user/' . $protected_account->id() . '/edit', $edit, t('Save'));

    // Re-load the user and assert the roles it has are still the same.
    $protected_account = entity_load('user', $protected_account->id(), TRUE);
    $this->assertTrue($protected_account->hasRole($rid1));
    $this->assertFalse($protected_account->hasRole($rid2));
  }
}
