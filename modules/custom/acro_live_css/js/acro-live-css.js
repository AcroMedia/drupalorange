/**
 * Acro Live JS
 */

(function ($, Drupal) {

  // Live CSS Nav
  if ($('.live-css-nav').length) {
    $('body').scrollspy({
      target: '.live-css-nav-col'
    });

    $('.live-css-nav').affix({
      offset: {
        top: $('.live-css-nav').offset().top
      }
    });
  }

  //TYPOGRAPHY
  //Append font-family to any element with class .print-font-family
  $('.print-font-family').each(function() {
    var fontFamily = $(this).css('font-family');
    $(this).append(' - ' + fontFamily);
  });
  //Append font-size to any element with class .print-font-size
  $('.print-font-size').each(function() {
    var fontSize = $(this).css('font-size');
    $(this).append(' - ' + fontSize);
  });

})(jQuery, Drupal);
