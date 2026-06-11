(function () {
  "use strict";

  const header = document.getElementById("header");
  const hamburger = document.getElementById("header-hamburger");
  const nav = document.getElementById("header-nav");

  if (!header || !hamburger || !nav) {
    return;
  }

  let scrollTicking = false;

  function updateScrollState() {
    header.classList.toggle("header--scrolled", window.scrollY > 10);
    scrollTicking = false;
  }

  function onScroll() {
    if (!scrollTicking) {
      window.requestAnimationFrame(updateScrollState);
      scrollTicking = true;
    }
  }

  function toggleMenu(expanded) {
    const isExpanded =
      expanded !== undefined
        ? expanded
        : hamburger.getAttribute("aria-expanded") !== "true";
    hamburger.setAttribute("aria-expanded", isExpanded);
    nav.classList.toggle("header__nav--is-open", isExpanded);
    document.body.classList.toggle("header-nav--open", isExpanded);
  }

  hamburger.addEventListener("click", function () {
    toggleMenu();
  });

  nav.addEventListener("click", function (e) {
    if (e.target.tagName === "A") {
      toggleMenu(false);
    }
  });

  document.addEventListener("click", function (e) {
    if (
      !header.contains(e.target) &&
      nav.classList.contains("header__nav--is-open")
    ) {
      toggleMenu(false);
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && nav.classList.contains("header__nav--is-open")) {
      toggleMenu(false);
      hamburger.focus();
    }
  });

  updateScrollState();
  window.addEventListener("scroll", onScroll, { passive: true });
})();
