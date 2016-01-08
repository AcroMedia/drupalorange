<?php

/**
 * @file
 * Contains \Drupal\userprotect\Tests\Entity\ProtectionRuleUnitTest.
 */

namespace Drupal\userprotect\Tests\Entity;

use Drupal\Core\Session\UserSession;
use Drupal\userprotect\Entity\ProtectionRuleInterface;
use Drupal\userprotect\Plugin\UserProtection\UserProtectionInterface;
use Drupal\userprotect\UserProtect;
use Drupal\userprotect\Plugin\UserProtection\UserProtectionPluginCollection;
use Drupal\simpletest\KernelTestBase;

/**
 * \Drupal\userprotect\Entity\ProtectionRule unit test.
 *
 * @group userprotect
 */
class ProtectionRuleUnitTest extends KernelTestBase {

  /**
   * The user protection plugin manager.
   *
   * @var \Drupal\userprotect\Plugin\UserProtection\UserProtectionManager
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  public static $modules = array('user', 'userprotect', 'field');

  /**
   * The protection rule to test on.
   *
   * @var \Drupal\userprotect\Entity\ProtectionRuleInterface
   */
  protected $protectionRule;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->manager = UserProtect::pluginManager();
    $this->protectionRule = entity_create('userprotect_rule', array(
      'name' => 'dummy',
      'label' => 'Dummy',
      'protections' => array(
        'user_mail' => array(
          'status' => TRUE,
        ),
      ),
      'protectedEntityTypeId' => 'user_role',
      'protectedEntityId' => 'administrator',
    ));
  }

  /**
   * Tests id().
   */
  protected function testId() {
    $this->assertIdentical('dummy', $this->protectionRule->id());
  }

  /**
   * Tests setProtectedEntityTypeId() and getProtectedEntityTypeId().
   */
  protected function testProtectedEntityTypeId() {
    $this->assertIdentical('user_role', $this->protectionRule->getProtectedEntityTypeId());
    $entity_type = 'user';
    $this->assertTrue($this->protectionRule->setProtectedEntityTypeId($entity_type) instanceof ProtectionRuleInterface);
    $this->assertIdentical($entity_type, $this->protectionRule->getProtectedEntityTypeId());
  }

  /**
   * Tests setProtectedEntityId() and getProtectedEntityId().
   */
  protected function testProtectedEntityId() {
    $this->assertIdentical('administrator', $this->protectionRule->getProtectedEntityId());
    $entity_id = 'authenticated';
    $this->assertTrue($this->protectionRule->setProtectedEntityId($entity_id) instanceof ProtectionRuleInterface);
    $this->assertIdentical($entity_id, $this->protectionRule->getProtectedEntityId());
  }

  /**
   * Tests setBypassRoles() and getBypassRoles().
   */
  protected function testBypassRoles() {
    $this->assertIdentical(array(), $this->protectionRule->getBypassRoles());
    $roles = array('administrator');
    $this->assertTrue($this->protectionRule->setBypassRoles($roles) instanceof ProtectionRuleInterface);
    $this->assertIdentical($roles, $this->protectionRule->getBypassRoles());
  }

  /**
   * Tests getProtection().
   */
  protected function testGetProtection() {
    $this->assertTrue($this->protectionRule->getProtection('user_mail') instanceof UserProtectionInterface);
  }

  /**
   * Tests getProtections().
   */
  protected function testGetProtections() {
    $this->assertTrue($this->protectionRule->getProtections() instanceof UserProtectionPluginCollection);
  }

  /**
   * Tests enableProtection().
   */
  protected function testEnableProtection() {
    $this->assertTrue($this->protectionRule->enableProtection('user_name') instanceof ProtectionRuleInterface);
    $this->assertTrue($this->protectionRule->hasProtection('user_name'));
  }

  /**
   * Tests disableProtection().
   */
  protected function testDisableProtection() {
    $this->assertTrue($this->protectionRule->disableProtection('user_mail') instanceof ProtectionRuleInterface);
    $this->assertFalse($this->protectionRule->hasProtection('user_mail'));
  }

  /**
   * Tests toArray().
   */
  protected function testToArray() {
    $array = $this->protectionRule->toArray();
    $this->assertIdentical('dummy', $array['name']);
    $this->assertIdentical('Dummy', $array['label']);
    $expected_protections = array(
      'user_mail' => array(
        'status' => TRUE,
      ),
    );
    $this->assertIdentical($expected_protections, $array['protections']);
    $this->assertIdentical('user_role', $array['protectedEntityTypeId']);
    $this->assertIdentical('administrator', $array['protectedEntityId']);
  }

  /**
   * Tests getPermissionName().
   */
  protected function testGetPermissionName() {
    $this->assertIdentical('userprotect.dummy.bypass', $this->protectionRule->getPermissionName());
  }

  /**
   * Tests appliesTo().
   */
  protected function testAppliesTo() {
    // Create an user with administrator role.
    $values = array(
      'uid' => 3,
      'name' => 'lorem',
      'roles' => array(
        'administrator',
      ),
    );
    $lorem = entity_create('user', $values);

    // Create an authenticated user.
    $values = array(
      'uid' => 4,
      'name' => 'ipsum',
    );
    $ipsum = entity_create('user', $values);

    // Assert that the protection rule applies to the user with the
    // administrator role and not to the authenticated user.
    $this->assertTrue($this->protectionRule->appliesTo($lorem));
    $this->assertFalse($this->protectionRule->appliesTo($ipsum));

    // Create an user based protection rule.
    $user_protection_rule = entity_create('userprotect_rule', array(
      'name' => 'dummy',
      'label' => 'Dummy',
      'protections' => array(
        'user_mail' => array(
          'status' => TRUE,
        ),
      ),
      'protectedEntityTypeId' => 'user',
      'protectedEntityId' => 4,
    ));

    // Assert that the protection rule applies to "ipsum", but no to "lorem".
    $this->assertFalse($user_protection_rule->appliesTo($lorem));
    $this->assertTrue($user_protection_rule->appliesTo($ipsum));
  }

  /**
   * Tests hasProtection().
   */
  protected function testHasProtection() {
    // The protection rule was created with only the protection "user_mail"
    // enabled.
    $this->assertTrue($this->protectionRule->hasProtection('user_mail'));
    $this->assertFalse($this->protectionRule->hasProtection('user_name'));
    $this->assertFalse($this->protectionRule->hasProtection('non_existing_plugin_id'));
  }

  /**
   * Tests isProtected().
   */
  protected function testIsProtected() {
    // Create an user with administrator role.
    $values = array(
      'uid' => 3,
      'name' => 'lorem',
      'roles' => array(
        'administrator',
      ),
    );
    $lorem = entity_create('user', $values);

    // Create an authenticated user.
    $values = array(
      'uid' => 4,
      'name' => 'ipsum',
    );
    $ipsum = entity_create('user', $values);

    // Create an operating account.
    $account = new UserSession();

    // Assert that the operation is protected on the user with the administrator
    // role and not on the authenticated user.
    $this->assertTrue($this->protectionRule->isProtected($lorem, 'user_mail', $account));
    $this->assertFalse($this->protectionRule->isProtected($ipsum, 'user_mail', $account));
  }
}
