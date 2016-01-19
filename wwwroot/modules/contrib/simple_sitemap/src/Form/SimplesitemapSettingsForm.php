<?php

/**
 * @file
 * Contains \Drupal\xmlsitemap\Form\SimplesitemapSettingsForm.
 */

namespace Drupal\simplesitemap\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\simplesitemap\Simplesitemap;

/**
 * SimplesitemapSettingsFrom
 */
class SimplesitemapSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'simplesitemap_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['simplesitemap.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['simplesitemap_settings']['rebuild'] = array(
      '#title' => t('Rebuild sitemap'),
      '#type' => 'fieldset',
      '#markup' => '<p>' . t('This will rebuild the XML sitemap for all languages.') . '</p>',
    );

    $form['simplesitemap_settings']['rebuild']['rebuild_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Rebuild sitemap'),
      '#submit' => array('::rebuild_sitemap'),
      '#validate' => array(), // Skip form-level validator.
    );

    return $form;
  }

  public function rebuild_sitemap(array &$form, FormStateInterface $form_state) {
    $sitemap = new Simplesitemap;
    $sitemap->generate_sitemap();
  }
}
