/* Step 05 ratings: pause when offscreen/hover, accessible no-motion fallback. */
(function () {
  'use strict';
  function init(root) {
    var rail = root.querySelector('[data-ratings-rail]');
    var original = root.querySelector('[data-ratings-original]');
    if (!rail || !original) return;
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    var allowed = root.dataset.autoplay === 'true';
    var count = original.querySelectorAll('.sda-ratings__item').length;
    var duplicate = null;
    if (count > 1 && allowed && !reduced.matches) {
      duplicate = original.cloneNode(true);
      duplicate.removeAttribute('data-ratings-original');
      duplicate.setAttribute('aria-hidden', 'true');
      duplicate.querySelectorAll('a').forEach(function (a) { a.tabIndex = -1; a.removeAttribute('title'); });
      rail.appendChild(duplicate);
      rail.classList.add('is-looping');
    }
    function update() {
      root.classList.toggle('is-paused', document.hidden || reduced.matches || !allowed);
      if (reduced.matches) rail.classList.remove('is-looping');
      else if (duplicate) rail.classList.add('is-looping');
    }
    document.addEventListener('visibilitychange', update);
    if (reduced.addEventListener) reduced.addEventListener('change', update);
    if ('IntersectionObserver' in window) {
      var revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
          }
        });
      }, { threshold: .12 });
      root.classList.add('is-enhanced');
      root.querySelectorAll('[data-ratings-reveal]').forEach(function (node) { revealObserver.observe(node); });
      var viewObserver = new IntersectionObserver(function (entries) {
        root.classList.toggle('is-outside', !entries[0].isIntersecting);
        root.style.setProperty('--sda-ratings-motion-paused', entries[0].isIntersecting ? 'running' : 'paused');
      }, { threshold: .05 });
      viewObserver.observe(root);
    }
    update();
  }
  function start() { document.querySelectorAll('[data-sda-ratings]').forEach(init); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
  else start();
}());
