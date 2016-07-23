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
  $('.typography').each(function() {
    var fontFamily = $(this).css('font-family');
    $('.print-font-family').html(fontFamily);
  });

  $('.typography').each(function() {
    var fontSize = $(this).css('font-size');
    $('.print-font-size').html(fontSize);
  });

})(jQuery, Drupal);
