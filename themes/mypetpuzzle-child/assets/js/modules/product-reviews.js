(function($) {
  'use strict';

  function initStarRating() {
    $('body').off('click', '#respond p.stars a');

    $(document).on('click', '#respond p.stars a', function() {
      const $star = $(this);
      const $stars = $star.closest('.stars').find('a');
      const $rating = $star.closest('#respond').find('#rating');
      const $container = $star.closest('.stars');
      const clickedIndex = $stars.index($star);

      $rating.val($stars.length - clickedIndex);

      $stars.each(function(i) {
        const $this = $(this);
        if (i >= clickedIndex) {
          $this.addClass('active').attr('aria-checked', 'true');
        } else {
          $this.removeClass('active').attr('aria-checked', 'false').attr('tabindex', '-1');
        }
      });

      $star.attr('tabindex', '0');

      $container.addClass('selected');

      return false;
    });
  }

  $(document).ready(initStarRating);
  $(document).on('DOMContentLoaded', initStarRating);
})(jQuery);
