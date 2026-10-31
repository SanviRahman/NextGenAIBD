/* Last-resort display protection for legacy views. Does not alter files or DB. */
(function () {
  'use strict';
  var placeholder = '/frontend/assets/images/sda-media-placeholder.svg';
  document.addEventListener('error', function (event) {
    var element = event.target;
    if (!element || element.tagName !== 'IMG' || element.dataset.sdaFallbackApplied) return;
    try {
      var original = new URL(element.currentSrc || element.src, location.href);
      if (original.origin !== location.origin ||
          !/^\/(?:media|frontend\/assets|backend\/assets)\//.test(original.pathname) ||
          original.pathname === placeholder) return;
      element.dataset.sdaFallbackApplied = '1';
      element.src = placeholder;
    } catch (_) { /* Ignore malformed URLs; do not risk an error loop. */ }
  }, true);
}());
