/**
 * Custom Scripts
 */

(function ($, Drupal) {

  // Mobile Navigation
  $('.b-mobile-nav-toggle').sidr({
    name: 'sidr',
    side: 'right',
    onOpen: function(name) {
      $('#mobile-overlay').fadeIn('fast');
    },
    onClose: function(name) {
      $("#mobile-overlay").fadeOut("fast");
    }
  });

  $('.b-mobile-nav-close, #mobile-overlay').click(function() {
    $.sidr('close', 'sidr');
  });

  // Close Sidr on window resize
  var $window = $(window);
  function checkWidth() {
    $.sidr('close', 'sidr');
  }
  $(window).resize(checkWidth);

})(jQuery, Drupal);
