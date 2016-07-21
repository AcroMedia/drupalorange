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


  //TYPOGRAPHY
  //Print the font size and family
  var heading = $('.typography :header'),
    headingDescriptionText = heading.children('span').eq(0),
    body = heading.next('p'),
    bodyDescriptionText = body.children('span').eq(0);

  setTypography(heading, headingDescriptionText);
  setTypography(body, bodyDescriptionText);
  $(window).on('resize', function(){
    setTypography(heading, headingDescriptionText);
    setTypography(body, bodyDescriptionText);
  });

  function setTypography(element, textElement) {
    var fontSize = Math.round(element.css('font-size').replace('px',''))+'px',
      fontFamily = (element.css('font-family').split(','))[0].replace(/\'/g, '').replace(/\"/g, ''),
      fontWeight = element.css('font-weight');
    textElement.text(fontWeight + ' '+ fontFamily+' '+fontSize );
  }


})(jQuery, Drupal);
