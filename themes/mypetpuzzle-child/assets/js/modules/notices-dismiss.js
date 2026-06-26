document.addEventListener('DOMContentLoaded', function () {
  const dismissNotices = function () {
    document.querySelectorAll('.woocommerce-message, .woocommerce-info, .woocommerce-error').forEach(function (el) {
      if (!el.dataset.dismissTimer) {
        el.dataset.dismissTimer = 'true';
        el.style.transition = 'opacity .5s ease';
        setTimeout(function () {
          el.style.opacity = '0';
          setTimeout(function () { el.remove(); }, 500);
        }, 4000);
      }
    });
  };

  dismissNotices();
  new MutationObserver(dismissNotices).observe(document.body, { childList: true, subtree: true });
});
