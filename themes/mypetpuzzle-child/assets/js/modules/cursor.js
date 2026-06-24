(function () {
  'use strict';

  if (window.matchMedia('(max-width: 1023px)').matches) return;

  const cursor = document.createElement('div');
  cursor.className = 'puzzle-cursor';
  document.body.appendChild(cursor);

  let mouseX = 0, mouseY = 0;
  let posX = 0, posY = 0;
  const speed = 0.18;

  document.addEventListener('mousemove', function (e) {
    mouseX = e.clientX;
    mouseY = e.clientY;
  });

  function loop() {
    posX += (mouseX - posX) * speed;
    posY += (mouseY - posY) * speed;
    cursor.style.left = posX + 'px';
    cursor.style.top = posY + 'px';
    requestAnimationFrame(loop);
  }
  loop();

  const hoverTargets = 'a, button, input, select, textarea, [onclick], .btn, [role="button"]';

  document.addEventListener('mouseover', function (e) {
    const target = e.target.closest(hoverTargets);
    if (target) {
      cursor.classList.add('puzzle-cursor--hover');
    }
  });

  document.addEventListener('mouseout', function (e) {
    const target = e.target.closest(hoverTargets);
    if (target) {
      cursor.classList.remove('puzzle-cursor--hover');
    }
  });

  document.addEventListener('mouseleave', function () {
    cursor.style.display = 'none';
  });
  document.addEventListener('mouseenter', function () {
    cursor.style.display = 'block';
  });
})();
