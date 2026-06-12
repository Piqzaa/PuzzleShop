(function () {
  'use strict';

  var animateElements = document.querySelectorAll('[data-animate]');

  if (!animateElements.length) return;

  if (!('IntersectionObserver' in window)) {
    for (var i = 0; i < animateElements.length; i++) {
      animateElements[i].classList.add('is-visible');
    }
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
  );

  for (var j = 0; j < animateElements.length; j++) {
    observer.observe(animateElements[j]);
  }
})();
