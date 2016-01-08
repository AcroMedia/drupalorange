<?php

/**
 * @file
 * Contains \Drupal\imce\Plugin\CKEditorPlugin\Imce.
 */

namespace Drupal\imce\Plugin\CKEditorPlugin;

use Drupal\editor\Entity\Editor;
use Drupal\ckeditor\CKEditorPluginBase;

/**
 * Defines Imce plugin for CKEditor.
 *
 * @CKEditorPlugin(
 *   id = "imce",
 *   label = "Imce File Manager"
 * )
 */
class Imce extends CKEditorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'imce') . '/js/plugins/ckeditor/imce.ckeditor.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return array(
      'ImceImage' => array(
        'label' => t('Insert images using Imce File Manager'),
        'image' => $this->imageIcon(),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return array('ImceImageIcon' => file_create_url($this->imageIcon()));
  }

  /**
   * Returns image icon path.
   * Use the icon from drupalimage plugin.
   */
  public function imageIcon() {
    return drupal_get_path('module', 'ckeditor') . '/js/plugins/drupalimage/image.png';
  }

}
