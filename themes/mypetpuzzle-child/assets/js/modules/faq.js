(function () {
  'use strict';

  const faqItems = document.querySelectorAll('.faq__item');

  if (!faqItems.length) return;

  faqItems.forEach(function (item) {
    var question = item.querySelector('.faq__question');
    var answer = item.querySelector('.faq__answer');

    if (!question || !answer) return;

    question.addEventListener('click', function () {
      var isOpen = question.getAttribute('aria-expanded') === 'true';

      faqItems.forEach(function (other) {
        var otherQ = other.querySelector('.faq__question');
        var otherA = other.querySelector('.faq__answer');
        if (otherQ && otherA && other !== item) {
          otherQ.setAttribute('aria-expanded', 'false');
          otherA.hidden = true;
        }
      });

      question.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      answer.hidden = isOpen ? true : false;
    });
  });
})();
