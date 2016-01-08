<?php

/**
 * @file
 * Contains \Drupal\userprotect\Annotation\UserProtection.
 */

namespace Drupal\userprotect\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an user protection annotation object.
 *
 * @Annotation
 */
class UserProtection extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the protection.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $label;

  /**
   * A brief description of the protection.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation (optional)
   */
  public $description = '';

  /**
   * A default weight used for presentation in the user interface only.
   *
   * @var int (optional)
   */
  public $weight = 0;

  /**
   * Whether this protection is enabled or disabled by default.
   *
   * @var bool (optional)
   */
  public $status = FALSE;
}
