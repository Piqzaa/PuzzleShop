(function () {
  'use strict';

  const faqItems = document.querySelectorAll('.faq__item');

  if (!faqItems.length) return;

  faqItems.forEach(function (item) {
    const question = item.querySelector('.faq__question');
    const answer = item.querySelector('.faq__answer');

    if (!question || !answer) return;

    question.addEventListener('click', function () {
      const isOpen = question.getAttribute('aria-expanded') === 'true';

      faqItems.forEach(function (other) {
        const otherQ = other.querySelector('.faq__question');
        const otherA = other.querySelector('.faq__answer');
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
