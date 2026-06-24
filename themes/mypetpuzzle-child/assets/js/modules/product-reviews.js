(function($) {
  'use strict';

  function initStarRating() {
    $('body').off('click', '#respond p.stars a');

    $(document).on('click', '#respond p.stars a', function() {
      var $star = $(this),
          $stars = $star.closest('.stars').find('a'),
          $rating = $star.closest('#respond').find('#rating'),
          $container = $star.closest('.stars'),
          clickedIndex = $stars.index($star);

      $rating.val($stars.length - clickedIndex);

      $stars.each(function(i) {
        var $this = $(this);
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
