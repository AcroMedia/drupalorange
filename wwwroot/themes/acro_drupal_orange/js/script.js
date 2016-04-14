/**
 * Custom Scripts
 */

(function ($, Drupal) {

  // Mobile Navigation
  $('#li-mobile-nav-toggle a').sidr({
    name: 'sidr',
    side: 'right',
    onOpen: function(name) {
      $('#mobile-overlay').fadeIn('fast');
    },
    onClose: function(name) {
      $("#mobile-overlay").fadeOut("fast");
    }
  });

  $('a#b-mobile-nav-close, #mobile-overlay').click(function() {
    $.sidr('close', 'sidr');
  });

  // Close Sidr on window resize
  var $window = $(window);

  function checkWidth() {
    $.sidr('close', 'sidr');
  }

  $(window).resize(checkWidth);

  // Mobile Search
  $('#li-mobile-search-toggle a').click(function(e) {
    $(this).toggleClass('active');
    $('#mobile-search-cont').slideToggle('fast');

    e.preventDefault();
  });

  // Homepage Rich Media
  if($('.homepage-rich-media').length) {
    $('.homepage-rich-media-banners-slider').flexslider({
      directionNav: true,
      controlNav: true,
      animation: "fade",
      slideshow: true,
      keyboard: false
    });
  }

  // Language Switcher
  function languageSwitcherOver() {
    $(this).addClass('dropdown-hover');
    $(this).find('.language-switcher-dropdown').fadeIn('fast');
  }
  function languageSwitcherOut() {
    $(this).removeClass('dropdown-hover');
    $(this).find('.language-switcher-dropdown').fadeOut('fast');
  }
  $('.language-switcher-cont ul > li').hoverIntent(languageSwitcherOver, languageSwitcherOut);

  // Add active class to first element if none are assigned active
  if (!$('.language-switcher-current ul li.is-active').length) {
    $('.language-switcher-current ul li').first().addClass('is-active');
  }

  // User Navigation - Separators
  $('.site-header-user-nav > ul > li').append("<i></i>");

  // Header Navigation - Dropdown Arrows
  $('.site-header-nav .menu-item--expanded a').append("<i></i>");

  // Social Media Navigation - Adds Icons
  $('.social-media-nav .menu-item a').prepend("<i></i>");

  // Blog Search
  $("#block-blog-search .form-search").attr("placeholder", Drupal.t('Enter Keyword') + '...');

  // Blog Categories
  $('.blog-categories-show-all a').click(function(e) {
    $('#block-blog-categories .views-row').fadeIn('fast');
    $(this).parent().hide();

    e.preventDefault();
  });

  // Image Gallery
  if ($('.image-gallery__slider').length) {
    $('.image-gallery-nav__slider').flexslider({
      animation: "slide",
      controlNav: false,
      animationLoop: false,
      slideshow: false,
      itemWidth: 90,
      itemMargin: 10,
      asNavFor: '.image-gallery__slider',
      keyboard: false,
      multipleKeyboard: true
    });

    $('.image-gallery__slider').flexslider({
      animation: "fade",
      controlNav: false,
      directionNav: false,
      animationLoop: false,
      slideshow: false,
      sync: ".image-gallery-nav__slider",
      keyboard: true,
      multipleKeyboard: true
    });

    // Image Gallery Hover
    $(".image-gallery__slider").hover(function() {
      $(this).find('ul.flex-direction-nav').stop(true, true).fadeIn('fast');

      // Caption
      $(this).find('.image-gallery__caption').stop(true, true).fadeIn('fast');
    }, function() {
      $(this).find('ul.flex-direction-nav').stop(true, true).fadeOut('fast');

      // Caption
      $(this).find('.image-gallery__caption').fadeOut('fast');
    });
  }

  // Accordion
  $(".base-accordion__header").click(function(e) {
    var _this = $(this);
    var _parent = _this.parent();
    var _thisBody = _this.parent().find('.base-accordion__content');

    _parent.toggleClass('active');

    _this.parent().find('.base-accordion__content').toggle('fast');
    $('.base-accordion__content').not(_thisBody).hide('fast');
    $('.base-accordion__item').not(_parent).removeClass('active');

    e.preventDefault();
  });

})(jQuery, Drupal);
