(function ($, Drupal) {
  "use strict";

  /**
   * @file
   * Integrates Imce into file field widgets.
   */

  /**
   * Global container for helper methods.
   */
  var imceFileField = window.imceFileField = {};

  /**
   * Drupal behavior to handle imce file field integration.
   */
  Drupal.behaviors.imceFileField = {
    attach: function (context, settings) {
      var i;
      var button;
      var $buttons = $('.imce-filefield-button', context).not('.iff-processed').addClass('iff-processed');
      for (i = 0; button = $buttons[i]; i++) {
        imceFileField.processButton(button);
      }
    }
  };

  /**
   * Processes an imce file field button to create a widget.
   */
  imceFileField.processButton = function (button) {
    var el;
    var url = button.getAttribute('data-imce-url');
    var fieldId = button.getAttribute('data-drupal-selector').split('-imce-button')[0];
    if (url && fieldId) {
      url += (url.indexOf('?') === -1 ? '?' : '&') + 'sendto=imceFileField.sendto&fieldId=' + fieldId;
      el = $(imceFileField.createWidget(url)).insertBefore(button.parentNode)[0];
      el.parentNode.className += ' imce-filefield-parent';
    }
    return el;
  };

  /**
   * Creates an imce file field widget with the given url.
   */
  imceFileField.createWidget = function (url) {
    var $link = $('<a class="imce-filefield-link">' + Drupal.t('Open File Browser') + '</a>');
    $link.attr('href', url).click(imceFileField.eLinkClick);
    return $('<div class="imce-filefield-widget"></div>').append($link)[0];
  };

  /**
   * Click event for the browser link.
   */
  imceFileField.eLinkClick = function (e) {
    window.open(this.href, '', 'width=760,height=560,resizable=1');
    e.preventDefault();
  };

  /**
   * Handler for imce sendto operation.
   */
  imceFileField.sendto = function (File, win) {
    var imce = win.imce;
    var items = imce.getSelection();
    var fieldId = imce.getQuery('fieldId');
    var exts = imceFileField.getFieldExts(fieldId);
    // Check extensions
    if (exts && !imce.validateExtensions(items, exts)) {
      return;
    }
    // Submit form with selected item paths
    imceFileField.submit(fieldId, imce.getItemPaths(items));
    win.close();
  };


  /**
   * Returns allowed extensions for a field.
   */
  imceFileField.getFieldExts = function (fieldId) {
    var settings = drupalSettings.file;
    var elements = settings && settings.elements;
    return elements ? elements['#' + fieldId] : false;
  };

  /**
   * Submits a field widget with selected file paths.
   */
  imceFileField.submit = function (fieldId, paths) {
    $('[data-drupal-selector="' + fieldId + '-imce-paths"]').val(paths.join(':'));
    $('[data-drupal-selector="' + fieldId + '-imce-button"]').mousedown();
  };

})(jQuery, Drupal);
