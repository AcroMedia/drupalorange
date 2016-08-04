/**
 * Custom Scripts
 */

(function ($, Drupal) {

  // Mobile Navigation
  $('.header__top-mobile-toggle').sidr({
    name: 'sidr',
    side: 'right',
    onOpen: function(name) {
      $('#mobile-overlay').fadeIn('fast');
    },
    onClose: function(name) {
      $("#mobile-overlay").fadeOut("fast");
    }
  });

  $('.sidr__content-close-btn, #mobile-overlay').click(function() {
    $.sidr('close', 'sidr');
  });

  // Close Sidr on window resize
  var $window = $(window);
  function checkWidth() {
    $.sidr('close', 'sidr');
  }
  $(window).resize(checkWidth);

  //Double Tap parent of dropdown on touch devices
  $('.menu__dropdown-parent').doubleTapToGo();

  //Add table class to all of the tables added to content
  $('.content__main-content table').addClass('table');

  //Make tables responsive
  $('.content__main-content .table').wrap('<div class="table-responsive"></div>');

  //Add classes to aligned images in content to add styles
  $('.content__main-content img').each(function() {
    var float = $(this).css('float');
    if(float == 'right')
      $(this).addClass('img-right');
    else if(float == 'left')
      $(this).addClass('img-left');
  });

  $('#show-search').click(function() {
    $('#header-search').toggle('slow', function() {
      // Animation complete.
    });
  });

})(jQuery, Drupal);