/**
 * Custom Scripts
 */


(function ($) {

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

  // Tabs
  if ($('.content-tabs').length) {

    $('.content-tabs a').click(function(e) {
      $(this).tab('show');

      e.preventDefault();
    })
  }

})(jQuery);
