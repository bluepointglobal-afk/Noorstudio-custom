/* NoorStudio — homepage interactions
 * Vanilla JS, no dependencies. Loaded in the footer (defer-equivalent).
 * Mirrors the design source behaviour, plus accessibility + Page Visibility.
 */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- FAQ accordion (aria-expanded synced) ---- */
  document.querySelectorAll('.faq-q').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = btn.parentElement;
      var open = item.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });

  /* ---- Shelf filter tabs (visual state only; filtering is a backend task) ---- */
  document.querySelectorAll('.shelf-filter button').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.shelf-filter button').forEach(function (b) {
        b.classList.remove('active');
        b.setAttribute('aria-selected', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');
    });
  });

  /* ---- Mobile nav drawer ---- */
  var toggle = document.querySelector('.nav-toggle');
  var drawer = document.getElementById('noor-drawer');
  var overlay = document.querySelector('.nav-overlay');
  var closeBtn = document.querySelector('.nav-drawer-close');

  function setDrawer(open) {
    if (!drawer) return;
    drawer.classList.toggle('open', open);
    drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
    if (overlay) {
      overlay.classList.toggle('open', open);
      overlay.hidden = !open;
    }
    if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.style.overflow = open ? 'hidden' : '';
  }

  if (toggle) toggle.addEventListener('click', function () { setDrawer(true); });
  if (closeBtn) closeBtn.addEventListener('click', function () { setDrawer(false); });
  if (overlay) overlay.addEventListener('click', function () { setDrawer(false); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setDrawer(false);
  });

  /* ---- Hero main portrait rotation (5s, 320ms cross-fade) ---- */
  var rotateSet = [
    { src: 'img-02.jpg', style: '3D Cinematic' },
    { src: 'img-07.jpg', style: 'Heroic 3D' },
    { src: 'img-05.jpg', style: 'Professional 3D' },
    { src: 'img-15.jpg', style: 'Soft Storybook' },
    { src: 'img-03.jpg', style: '3D Cinematic' }
  ];

  var mainImg = document.querySelector('.p-main img');
  var tagName = document.querySelector('.style-tag-name');

  // Only rotate real <img> elements (the placeholder fallback renders a <span>).
  if (mainImg && tagName && !reduceMotion) {
    // Derive the assets base path from the current src so we don't hard-code it.
    var base = mainImg.getAttribute('src').replace(/img-02\.jpg.*$/, '');
    var i = 0;
    var timer = null;

    function tick() {
      i = (i + 1) % rotateSet.length;
      mainImg.style.opacity = '0';
      window.setTimeout(function () {
        mainImg.src = base + rotateSet[i].src;
        tagName.textContent = rotateSet[i].style;
        mainImg.style.opacity = '1';
      }, 320);
    }

    function start() { if (!timer) timer = window.setInterval(tick, 5000); }
    function stop() { if (timer) { window.clearInterval(timer); timer = null; } }

    // Pause when the tab is hidden (Core Web Vitals / battery friendliness).
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) { stop(); } else { start(); }
    });
    start();
  }
})();
