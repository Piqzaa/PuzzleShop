document.addEventListener('DOMContentLoaded', function () {
  var tabs = document.querySelectorAll('.auth-tab');
  var panels = {};

  var loginPanel = document.querySelector('.auth-panel--login');
  var registerPanel = document.querySelector('.auth-panel--register');

  if (!tabs.length) return;

  panels.login = loginPanel;
  panels.register = registerPanel;

  function switchAuth(section) {
    var i;
    for (i = 0; i < tabs.length; i++) {
      tabs[i].classList.toggle('is-active', tabs[i].getAttribute('data-auth') === section);
      tabs[i].setAttribute('aria-selected', tabs[i].getAttribute('data-auth') === section ? 'true' : 'false');
    }
    for (var key in panels) {
      if (panels[key]) {
        panels[key].classList.toggle('is-visible', key === section);
      }
    }
  }

  var i;
  for (i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener('click', function () {
      switchAuth(this.getAttribute('data-auth'));
    });
  }

  var switchBtns = document.querySelectorAll('.auth-switch-btn');
  var j;
  for (j = 0; j < switchBtns.length; j++) {
    switchBtns[j].addEventListener('click', function () {
      switchAuth(this.getAttribute('data-auth'));
    });
  }
});
