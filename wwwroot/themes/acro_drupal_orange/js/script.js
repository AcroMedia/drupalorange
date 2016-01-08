/**
 * Custom Scripts
 */

(function () {
  'use strict';

  /* -- Homepage Rich Media -- */
  if($('.homepage-rich-media').length) {
    $('.homepage-rich-media-banners-slider').flexslider({
      directionNav: true,
      controlNav: true,
      animation: "fade",
      slideshow: true,
      keyboard: false
    });
  }

  /* -- Language Switcher -- */
  function languageSwitcherOver() {
    $(this).addClass('dropdown-hover');
    $(this).find('.language-switcher-dropdown').fadeIn('fast');
  }
  function languageSwitcherOut() {
    $(this).removeClass('dropdown-hover');
    $(this).find('.language-switcher-dropdown').fadeOut('fast');
  }
  $(".language-switcher-cont ul > li").hoverIntent(languageSwitcherOver, languageSwitcherOut);

  /* -- Language Switcher - Dropdown Arrow -- */
  $('.language-switcher-current a.is-active').append("<i></i>");

  /* -- User Navigation - Separators -- */
  $('.site-header-user-nav > ul > li').append("<i></i>");

  /* -- Header Navigation - Dropdown Arrows -- */
  $('.site-header-nav .menu-item--expanded a').append("<i></i>");

  /* -- Social Media Navigation - Adds Icons -- */
  $('.social-media-nav .menu-item a').prepend("<i></i>");

})();
