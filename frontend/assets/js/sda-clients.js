/* Step-07 v2.0 — four 180deg flip cards and an independent seamless marquee.
   Uses existing server-rendered permitted client records; no API or DB changes. */
(() => {
  'use strict';

  function initClients(section) {
    if (section.dataset.sdaClientsInitialized === '1') return;
    section.dataset.sdaClientsInitialized = '1';

    const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    const reduceMotion = () => mediaQuery.matches;
    const featured = section.querySelector('[data-sda-clients-featured]');
    const marquee = section.querySelector('[data-sda-clients-marquee]');
    const bank = section.querySelector('template[data-sda-clients-bank]');
    const cards = featured ? [...featured.querySelectorAll('[data-sda-client-flip]')] : [];
    const clientTemplates = bank ? [...bank.content.children] : [];
    const toggle = section.querySelector('[data-sda-clients-toggle]');
    const toggleIcon = section.querySelector('[data-sda-clients-toggle-icon]');
    const toggleLabel = section.querySelector('[data-sda-clients-toggle-label]');
    const canFlip = clientTemplates.length > 4 && cards.length === 4;
    const groupCount = Math.ceil(clientTemplates.length / 4);
    const intervalMs = 7400;
    const flipMs = 1050;
    let intervalId = null;
    let activeGroup = 0;
    let pointerPaused = false;
    let focusPaused = false;
    let userPaused = false;
    let inViewport = true;
    let flipping = false;
    const pendingTimeouts = new Set();

    const later = (callback, ms) => {
      const id = window.setTimeout(() => {
        pendingTimeouts.delete(id);
        callback();
      }, ms);
      pendingTimeouts.add(id);
    };

    const stopTimer = () => {
      if (intervalId !== null) window.clearInterval(intervalId);
      intervalId = null;
    };

    function isPaused() {
      return reduceMotion() || document.hidden || !inViewport ||
        pointerPaused || focusPaused || userPaused;
    }

    function flipToNextGroup() {
      if (!canFlip || flipping || isPaused()) return;
      flipping = true;
      const nextGroup = (activeGroup + 1) % groupCount;

      cards.forEach((card, index) => {
        const front = card.querySelector('[data-sda-client-front]');
        const back = card.querySelector('[data-sda-client-back]');
        const toBack = !card.classList.contains('is-flipped');
        const nextFace = toBack ? back : front;
        const previousFace = toBack ? front : back;
        const clientIndex = (nextGroup * 4 + index) % clientTemplates.length;
        const clonedTile = clientTemplates[clientIndex].cloneNode(true);

        // Populate the hidden side before turning it into view.
        nextFace.replaceChildren(clonedTile);
        nextFace.inert = true;
        nextFace.setAttribute('aria-hidden', 'true');
        previousFace.inert = true;
        previousFace.setAttribute('aria-hidden', 'true');

        later(() => card.classList.toggle('is-flipped', toBack), index * 110);
        later(() => {
          nextFace.inert = false;
          nextFace.removeAttribute('aria-hidden');
        }, flipMs + index * 110 + 50);
      });
      activeGroup = nextGroup;
      later(() => { flipping = false; }, flipMs + 4 * 110);
    }

    function syncState() {
      const paused = isPaused();
      section.classList.toggle('is-paused', paused);
      stopTimer();
      if (!paused && canFlip) intervalId = window.setInterval(flipToNextGroup, intervalMs);
    }

    // Ensure back-side links remain out of the keyboard tab order until flipped.
    cards.forEach(card => {
      const face = card.querySelector('[data-sda-client-back]');
      if (face) face.inert = true;
    });

    section.classList.add('sda-clients--js');
    if (clientTemplates.length <= 1) section.classList.add('sda-clients--single');
    if (reduceMotion()) section.classList.add('is-paused');

    if (toggle) {
      toggle.addEventListener('click', () => {
        userPaused = !userPaused;
        toggle.setAttribute('aria-pressed', String(userPaused));
        toggle.setAttribute('aria-label', userPaused ? 'Resume client logo animations' : 'Pause client logo animations');
        if (toggleIcon) toggleIcon.textContent = userPaused ? '▶' : 'Ⅱ';
        if (toggleLabel) toggleLabel.textContent = userPaused ? 'Resume motion' : 'Pause motion';
        syncState();
      });
    }

    const panel = section.querySelector('.sda-clients__panel');
    if (panel) {
      panel.addEventListener('pointerenter', () => { pointerPaused = true; syncState(); });
      panel.addEventListener('pointerleave', () => { pointerPaused = false; syncState(); });
      panel.addEventListener('focusin', () => { focusPaused = true; syncState(); });
      panel.addEventListener('focusout', () => {
        // Focusout fires before the next focus target is updated.
        later(() => {
          focusPaused = panel.contains(document.activeElement);
          syncState();
        }, 0);
      });
    }
    document.addEventListener('visibilitychange', syncState);
    if ('IntersectionObserver' in window) {
      const visibilityObserver = new IntersectionObserver(entries => {
        inViewport = !!entries[0]?.isIntersecting;
        syncState();
      }, {threshold:0.08});
      visibilityObserver.observe(section);

      if (!reduceMotion()) {
        const reveals = [...section.querySelectorAll('.sda-clients__header[data-sda-clients-reveal], .sda-clients__panel[data-sda-clients-reveal]')];
        const revealObserver = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              revealObserver.unobserve(entry.target);
            }
          });
        }, {threshold:0.08, rootMargin:'0px 0px 90px 0px'});
        section.classList.add('sda-clients--can-animate');
        reveals.forEach(el => revealObserver.observe(el));
      }
    }

    if (typeof mediaQuery.addEventListener === 'function') {
      mediaQuery.addEventListener('change', syncState);
    } else if (typeof mediaQuery.addListener === 'function') {
      mediaQuery.addListener(syncState);
    }
    if (marquee && clientTemplates.length > 1) {
      // Track is ready to run only after JS is initialized, avoiding double rows
      // scrolling when scripts are blocked.
      marquee.setAttribute('aria-label', 'Client logos; pause animation using the Pause motion control');
    }
    syncState();
  }

  function init() {
    document.querySelectorAll('[data-sda-clients]').forEach(initClients);
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, {once:true});
  } else init();
})();
