(function () {
  'use strict';

  const COOKIE_NAME = 'mypetpuzzle_cookies_accepted';
  const banner = document.getElementById('cookie-banner');
  const acceptBtn = document.getElementById('cookie-accept');
  const refuseBtn = document.getElementById('cookie-refuse');
  const manageLink = document.getElementById('cookie-manage');

  if (!banner || !acceptBtn) return;

  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }

  function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + expires.toUTCString() + ';path=/;SameSite=Lax';
  }

  if (getCookie(COOKIE_NAME)) {
    banner.hidden = true;
    return;
  }

  banner.hidden = false;

  acceptBtn.addEventListener('click', function () {
    setCookie(COOKIE_NAME, 'accepted', 365);
    banner.hidden = true;
  });

  if (refuseBtn) {
    refuseBtn.addEventListener('click', function () {
      setCookie(COOKIE_NAME, 'refused', 365);
      banner.hidden = true;
    });
  }

  if (manageLink) {
    manageLink.addEventListener('click', function (e) {
      e.preventDefault();
    });
  }
})();
