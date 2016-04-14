<?php
/**
 * @file
 * Contains \Drupal\acro_living_css\Controller\AcroLivingCSSController.
 */

namespace Drupal\acro_living_css\Controller;

use Drupal\Core\Controller\ControllerBase;

class LivingCSSController extends ControllerBase {
  public function content() {
    return array(
      '#theme' => 'live_css'
    );

  }
}
?>




