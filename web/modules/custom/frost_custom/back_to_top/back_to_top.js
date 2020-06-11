(function ($, Drupal) {
  Drupal.behaviors.backToTop = {
    attach: function (context, settings) {
      var offset = 300,
        scroll_top_duration = 700,
        $back_to_top = $('.block-back-to-top-block');

      if ($back_to_top.length) {
        var lastScrollTop = 0;
        $(window).scroll(function () {
          var st = $(this).scrollTop();
          if (st > offset && st < lastScrollTop) {
            $back_to_top.addClass('visible');
          }
          else {
            $back_to_top.removeClass('visible');
          }

          lastScrollTop = st;
        });

        $back_to_top.unbind().click(function (e) {
          e.preventDefault();
          $('body,html').animate({
            scrollTop: 0
          }, scroll_top_duration);
        });
      }
    }
  }
})(jQuery, Drupal);
