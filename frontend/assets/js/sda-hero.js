/* Sunnah Hero v6.5 — mobile typewriter/reverse restoration.
   Keep image-first mobile layout, all carousel controls, and desktop motion. */
(function () {
  'use strict';

  function initialize(root) {
    var slides = Array.from(root.querySelectorAll('[data-sda-hero-slide]'));
    if (!slides.length) return;

    var dots = Array.from(root.querySelectorAll('[data-sda-hero-dot]'));
    var prev = root.querySelector('[data-sda-hero-prev]');
    var next = root.querySelector('[data-sda-hero-next]');
    var status = root.querySelector('[data-sda-hero-status]');
    var motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    // Compact viewport keeps single-slide artwork stable; typing still runs.
    var compactViewport = window.matchMedia('(max-width: 991.98px)');
    var autoplayEnabled = root.getAttribute('data-autoplay') !== 'false';
    var singleReplayEnabled = root.getAttribute('data-single-replay') !== 'false';

    var current = 0;
    var nextTimer = null;
    var typeTimer = null;
    var transitionTimer = null;
    var bootTimer = null;
    var hovered = false;
    var focused = false;
    var visible = true;
    var transitionLocked = false;
    var typingNonce = 0;
    var queuedTarget = null;
    var cardTypingNonce = 0;
    var cardTimers = new Set();

    function readTransitionDuration() {
      var raw = window.getComputedStyle(root).getPropertyValue('--sda-enter-time').trim();
      if (!raw) return 1900;
      if (raw.slice(-2) === 'ms') return Math.max(300, parseFloat(raw) || 1900);
      if (raw.slice(-1) === 's') return Math.max(300, (parseFloat(raw) || 1.9) * 1000);
      return 1900;
    }

    var ENTER_DURATION = readTransitionDuration();
    var CAPTION_DELAY = 760;
    var AFTER_CAPTION_DELAY = 1600;

    var captions = slides.map(function (slide) {
      var node = slide.querySelector('.sda-hero__caption-text');
      var wrapper = slide.querySelector('.sda-hero__caption');
      return { node: node, wrapper: wrapper, text: node ? node.textContent.trim() : '' };
    });

    slides.forEach(function (slide) {
      slide.querySelectorAll('img').forEach(function (img) {
        function onError() { img.classList.add('sda-image-failed'); }
        img.addEventListener('error', onError);
        if (img.complete && !img.naturalWidth) onError();
      });
    });

    function letters(str) {
      if (typeof Intl !== 'undefined' && Intl.Segmenter) {
        return Array.from(new Intl.Segmenter(undefined, { granularity: 'grapheme' }).segment(str), function (x) {
          return x.segment;
        });
      }
      return Array.from(str);
    }

    var captionLetters = captions.map(function (c) { return letters(c.text); });

    // Cache original DB text once. Never change the admin-stored subtitle itself.
    // A visually-hidden static duplicate in Blade supplies stable accessible text.
    var cardDescriptions = slides.map(function (slide) {
      return Array.from(slide.querySelectorAll('[data-sda-card-description]')).map(function (node) {
        var text = node.textContent.trim();
        return { node: node, text: text, glyphs: letters(text) };
      });
    });

    function queueCardTick(fn, delay) {
      var id = setTimeout(function () {
        cardTimers.delete(id);
        fn();
      }, delay);
      cardTimers.add(id);
    }

    function stopCardTyping() {
      cardTypingNonce++;
      cardTimers.forEach(function (id) { clearTimeout(id); });
      cardTimers.clear();
      cardDescriptions.forEach(function (group) {
        group.forEach(function (entry) {
          entry.node.textContent = entry.text;
          entry.node.classList.remove('is-card-typing');
        });
      });
    }

    // Every card loops: TYPE -> pause at full text -> DELETE backward -> brief pause -> TYPE.
    // Grapheme segmentation preserves Bangla conjuncts and combined characters.
    function startCardTyping(slideIndex) {
      stopCardTyping();
      if (motion.matches || document.hidden || !visible) return;
      var nonce = cardTypingNonce;
      cardDescriptions[slideIndex].forEach(function (entry, cardIndex) {
        if (!entry.glyphs.length) return;
        var cursor = 0;
        var deleting = false;
        entry.node.textContent = '';
        entry.node.classList.add('is-card-typing');

        function tick() {
          if (nonce !== cardTypingNonce) return;
          cursor += deleting ? -1 : 1;
          cursor = Math.max(0, Math.min(entry.glyphs.length, cursor));
          entry.node.textContent = entry.glyphs.slice(0, cursor).join('');

          if (!deleting && cursor === entry.glyphs.length) {
            deleting = true;
            queueCardTick(tick, 1500 + cardIndex * 180);
          } else if (deleting && cursor === 0) {
            deleting = false;
            queueCardTick(tick, 550 + cardIndex * 200);
          } else {
            queueCardTick(tick, deleting ? 43 : 80);
          }
        }
        queueCardTick(tick, 980 + cardIndex * 330);
      });
    }

    function typingSpeed(i) {
      var mode = captions[i].wrapper && captions[i].wrapper.getAttribute('data-caption-animation');
      return mode === 'typewriter' ? 50 : 34;
    }

    function typingDuration(i) {
      var wrapper = captions[i].wrapper;
      if (!wrapper || wrapper.getAttribute('data-caption-animation') === 'none') return 0;
      return captionLetters[i].length * typingSpeed(i);
    }

    function cancelTyping() {
      typingNonce++;
      if (typeTimer !== null) clearTimeout(typeTimer);
      typeTimer = null;
      captions.forEach(function (c) {
        if (c.wrapper) c.wrapper.classList.remove('is-typing');
      });
    }

    function revealCaption(i) {
      cancelTyping();
      captions.forEach(function (c) {
        if (c.node) c.node.textContent = c.text;
      });

      var data = captions[i];
      if (!data.node || !data.wrapper || motion.matches || document.hidden || !visible) return;
      if (data.wrapper.getAttribute('data-caption-animation') === 'none') return;

      var glyphs = captionLetters[i];
      if (!glyphs.length) return;
      var position = 0;
      var nonce = typingNonce;
      var speed = typingSpeed(i);
      // The admin's Typewriter caption loops TYPE -> HOLD -> REVERSE on
      // compact screens. Desktop remains the original one-shot typing effect.
      var reverseLoop = compactViewport.matches &&
        data.wrapper.getAttribute('data-caption-animation') === 'typewriter';
      data.node.textContent = '';
      data.wrapper.classList.add('is-typing');

      function typeNext() {
        if (nonce !== typingNonce) return;
        if (position >= glyphs.length) {
          data.node.textContent = data.text;
          if (reverseLoop) {
            typeTimer = setTimeout(deleteNext, 1400);
          } else {
            data.wrapper.classList.remove('is-typing');
            typeTimer = null;
          }
          return;
        }
        data.node.textContent += glyphs[position++];
        typeTimer = setTimeout(typeNext, speed);
      }

      function deleteNext() {
        if (nonce !== typingNonce) return;
        if (position <= 0) {
          typeTimer = setTimeout(typeNext, 600);
          return;
        }
        position--;
        data.node.textContent = glyphs.slice(0, position).join('');
        typeTimer = setTimeout(deleteNext, 38);
      }

      typeTimer = setTimeout(typeNext, CAPTION_DELAY);
    }

    function paused() {
      if (!autoplayEnabled || motion.matches || document.hidden || hovered || focused || !visible) return true;
      // Replaying the only slide produces empty mobile frames. Keep it fully visible.
      if (slides.length === 1 && (compactViewport.matches || !singleReplayEnabled)) return true;
      return false;
    }

    function stopAutoplay() {
      if (nextTimer !== null) clearTimeout(nextTimer);
      nextTimer = null;
    }

    function dwellTime(i) {
      var stored = Number(slides[i].getAttribute('data-duration'));
      var requested = Number.isFinite(stored) ? Math.min(15000, Math.max(3000, stored)) : 7600;
      if (compactViewport.matches) {
        // Permit one complete typing + reverse pass before auto-advancing.
        // Otherwise a short (e.g. 7000ms) slide cuts off the reverse phase.
        var longestCard = 0;
        cardDescriptions[i].forEach(function (entry, index) {
          var cycle = 980 + index * 330 + entry.glyphs.length * (80 + 43) +
            1500 + index * 180 + 550 + index * 200;
          longestCard = Math.max(longestCard, cycle);
        });
        var captionCycle = captions[i].wrapper &&
          captions[i].wrapper.getAttribute('data-caption-animation') === 'typewriter'
          ? CAPTION_DELAY + captionLetters[i].length * (typingSpeed(i) + 38) + 1400 + 600
          : CAPTION_DELAY + typingDuration(i) + 500;
        return Math.max(requested, Math.min(15000, Math.ceil(Math.max(longestCard, captionCycle) + 250)));
      }
      return Math.max(requested, CAPTION_DELAY + typingDuration(i) + AFTER_CAPTION_DELAY);
    }

    function schedule() {
      stopAutoplay();
      if (paused() || transitionLocked) return;
      nextTimer = setTimeout(function () {
        if (slides.length === 1) replaySingleScene();
        else switchTo((current + 1) % slides.length, false);
      }, dwellTime(current));
    }

    function updateControls() {
      dots.forEach(function (dot, i) {
        dot.classList.toggle('is-active', i === current);
        dot.setAttribute('aria-current', i === current ? 'true' : 'false');
      });
    }

    function replaySingleScene() {
      if (slides.length !== 1 || paused() || transitionLocked) {
        schedule();
        return;
      }

      stopAutoplay();
      transitionLocked = true;
      var slide = slides[0];
      if (captions[0].node) captions[0].node.textContent = captions[0].text;
      cancelTyping();
      stopCardTyping();

      slide.classList.add('is-single-leaving');
      slide.classList.remove('is-active');

      transitionTimer = setTimeout(function () {
        slide.classList.add('is-single-reset');
        slide.classList.remove('is-single-leaving');
        void slide.offsetWidth;

        slide.classList.remove('is-single-reset');
        slide.classList.add('is-active');
        slide.setAttribute('aria-hidden', 'false');
        revealCaption(0);
        startCardTyping(0);

        transitionTimer = setTimeout(function () {
          transitionLocked = false;
          transitionTimer = null;
          schedule();
        }, ENTER_DURATION);
      }, ENTER_DURATION);
    }

    function switchTo(target, manual) {
      if (target < 0 || target >= slides.length || target === current) {
        schedule();
        return;
      }
      if (transitionLocked) {
        queuedTarget = target;
        return;
      }

      stopAutoplay();
      transitionLocked = true;

      var outgoing = slides[current];
      var incoming = slides[target];

      if (captions[current].node) captions[current].node.textContent = captions[current].text;
      stopCardTyping();

      // Inactive slides stay off-screen RIGHT. They travel RIGHT -> CENTER.
      incoming.classList.remove('is-active', 'is-leaving', 'is-single-leaving', 'is-single-reset');
      void incoming.offsetWidth;

      // Current scene travels CENTER -> LEFT.
      outgoing.classList.remove('is-active');
      outgoing.classList.add('is-leaving');
      outgoing.setAttribute('aria-hidden', 'true');

      current = target;
      incoming.classList.add('is-active');
      incoming.setAttribute('aria-hidden', 'false');

      updateControls();
      revealCaption(current);
      startCardTyping(current);
      if (status && manual) status.textContent = 'Hero slide ' + (current + 1) + ' of ' + slides.length;

      transitionTimer = setTimeout(function () {
        outgoing.classList.remove('is-leaving');
        transitionLocked = false;
        transitionTimer = null;

        if (queuedTarget !== null && queuedTarget !== current) {
          var queued = queuedTarget;
          queuedTarget = null;
          switchTo(queued, true);
          return;
        }

        queuedTarget = null;
        schedule();
      }, ENTER_DURATION);
    }

    function goRelative(delta) {
      if (slides.length === 1) {
        replaySingleScene();
        return;
      }
      switchTo((current + delta + slides.length) % slides.length, true);
    }

    if (next) next.addEventListener('click', function () { goRelative(1); });
    if (prev) prev.addEventListener('click', function () { goRelative(-1); });
    dots.forEach(function (dot, index) {
      dot.addEventListener('click', function () { switchTo(index, true); });
    });

    root.addEventListener('mouseenter', function () {
      hovered = true;
      stopAutoplay();
    });
    root.addEventListener('mouseleave', function () {
      hovered = false;
      schedule();
    });
    root.addEventListener('focusin', function () {
      focused = true;
      stopAutoplay();
    });
    root.addEventListener('focusout', function (ev) {
      if (!root.contains(ev.relatedTarget)) {
        focused = false;
        schedule();
      }
    });

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        cancelTyping();
        if (captions[current].node) captions[current].node.textContent = captions[current].text;
        stopCardTyping();
      } else if (!motion.matches && visible && !transitionLocked) {
        revealCaption(current);
        startCardTyping(current);
      }
      schedule();
    });

    // Switching desktop <-> tablet/phone safely restarts both type loops.
    function onCompactChange() {
      stopCardTyping();
      revealCaption(current);
      if (!transitionLocked) startCardTyping(current);
      schedule();
    }
    if (compactViewport.addEventListener) compactViewport.addEventListener('change', onCompactChange);
    else if (compactViewport.addListener) compactViewport.addListener(onCompactChange);

    if (motion.addEventListener) {
      motion.addEventListener('change', function () {
        if (motion.matches) {
          cancelTyping();
          stopCardTyping();
          if (captions[current].node) captions[current].node.textContent = captions[current].text;
        } else {
          revealCaption(current);
          startCardTyping(current);
        }
        schedule();
      });
    }

    if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(function (entries) {
        var wasVisible = visible;
        visible = !!entries[0].isIntersecting;
        if (!visible) {
          cancelTyping();
          if (captions[current].node) captions[current].node.textContent = captions[current].text;
          stopCardTyping();
        } else if (!wasVisible && !transitionLocked && !motion.matches && !document.hidden) {
          revealCaption(current);
          startCardTyping(current);
        }
        schedule();
      }, { threshold: .15 });
      observer.observe(root);
    }

    if (!motion.matches) {
      root.classList.add('is-booting');
      bootTimer = setTimeout(function () {
        root.classList.remove('is-booting');
      }, ENTER_DURATION);
    }

    updateControls();
    revealCaption(0);
    startCardTyping(0);
    schedule();
  }

  function start() {
    document.querySelectorAll('[data-sda-hero-carousel]').forEach(initialize);
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
  else start();
}());
