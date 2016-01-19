<?php
/**
 * @file
 * Contains \Drupal\simplesitemap\LinkGenerators\EntityTypeLinkGenerators\node_type.
 *
 * Plugin for node entity link generation.
 *
 * This can be used as a template to create new plugins for other configuration
 * entity types. To create a plugin simply create a new class file in the
 * EntityTypeLinkGenerators folder. Name this file after the configuration
 * entity type id (eg. 'node_type' or 'taxonomy_vocabulary'.)
 * This class needs to extend the EntityLinkGenerator class and include
 * the get_entity_bundle_links() method. - as shown here. This method has to
 * return an array of pure urls to the entities of the configuration entity type
 * in question.
 */

namespace Drupal\simplesitemap\LinkGenerators\EntityTypeLinkGenerators;

use Drupal\simplesitemap\LinkGenerators\EntityLinkGenerator;
use Drupal\Core\Url;

/**
 * node_type class.
 */
class node_type extends EntityLinkGenerator {

  function get_entity_bundle_links($bundle, $languages) {
    $results = db_query("SELECT nid FROM {node_field_data} WHERE status = 1 AND type = :type", array(':type' => $bundle))
      ->fetchAllAssoc('nid');
    $urls = array();
    foreach ($results as $id => $changed) {
      foreach($languages as $language) {
        $urls[$id][$language->getId()] = Url::fromRoute("entity.node.canonical", array('node' => $id), array(
          'language' => $language,
          'absolute' => TRUE
        ))->toString();
      }
    }
    return $urls;
  }
}
