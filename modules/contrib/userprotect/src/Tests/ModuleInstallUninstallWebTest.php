<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\ModuleInstallUninstallWebTest.
 */

namespace Drupal\userprotect\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests module installation and uninstallation.
 *
 * @group userprotect
 */
class ModuleInstallUninstallWebTest extends WebTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = array('userprotect');

  /**
   * Test installation and uninstallation.
   */
  protected function testInstallationAndUninstallation() {
    /** @var \Drupal\Core\Extension\ModuleInstallerInterface $module_installer */
    $module_installer = \Drupal::service('module_installer');
    $module_handler = \Drupal::moduleHandler();
    $this->assertTrue($module_handler->moduleExists('userprotect'));

    // Test default configuration.
    $account = $this->drupalCreateUser();
    $this->assertTrue($account->hasPermission('userprotect.mail.edit'), 'Authenticated user can edit own mail address.');
    $this->assertTrue($account->hasPermission('userprotect.pass.edit'), 'Authenticated user can edit own password.');
    $this->assertTrue($account->hasPermission('userprotect.account.edit'), 'Authenticated user can edit own account.');

    // Ensure an authenticated user can edit its own account.
    $this->drupalLogin($account);
    $this->drupalGet('user/' . $account->id() . '/edit');
    $this->assertResponse(200, 'Authenticated user has access to edit page of own account.');

    $module_installer->uninstall(array('userprotect'));
    $this->assertFalse($module_handler->moduleExists('userprotect'));
  }
}
