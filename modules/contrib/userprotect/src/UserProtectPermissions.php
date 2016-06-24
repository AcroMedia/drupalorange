<?php

/**
 * @file
 * Contains \Drupal\userprotect\UserProtectPermissions.
 */

namespace Drupal\userprotect;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides dynamic permissions of the filter module.
 */
class UserProtectPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructs a new UserProtectPermissions instance.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity.manager'));
  }

  /**
   * Returns an array of userprotect permissions.
   *
   * @return array
   *   An array of permissions to bypass protection rules.
   */
  public function permissions() {
    $permissions = [];
    // For each protection rule, create a permission to bypass the rule.
    /** @var \Drupal\userprotect\Entity\ProtectionRuleInterface[] $rules */
    $rules = $this->entityManager->getStorage('userprotect_rule')->loadMultiple();
    uasort($rules, 'Drupal\Core\Config\Entity\ConfigEntityBase::sort');
    foreach ($rules as $rule) {
      $vars = [
        '%label' => $rule->label(),
      ];
      $permissions += [
        $rule->getPermissionName() => [
          'title' => $this->t('Bypass user protection for %label', $vars),
          'description' => $this->t('The user protection rule %label is ignored for users with this permission.', $vars),
        ],
      ];
    }
    return $permissions;
  }

}
