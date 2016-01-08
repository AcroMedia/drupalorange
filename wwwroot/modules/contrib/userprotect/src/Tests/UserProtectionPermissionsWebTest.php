<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\UserProtectionPermissionsWebTest.
 */

namespace Drupal\userprotect\Tests;

use Drupal\userprotect\Entity\ProtectionRuleInterface;

/**
 * Tests if permissions "Change own e-mail", "Change own password" and "Change own account" are respected.
 *
 * @group userprotect
 */
class UserProtectionPermissionsWebTest extends UserProtectWebTestBase {

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

    // Revoke default permissions on the authenticated user role that are
    // installed by the userprotect module.
    // @see userprotect_install().
    $role = entity_load('user_role', DRUPAL_AUTHENTICATED_RID);
    $role->revokePermission('userprotect.mail.edit');
    $role->revokePermission('userprotect.pass.edit');
    $role->revokePermission('userprotect.account.edit');
    $role->save();
  }

  /**
   * Tests edit mail with permission "userprotect.mail.edit".
   *
   * Tests if an user with the permission "userprotect.mail.edit" can edit its
   * own mail.
   */
  protected function testEditOwnMail() {
    // Create account that may edit its own mail address.
    $account = $this->drupalCreateUser(array('userprotect.mail.edit', 'userprotect.account.edit'));
    $this->drupalLogin($account);

    $edit = array(
      'mail' => $this->randomMachineName() . '@example.com',
    );
    $this->drupalPostForm('user/' . $account->id() . '/edit', $edit, t('Save'));

    // Assert the mail address changed.
    $account = entity_load('user', $account->id(), TRUE);
    $this->assertEqual($edit['mail'], $account->getEmail(), "The user has changed its own mail address.");
  }

  /**
   * Tests edit mail without permission "userprotect.mail.edit".
   *
   * Tests if an user without the permission "userprotect.mail.edit" can not
   * edit its own mail address.
   */
  protected function testNoEditOwnMail() {
    // Create account that may NOT edit its own mail address.
    $account = $this->drupalCreateUser(array('userprotect.account.edit'));
    $expected_mail = $account->getEmail();
    $this->drupalLogin($account);

    $edit = array(
      'mail' => $this->randomMachineName() . '@example.com',
    );
    $this->userprotectPostForm('user/' . $account->id() . '/edit', $edit, t('Save'));

    // Assert the mail address changed.
    $account = entity_load('user', $account->id(), TRUE);
    $this->assertEqual($expected_mail, $account->getEmail(), "The user's mail address was NOT changed.");
  }

  /**
   * Tests edit password with permission "userprotect.pass.edit".
   *
   * Tests if an user with the permission "userprotect.pass.edit" can edit its
   * own password.
   */
  protected function testEditOwnPass() {
    // Create account that may edit its own password.
    $account = $this->drupalCreateUser(array('userprotect.pass.edit', 'userprotect.account.edit'));
    $this->drupalLogin($account);

    $new_pass = $this->randomMachineName();
    $edit = array(
      'current_pass' => $account->pass_raw,
      'pass[pass1]' => $new_pass,
      'pass[pass2]' => $new_pass,
    );
    $this->drupalPostForm('user/' . $account->id() . '/edit', $edit, t('Save'));

    // Assert the password changed.
    $account = entity_load('user', $account->id(), TRUE);
    $account->pass_raw = $new_pass;
    $this->drupalLogout();
    $this->drupalLogin($account);
  }

  /**
   * Tests edit password without permission "userprotect.pass.edit".
   *
   * Tests if an user without the permission "userprotect.pass.edit" can not
   * edit its own password.
   */
  protected function testNoEditOwnPass() {
    // Create account that may NOT edit its own password.
    $account = $this->drupalCreateUser(array('userprotect.account.edit'));
    $expected_pass = $account->pass_raw;
    $this->drupalLogin($account);

    $new_pass = $this->randomMachineName();
    $edit = array(
      'current_pass' => $account->pass_raw,
      'pass[pass1]' => $new_pass,
      'pass[pass2]' => $new_pass,
    );
    $this->userprotectPostForm('user/' . $account->id() . '/edit', $edit, t('Save'));

    // Assert the password did not change.
    $account = entity_load('user', $account->id(), TRUE);
    $account->pass_raw = $expected_pass;
    $this->drupalLogout();
    $this->drupalLogin($account);
  }

  /**
   * Tests edit account with permission "userprotect.account.edit".
   *
   * Tests if an user with the permission "userprotect.account.edit" can edit
   * its own account.
   */
  protected function testEditOwnAccount() {
    // Create an account that may edit its own account.
    $account = $this->drupalCreateUser(array('userprotect.account.edit'));
    $this->drupalLogin($account);

    // Assert the user can edit its own account.
    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertResponse(200, "The user may edit its own account.");
  }

  /**
   * Tests edit account without permission "userprotect.account.edit".
   *
   * Tests if an user without the permission "userprotect.account.edit" can
   * not edit its own account.
   */
  protected function testNoEditOwnAccount() {
    // Create an account that may NOT edit its own account.
    $account = $this->drupalCreateUser();
    $this->drupalLogin($account);

    // Assert the user can edit its own account.
    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertResponse(403, "The user may NOT edit its own account.");
  }
}
