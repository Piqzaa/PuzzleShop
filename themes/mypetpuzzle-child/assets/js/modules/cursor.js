(function () {
  'use strict';

  if (window.matchMedia('(max-width: 1023px)').matches) return;

  var cursor = document.createElement('div');
  cursor.className = 'puzzle-cursor';
  document.body.appendChild(cursor);

  var mouseX = 0, mouseY = 0;
  var posX = 0, posY = 0;
  var speed = 0.18;

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

  var hoverTargets = 'a, button, input, select, textarea, [onclick], .btn, [role="button"]';

  document.addEventListener('mouseover', function (e) {
    var target = e.target.closest(hoverTargets);
    if (target) {
      cursor.classList.add('puzzle-cursor--hover');
    }
  });

  document.addEventListener('mouseout', function (e) {
    var target = e.target.closest(hoverTargets);
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
