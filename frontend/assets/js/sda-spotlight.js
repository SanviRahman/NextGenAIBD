/* Sunnah Spotlight v1.2: live logo marquee, muted preview, click-to-play modal. */
(function () {
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
  var saveData = !!(navigator.connection && navigator.connection.saveData);

  function marqueeInit(root) {
    var viewport = root.querySelector('[data-sda-marquee-viewport]');
    var track = root.querySelector('[data-sda-marquee-track]');
    var prev = root.querySelector('[data-sda-marquee-prev]');
    var next = root.querySelector('[data-sda-marquee-next]');
    var originals = Array.from(track.children);
    if (!originals.length) return;
    var gap = parseFloat(getComputedStyle(track).gap) || 24;
    var oneWidth = 0;
    var offset = 0;
    var auto = root.getAttribute('data-autoplay') !== 'false';
    var paused = false;
    var inView = true;
    var lastTime = 0;
    var raf = 0;
    var speedSeconds = Math.max(18, Math.min(90, Number(root.getAttribute('data-scroll-seconds')) || 32));
    var loops = originals.length > 1;
    var clones = [];
    // Repeat enough cycles to fill a wide screen even when only two logos exist.
    // Only the originals are accessible; all repeats are inert and hidden from assistive tech.
    function ensureCopies() {
      if (!loops) return;
      gap = parseFloat(getComputedStyle(track).gap) || 24;
      var cycle = originals.reduce(function (sum, element) { return sum + element.getBoundingClientRect().width + gap; }, 0);
      if (!cycle) return;
      var needed = Math.max(2, Math.ceil((viewport.clientWidth + cycle) / cycle) + 1);
      while (clones.length < needed * originals.length) {
        var element = originals[clones.length % originals.length];
        var clone = element.cloneNode(true);
        clone.setAttribute('aria-hidden', 'true');
        clone.querySelectorAll('a,button,[tabindex]').forEach(function (node) { node.setAttribute('tabindex', '-1'); });
        track.appendChild(clone);
        clones.push(clone);
      }
    }
    ensureCopies();

    function measure() {
      gap = parseFloat(getComputedStyle(track).gap) || 24;
      oneWidth = originals.reduce(function (sum, element) { return sum + element.getBoundingClientRect().width + gap; }, 0);
      if (loops && oneWidth > 0) offset = ((offset % oneWidth) + oneWidth) % oneWidth;
    }

    function draw() { track.style.transform = 'translate3d(' + (-offset) + 'px,0,0)'; }
    function cycle(timestamp) {
      if (!lastTime) lastTime = timestamp;
      var delta = Math.min(100, timestamp - lastTime);
      lastTime = timestamp;
      if (loops && oneWidth > 0 && auto && !paused && inView && !document.hidden && !reduce.matches) {
        offset = (offset + delta * oneWidth / (speedSeconds * 1000)) % oneWidth;
        draw();
      }
      raf = requestAnimationFrame(cycle);
    }
    function nudge(direction) {
      if (!loops) return;
      measure();
      if (!oneWidth) return;
      var step = originals[0].getBoundingClientRect().width + gap;
      offset = ((offset + step * direction) % oneWidth + oneWidth) % oneWidth;
      draw();
    }
    prev.addEventListener('click', function () { nudge(-1); });
    next.addEventListener('click', function () { nudge(1); });
    root.addEventListener('mouseenter', function () { paused = true; });
    root.addEventListener('mouseleave', function () { paused = false; });
    root.addEventListener('focusin', function () { paused = true; });
    root.addEventListener('focusout', function (event) { if (!root.contains(event.relatedTarget)) paused = false; });
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) { inView = entries[0].isIntersecting; }, {threshold:.12}).observe(root);
    }
    window.addEventListener('resize', function () { ensureCopies(); measure(); draw(); }, {passive:true});
    // Decoded logo width changes need a second measurement.
    track.querySelectorAll('img').forEach(function (img) { img.addEventListener('load', function () { measure(); draw(); }, {once:true}); });
    measure(); draw();
    raf = requestAnimationFrame(cycle);
  }

  function previewInit(root) {
    var stage = root.querySelector('.sda-spotlight__video-stage');
    var preview = root.querySelector('[data-preview-file]');
    var previewEmbed = root.getAttribute('data-preview-embed');
    var modal = root.parentElement.querySelector('[data-spotlight-modal]');
    var play = root.querySelector('[data-spotlight-play]');
    var player = modal.querySelector('[data-spotlight-player]');
    var close = modal.querySelector('.sda-spotlight__modal-close');
    // Escape the section's overflow/stacking context so the modal truly covers the viewport.
    modal.style.setProperty('--sda-spotlight-accent',
      getComputedStyle(root.closest('.sda-spotlight')).getPropertyValue('--sda-spotlight-accent'));
    document.body.appendChild(modal);
    var fullUrl = root.getAttribute('data-full-url');
    var provider = root.getAttribute('data-full-provider');
    var autoplay = root.getAttribute('data-autoplay') !== 'false';
    var shouldPlay = false;
    var modalOpen = false;
    var returnFocus = null;
    var iframe = null;

    // Size a third-party 16:9 iframe like object-fit:cover. CSS object-fit
    // does not crop an iframe's internal video content.
    function fitPreviewEmbed() {
      if (!iframe) return;
      var width = stage.clientWidth;
      var height = stage.clientHeight;
      if (!width || !height) return;
      var frameWidth = Math.max(width, height * (16 / 9));
      var frameHeight = frameWidth * (9 / 16);
      iframe.style.width = Math.ceil(frameWidth) + 'px';
      iframe.style.height = Math.ceil(frameHeight) + 'px';
      iframe.style.top = '50%';
      iframe.style.left = '50%';
      iframe.style.transform = 'translate(-50%, -50%)';
    }

    function stopPreview() {
      if (preview) { preview.pause(); stage.classList.remove('is-playing'); }
      if (iframe) { iframe.removeAttribute('src'); stage.classList.remove('is-playing'); }
    }
    function syncPreview() {
      var allowed = autoplay && shouldPlay && !reduce.matches && !saveData && !document.hidden && !modalOpen;
      if (!allowed) { stopPreview(); return; }
      if (preview) {
        preview.muted = true;
        preview.loop = true;
        preview.playsInline = true;
        var promise = preview.play();
        if (promise && promise.then) promise.then(function () { if (!modalOpen && shouldPlay) stage.classList.add('is-playing'); }).catch(function () { stage.classList.remove('is-playing'); });
      } else if (previewEmbed) {
        if (!iframe) {
          iframe = document.createElement('iframe');
          iframe.className = 'sda-spotlight__preview-iframe';
          iframe.setAttribute('tabindex', '-1');
          iframe.setAttribute('aria-hidden', 'true');
          iframe.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
          iframe.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
          stage.insertBefore(iframe, stage.querySelector('.sda-spotlight__scrim'));
          fitPreviewEmbed();
          iframe.addEventListener('load', function () { if (!modalOpen && shouldPlay) stage.classList.add('is-playing'); });
        }
        if (iframe.getAttribute('src') !== previewEmbed) iframe.setAttribute('src', previewEmbed);
      }
    }

    function openModal() {
      if (!fullUrl) return;
      returnFocus = document.activeElement;
      modalOpen = true;
      stopPreview();
      player.textContent = '';
      var media;
      if (provider === 'upload') {
        media = document.createElement('video');
        media.controls = true;
        media.autoplay = true;
        media.playsInline = true;
        media.src = fullUrl;
      } else {
        media = document.createElement('iframe');
        media.title = 'Spotlight full video';
        media.src = fullUrl;
        media.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
        media.setAttribute('allowfullscreen','');
        media.setAttribute('referrerpolicy', 'strict-origin-when-cross-origin');
      }
      player.appendChild(media);
      modal.hidden = false;
      document.body.classList.add('sda-spotlight-modal-open');
      close.focus();
      if (provider === 'upload') {
        var p = media.play();
        if (p && p.catch) p.catch(function () { /* User can press the native play control. */ });
      }
    }
    function closeModal() {
      if (!modalOpen) return;
      modalOpen = false;
      player.querySelectorAll('video').forEach(function (v) { v.pause(); v.removeAttribute('src'); v.load(); });
      player.querySelectorAll('iframe').forEach(function (f) { f.removeAttribute('src'); });
      player.textContent = '';
      modal.hidden = true;
      document.body.classList.remove('sda-spotlight-modal-open');
      if (returnFocus && returnFocus.isConnected) returnFocus.focus();
      syncPreview();
    }
    play.addEventListener('click', openModal);
    modal.querySelectorAll('[data-spotlight-close]').forEach(function (btn) { btn.addEventListener('click', closeModal); });
    document.addEventListener('keydown', function (ev) {
      if (!modalOpen) return;
      if (ev.key === 'Escape') { ev.preventDefault(); closeModal(); }
      if (ev.key === 'Tab') {
        var items = [close].concat(Array.from(player.querySelectorAll('video,iframe')));
        var current = items.indexOf(document.activeElement);
        var nextIndex = ev.shiftKey ? (current <= 0 ? items.length - 1 : current - 1) : (current >= items.length - 1 ? 0 : current + 1);
        ev.preventDefault(); items[nextIndex].focus();
      }
    });
    window.addEventListener('resize', fitPreviewEmbed, { passive: true });
    if ('ResizeObserver' in window) {
      new ResizeObserver(fitPreviewEmbed).observe(stage);
    }
    document.addEventListener('visibilitychange', syncPreview);
    if (reduce.addEventListener) reduce.addEventListener('change', syncPreview);
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) { shouldPlay = entries[0].isIntersecting; syncPreview(); }, {threshold:.20}).observe(root);
    } else { shouldPlay = true; syncPreview(); }
  }

  function start() {
    document.querySelectorAll('[data-sda-marquee]').forEach(marqueeInit);
    document.querySelectorAll('[data-sda-preview]').forEach(previewInit);
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
  else start();
}());
