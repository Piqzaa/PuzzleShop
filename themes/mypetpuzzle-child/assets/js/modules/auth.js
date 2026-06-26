document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.auth-tab');
  const panels = {};

  const loginPanel = document.querySelector('.auth-panel--login');
  const registerPanel = document.querySelector('.auth-panel--register');

  if (!tabs.length) return;

  panels.login = loginPanel;
  panels.register = registerPanel;

  function switchAuth(section) {
    for (let i = 0; i < tabs.length; i++) {
      tabs[i].classList.toggle('is-active', tabs[i].getAttribute('data-auth') === section);
      tabs[i].setAttribute('aria-selected', tabs[i].getAttribute('data-auth') === section ? 'true' : 'false');
    }
    for (const key in panels) {
      if (panels[key]) {
        panels[key].classList.toggle('is-visible', key === section);
      }
    }
  }

  for (let i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener('click', function () {
      switchAuth(this.getAttribute('data-auth'));
    });
  }

  const switchBtns = document.querySelectorAll('.auth-switch-btn');
  for (let j = 0; j < switchBtns.length; j++) {
    switchBtns[j].addEventListener('click', function () {
      switchAuth(this.getAttribute('data-auth'));
    });
  }
});
