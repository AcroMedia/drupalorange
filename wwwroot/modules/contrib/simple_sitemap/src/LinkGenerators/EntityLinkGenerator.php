<?php
/**
 * @file
 * Contains \Drupal\simplesitemap\LinkGenerators\EntityLinkGenerator.
 *
 * Abstract class to be extended for plugin creation.
 * See \Drupal\simplesitemap\LinkGenerators\CustomLinkGenerator\node for more
 * documentation.
 */

namespace Drupal\simplesitemap\LinkGenerators;
use Drupal\simplesitemap\SitemapGenerator;

/**
 * EntityLinkGenerator abstract class.
 */
abstract class EntityLinkGenerator {

  private $entity_links = array();

  public function get_entity_links($entity_type, $bundles, $languages) {
    $i = 0;
    foreach($bundles as $bundle => $bundle_settings) {
      if (!$bundle_settings['index']) {
        continue;
      }
      $links = $this->get_entity_bundle_links($bundle, $languages);
      foreach ($links as $id => $link) {
        $this->entity_links[$i]['url'] = $link;
        $this->entity_links[$i]['priority'] = $bundle_settings['priority'];
        $this->entity_links[$i]['lastmod'] = $this->get_lastmod($entity_type, $id);
        $i++;
      }
    }
    return $this->entity_links;
  }

  private function get_lastmod($entity_type, $id) {
    switch ($entity_type) {
      case 'node_type':
        $lastmod = db_query("SELECT changed FROM {node_field_data} WHERE nid = :nid LIMIT 1", array(':nid' => $id))->fetchCol();
        break;
      case 'taxonomy_vocabulary':
        $lastmod = db_query("SELECT changed FROM {taxonomy_term_field_data} WHERE tid = :tid LIMIT 1", array(':tid' => $id))->fetchCol();
        break;
      case 'menu':
        //todo: to be implemented
    }
    return isset($lastmod[0]) ? date_iso8601($lastmod[0]) : NULL;
  }

  abstract function get_entity_bundle_links($bundle, $languages);
}
