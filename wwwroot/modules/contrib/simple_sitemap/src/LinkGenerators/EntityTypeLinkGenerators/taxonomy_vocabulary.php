<?php
/**
 * @file
 * Contains \Drupal\simplesitemap\LinkGenerators\EntityTypeLinkGenerators\taxonomy_vocabulary.
 *
 * Plugin for taxonomy term entity link generation.
 * See \Drupal\simplesitemap\LinkGenerators\CustomLinkGenerator\node_type for more
 * documentation.
 */

namespace Drupal\simplesitemap\LinkGenerators\EntityTypeLinkGenerators;

use Drupal\simplesitemap\LinkGenerators\EntityLinkGenerator;
use Drupal\Core\Url;

/**
 * taxonomy_vocabulary class.
 */
class taxonomy_vocabulary extends EntityLinkGenerator {

  function get_entity_bundle_links($bundle, $languages) {

    $results = db_query("SELECT tid FROM {taxonomy_term_field_data} WHERE vid = :vid", array(':vid' => $bundle))
      ->fetchAllAssoc('tid');

    $urls = array();
    foreach ($results as $id => $changed) {
      foreach($languages as $language) {
        $urls[$id][$language->getId()] = Url::fromRoute("entity.taxonomy_term.canonical", array('taxonomy_term' => $id), array(
          'language' => $language,
          'absolute' => TRUE
        ))->toString();
      }
    }
    return $urls;
  }
}
