<?php
/**
 * @file
 * Contains \Drupal\simplesitemap\Simplesitemap.
 */

namespace Drupal\simplesitemap;

/**
 * Simplesitemap class.
 */
class Simplesitemap {

  const SITEMAP_PLUGIN_PATH = 'src/LinkGenerators/EntityTypeLinkGenerators';

  private $config;
  private $sitemap;

  function __construct() {
    $this->set_config();
  }

  public static function get_form_entity($form_state) {
    if (!is_null($form_state->getFormObject())
      && method_exists($form_state->getFormObject(), 'getEntity')) {
      $entity = $form_state->getFormObject()->getEntity();
      return $entity;
    }
    return FALSE;
  }

  public static function get_plugin_path($entity_type_name) {
    $class_path = drupal_get_path('module', 'simplesitemap')
      . '/' . self::SITEMAP_PLUGIN_PATH . '/' . $entity_type_name . '.php';
    if (file_exists($class_path)) {
      return $class_path;
    }
    return FALSE;
  }

  private function set_config() {
    $this->get_config_from_db();
    $this->get_sitemap_from_db();
  }

  // Get sitemap from database.
  private function get_sitemap_from_db() {
    //todo: update for chunked sitemaps
    $result = db_query("SELECT sitemap_string FROM {simplesitemap}")->fetchAll();
    $this->sitemap = !empty($result[0]->sitemap_string) ? $result[0]->sitemap_string : NULL;
  }

  // Get sitemap settings from configuration storage.
  private function get_config_from_db() {
    $this->config = \Drupal::config('simplesitemap.settings');
  }

  public function save_entity_types($entity_types) {
    $this->save_config('entity_types', $entity_types);
  }

  public function save_custom_links($custom_links) {
    $this->save_config('custom', $custom_links);
  }

  private function save_config($key, $value) {
    \Drupal::service('config.factory')->getEditable('simplesitemap.settings')
      ->set($key, $value)->save();
    $this->set_config();
  }

  public function get_sitemap() {
    if (empty($this->sitemap)) {
      $this->generate_sitemap();
    }
    return $this->sitemap;
  }

  public function generate_sitemap() {
    $generator = new SitemapGenerator();
    $generator->set_custom_links($this->config->get('custom'));
    $generator->set_entity_types($this->config->get('entity_types'));
    $this->sitemap = $generator->generate_sitemap();
    $this->save_sitemap();
    drupal_set_message(t("The <a href='@url' target='_blank'>XML sitemap</a> has been regenerated for all languages.",
      array('@url' => $GLOBALS['base_url'] . '/sitemap.xml')));
  }

  private function save_sitemap() {
    //todo: update for chunked sitemaps
    db_merge('simplesitemap')
      ->key(array('id' => 1))
      ->fields(array(
        'id' => 1,
        'sitemap_string' => $this->sitemap,
      ))
      ->execute();
  }

  public function get_entity_types() {
    return $this->config->get('entity_types');
  }

  public function get_custom_links() {
    return $this->config->get('custom');
  }
}
