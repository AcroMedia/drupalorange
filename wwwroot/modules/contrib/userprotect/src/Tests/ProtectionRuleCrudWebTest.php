<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\ProtectionRuleCrudWebTest.
 */

namespace Drupal\userprotect\Tests;

use Drupal\userprotect\Entity\ProtectionRuleInterface;

/**
 * Tests creating, editing and deleting protection rules through the UI.
 *
 * @group userprotect
 */
class ProtectionRuleCrudWebTest extends UserProtectWebTestBase {

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

    $this->account = $this->drupalCreateUser(array('userprotect.administer'));
    $this->drupalLogin($this->account);
  }

  /**
   * Tests if role based protection rules can be created through the UI.
   */
  protected function testCrudRoleProtectionRule() {
    $rid = $this->drupalCreateRole(array());
    $rule_id = strtolower($this->randomMachineName());
    $label = $this->randomMachineName();

    // Create rule.
    $edit = array(
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $rid,
      'protection[user_mail]' => TRUE,
    );
    $this->drupalPostForm('admin/config/people/userprotect/add', $edit, t('Save'));

    // Assert that the rule was created.
    $protection_rule = entity_load('userprotect_rule', $rule_id);
    $this->assertTrue(($protection_rule instanceof ProtectionRuleInterface), 'A protection rule was created through the UI.');

    // Stop the test if rule is not an instance of ProtectionRuleInterface.
    if (!($protection_rule instanceof ProtectionRuleInterface)) {
      return;
    }

    // Assert that the rule has the expected values.
    $this->assertEqual($rule_id, $protection_rule->id());
    $this->assertEqual($label, $protection_rule->label);
    $this->assertEqual('user_role', $protection_rule->getProtectedEntityTypeId());
    $this->assertEqual($rid, $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertEqual(1, count($enabled_plugins), 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEqual('user_mail', $plugin->getPluginId());

    // Edit rule.
    $edit = array(
      'protection[user_name]' => TRUE,
      'protection[user_mail]' => FALSE,
    );
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id, $edit, t('Save'));

    // Assert that the rule was updated with the expected values.
    $protection_rule = entity_load('userprotect_rule', $rule_id, TRUE);
    $this->assertEqual($rule_id, $protection_rule->id());
    $this->assertEqual($label, $protection_rule->label);
    $this->assertEqual('user_role', $protection_rule->getProtectedEntityTypeId());
    $this->assertEqual($rid, $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertEqual(1, count($enabled_plugins), 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEqual('user_name', $plugin->getPluginId());

    // Attempt to create a rule with the same name.
    $edit = array(
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $rid,
      'protection[user_mail]' => TRUE,
    );
    $this->drupalPostForm('admin/config/people/userprotect/add', $edit, t('Save'));
    $this->assertText('The machine-readable name is already in use. It must be unique.');

    // Assert only one protection rule exists.
    $entities = entity_load_multiple('userprotect_rule', NULL, TRUE);
    $this->assertEqual(1, count($entities), 'Only one protection rule exists.');

    // Delete rule.
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id . '/delete', array(), t('Delete'));
    // Assert the rule no longer exists.
    $protection_rule = entity_load('userprotect_rule', $rule_id, TRUE);
    $this->assertFalse($protection_rule, 'The protection rule was deleted.');
  }

  /**
   * Tests if user based protection rules can be created through the UI.
   */
  protected function testCrudUserProtectionRule() {
    $account = $this->drupalCreateUser();
    $rule_id = strtolower($this->randomMachineName());
    $label = $this->randomMachineName();

    // Create rule.
    $edit = array(
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $account->getUsername(),
      'protection[user_mail]' => TRUE,
    );
    $this->drupalPostForm('admin/config/people/userprotect/add/user', $edit, t('Save'));

    // Assert that the rule was created.
    $protection_rule = entity_load('userprotect_rule', $rule_id);
    $this->assertTrue(($protection_rule instanceof ProtectionRuleInterface), 'A protection rule was created through the UI.');

    // Stop the test if rule is not an instance of ProtectionRuleInterface.
    if (!($protection_rule instanceof ProtectionRuleInterface)) {
      return;
    }

    // Assert that the rule has the expected values.
    $this->assertEqual($rule_id, $protection_rule->id());
    $this->assertEqual($label, $protection_rule->label);
    $this->assertEqual('user', $protection_rule->getProtectedEntityTypeId());
    $this->assertEqual($account->id(), $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertEqual(1, count($enabled_plugins), 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEqual('user_mail', $plugin->getPluginId());

    // Edit rule.
    $edit = array(
      'protection[user_name]' => TRUE,
      'protection[user_mail]' => FALSE,
    );
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id, $edit, t('Save'));

    // Assert that the rule was updated with the expected values.
    $protection_rule = entity_load('userprotect_rule', $rule_id, TRUE);
    $this->assertEqual($rule_id, $protection_rule->id());
    $this->assertEqual($label, $protection_rule->label);
    $this->assertEqual('user', $protection_rule->getProtectedEntityTypeId());
    $this->assertEqual($account->id(), $protection_rule->getProtectedEntityId());
    $enabled_plugins = $protection_rule->getProtections()->getEnabledPlugins();
    $this->assertEqual(1, count($enabled_plugins), 'One plugin was enabled.');
    $plugin = reset($enabled_plugins);
    $this->assertEqual('user_name', $plugin->getPluginId());

    // Attempt to create a rule with the same name.
    $edit = array(
      'label' => $label,
      'name' => $rule_id,
      'entity_id' => $account->getUsername(),
      'protection[user_mail]' => TRUE,
    );
    $this->drupalPostForm('admin/config/people/userprotect/add/user', $edit, t('Save'));
    $this->assertText('The machine-readable name is already in use. It must be unique.');

    // Assert only one protection rule exists.
    $entities = entity_load_multiple('userprotect_rule', NULL, TRUE);
    $this->assertEqual(1, count($entities), 'Only one protection rule exists.');

    // Delete rule.
    $this->drupalPostForm('admin/config/people/userprotect/manage/' . $rule_id . '/delete', array(), t('Delete'));
    // Assert the rule no longer exists.
    $protection_rule = entity_load('userprotect_rule', $rule_id, TRUE);
    $this->assertFalse($protection_rule, 'The protection rule was deleted.');
  }
}
