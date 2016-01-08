<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\FieldAccessWebTest.
 */

namespace Drupal\userprotect\Tests;

/**
 * Tests field access for each UserProtection plugin that protects a field.
 *
 * @group userprotect
 */
class FieldAccessWebTest extends UserProtectWebTestBase {
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
   * Tests field access for the user's name.
   */
  protected function testNameAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's name.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->name->access('edit', $this->account));

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's name.
    $protected_account = $this->createProtectedUser(array('user_name'));
    $this->assertFalse($protected_account->name->access('edit', $this->account));
  }

  /**
   * Tests field access for the user's mail address.
   */
  protected function testMailAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's mail address.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->mail->access('edit', $this->account));

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's mail address.
    $protected_account = $this->createProtectedUser(array('user_mail'));
    $this->assertFalse($protected_account->mail->access('edit', $this->account));
  }

  /**
   * Tests field access for the user's password.
   */
  protected function testPassAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's password.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->pass->access('edit', $this->account));

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's password.
    $protected_account = $this->createProtectedUser(array('user_pass'));
    $this->assertFalse($protected_account->pass->access('edit', $this->account));
  }

  /**
   * Tests field access for the user's status.
   */
  protected function testStatusAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's status.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->status->access('edit', $this->account));

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's status.
    $protected_account = $this->createProtectedUser(array('user_status'));
    $this->assertFalse($protected_account->status->access('edit', $this->account));
  }

  /**
   * Tests field access for the user's roles.
   */
  protected function testRolesAccess() {
    // Create an account with no protection. The logged in user should have the
    // privileges to edit this account's roles.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->roles->access('edit', $this->account));

    // Create a protected account. The logged in user should NOT have the
    // privileges to edit this account's roles.
    $protected_account = $this->createProtectedUser(array('user_roles'));
    $this->assertFalse($protected_account->roles->access('edit', $this->account));
  }
}
