(function () {
  var el = document.getElementById('registration-notice');
  if (!el) return;
  var close = function () {
    el.style.opacity = '0';
    el.style.transform = 'translateY(-100%)';
    setTimeout(function () { el.style.display = 'none'; }, 400);
  };
  el.addEventListener('click', close);
  setTimeout(close, 8000);
})();
