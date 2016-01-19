<?php
/**
 * @file
 * Contains \Drupal\simplesitemap\LinkGenerators\CustomLinkGenerator.
 *
 * Generates custom sitemap links provided by the user.
 */

namespace Drupal\simplesitemap\LinkGenerators;

use Drupal\Core\Url;
use Drupal\simplesitemap\SitemapGenerator;

/**
 * CustomLinkGenerator class.
 */
class CustomLinkGenerator {

  public function get_custom_links($custom_paths, $languages) {
    $links = array();
    foreach($custom_paths as $i => $custom_path) {
      if (!isset($custom_path['index']) || $custom_path['index']) {
        foreach($languages as $language) {
          $links[$i]['url'][$language->getId()] = Url::fromUserInput($custom_path['path'], array(
            'language' => $language,
            'absolute' => TRUE
          ))->toString();
        }
        $links[$i]['priority'] = isset($custom_path['priority']) ? $custom_path['priority'] : NULL;
        $links[$i]['lastmod'] = NULL; //todo: implement
      }
    }
    return $links;
  }
}
