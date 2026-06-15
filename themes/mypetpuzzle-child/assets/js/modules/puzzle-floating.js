(function () {
  'use strict';

  var canvas = document.querySelector('.hero__canvas');
  if (!canvas) return;

  var hero = canvas.parentElement;
  var ctx = canvas.getContext('2d');
  var pieces = [];
  var mouseX = -9999, mouseY = -9999;

  function repulseRadius() {
    return isSmallScreen() ? 80 : 180;
  }

  function isSmallScreen() {
    return window.matchMedia('(max-width: 1023px)').matches;
  }

  var palette = [
    '#6B8F71', '#7B9CB5', '#B8917A', '#A8B5A0',
    '#C49B8B', '#8FA3A8', '#9B8B7A', '#7A9B8B',
    '#B5A08F', '#90A1A8', '#A28B7A', '#8AA0A8',
  ];

  function rand(a, b) {
    return a + Math.random() * (b - a);
  }

  function resize() {
    var rect = hero.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
  }

  function Piece() {
    var size = isSmallScreen() ? rand(16, 44) : rand(18, 52);
    this.x = rand(0, canvas.width);
    this.y = rand(0, canvas.height);
    this.size = size;
    this.vx = rand(-0.2, 0.2);
    this.vy = rand(-0.2, 0.2);
    this.rot = rand(0, Math.PI * 2);
    this.rotSpeed = rand(-0.008, 0.008);
    this.color = palette[Math.floor(Math.random() * palette.length)];
    this.opacity = rand(0.2, 0.5);
  }

  Piece.prototype.tick = function () {
    this.vx += rand(-0.04, 0.04);
    this.vy += rand(-0.04, 0.04);
    this.vx *= 0.995;
    this.vy *= 0.995;

    var dx = this.x - mouseX;
    var dy = this.y - mouseY;
    var dist = Math.sqrt(dx * dx + dy * dy);
    if (dist < repulseRadius() && dist > 0) {
      var strength = (1 - dist / repulseRadius()) * 1.6;
      this.vx += (dx / dist) * strength;
      this.vy += (dy / dist) * strength;
    }

    this.x += this.vx;
    this.y += this.vy;
    this.rot += this.rotSpeed;

    var m = this.size * 0.6;
    if (this.x < -m) this.x = canvas.width + m;
    if (this.x > canvas.width + m) this.x = -m;
    if (this.y < -m) this.y = canvas.height + m;
    if (this.y > canvas.height + m) this.y = -m;
  };

  Piece.prototype.draw = function () {
    ctx.save();
    ctx.translate(this.x, this.y);
    ctx.rotate(this.rot);
    ctx.globalAlpha = this.opacity;

    var s = this.size / 100;
    ctx.scale(s, s);

    ctx.beginPath();
    ctx.moveTo(15, 15);
    ctx.lineTo(40, 15);
    ctx.bezierCurveTo(40, 0, 60, 0, 60, 15);
    ctx.lineTo(85, 15);
    ctx.lineTo(85, 40);
    ctx.bezierCurveTo(65, 40, 65, 60, 85, 60);
    ctx.lineTo(85, 85);
    ctx.lineTo(60, 85);
    ctx.bezierCurveTo(60, 100, 40, 100, 40, 85);
    ctx.lineTo(15, 85);
    ctx.lineTo(15, 60);
    ctx.bezierCurveTo(35, 60, 35, 40, 15, 40);
    ctx.closePath();

    ctx.fillStyle = this.color;
    ctx.fill();
    ctx.restore();
  };

  function init() {
    resize();
    var count = isSmallScreen() ? 12 : 32;
    pieces = [];
    for (var i = 0; i < count; i++) {
      pieces.push(new Piece());
    }
  }

  function loop() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (var i = 0; i < pieces.length; i++) {
      pieces[i].tick();
      pieces[i].draw();
    }
    requestAnimationFrame(loop);
  }

  init();
  loop();

  window.addEventListener('resize', init);

  document.addEventListener('mousemove', function (e) {
    var rect = hero.getBoundingClientRect();
    mouseX = e.clientX - rect.left;
    mouseY = e.clientY - rect.top;
  });

  document.addEventListener('mouseleave', function () {
    mouseX = -9999;
    mouseY = -9999;
  });
})();
