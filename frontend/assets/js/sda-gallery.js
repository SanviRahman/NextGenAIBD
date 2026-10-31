/* Sunnah Digital Agency Step-09 Gallery — scoped, dependency-free */
(() => {
  'use strict';
  const root = document.querySelector('[data-sda-gallery]');
  if (!root) return;
  root.classList.add('js-enabled');

  const grid = root.querySelector('#sda-gallery-grid');
  const more = root.querySelector('[data-sda-gallery-toggle]');
  const modal = root.querySelector('[data-sda-gallery-lightbox]');
  const dialog = root.querySelector('.sda-gallery__dialog');
  const image = root.querySelector('[data-sda-gallery-image]');
  const caption = root.querySelector('[data-sda-gallery-caption]');
  const counter = root.querySelector('[data-sda-gallery-count]');
  const closeButton = root.querySelector('.sda-gallery__close');
  const previousButton = root.querySelector('[data-sda-gallery-prev]');
  const nextButton = root.querySelector('[data-sda-gallery-next]');
  let activeIndex = 0;
  let visibleTiles = [];
  let opener = null;
  let originalOverflow = '';

  if (more) {
    more.addEventListener('click', () => {
      const expanded = root.classList.toggle('is-expanded');
      more.setAttribute('aria-expanded', String(expanded));
      more.innerHTML = expanded ? 'SHOW LESS <span aria-hidden="true">↑</span>' : 'VIEW ALL <span aria-hidden="true">↗</span>';
    });
  }

  if (window.IntersectionObserver && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    root.classList.add('js-reveal');
    const observer = new IntersectionObserver(entries => {
      if (entries.some(entry => entry.isIntersecting)) {
        root.classList.add('is-in-view');
        observer.disconnect();
      }
    }, { threshold: 0.1 });
    observer.observe(root);
  }

  function updatePhoto() {
    const tile = visibleTiles[activeIndex];
    if (!tile) return;
    const photo = tile.querySelector('img');
    image.src = photo.currentSrc || photo.src;
    image.alt = photo.alt;
    caption.textContent = tile.querySelector('.sda-gallery__caption')?.textContent.trim() || photo.alt;
    counter.textContent = `${activeIndex + 1} / ${visibleTiles.length}`;
  }

  function openPhoto(button) {
    visibleTiles = Array.from(grid.querySelectorAll('[data-sda-gallery-tile]'))
      .filter(tile => tile.getClientRects().length > 0);
    const tile = button.closest('[data-sda-gallery-tile]');
    const index = visibleTiles.indexOf(tile);
    if (index < 0) return;
    activeIndex = index;
    opener = button;
    originalOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';
    modal.hidden = false;
    updatePhoto();
    dialog.focus();
    closeButton.focus();
  }

  function closePhoto() {
    if (modal.hidden) return;
    modal.hidden = true;
    image.removeAttribute('src');
    document.body.style.overflow = originalOverflow;
    if (opener?.isConnected) opener.focus();
    opener = null;
  }

  function step(direction) {
    if (!visibleTiles.length) return;
    activeIndex = (activeIndex + direction + visibleTiles.length) % visibleTiles.length;
    updatePhoto();
  }

  grid.addEventListener('click', event => {
    const trigger = event.target.closest('[data-sda-gallery-open]');
    if (trigger && grid.contains(trigger)) openPhoto(trigger);
  });
  modal.querySelectorAll('[data-sda-gallery-close]').forEach(btn => btn.addEventListener('click', closePhoto));
  previousButton.addEventListener('click', () => step(-1));
  nextButton.addEventListener('click', () => step(1));

  document.addEventListener('keydown', event => {
    if (modal.hidden) return;
    if (event.key === 'Escape') { event.preventDefault(); closePhoto(); }
    else if (event.key === 'ArrowLeft') { event.preventDefault(); step(-1); }
    else if (event.key === 'ArrowRight') { event.preventDefault(); step(1); }
    else if (event.key === 'Tab') {
      const controls = [closeButton, previousButton, nextButton];
      const index = controls.indexOf(document.activeElement);
      if (index === -1) { event.preventDefault(); controls[0].focus(); return; }
      if (event.shiftKey && index === 0) { event.preventDefault(); controls[2].focus(); }
      if (!event.shiftKey && index === 2) { event.preventDefault(); controls[0].focus(); }
    }
  });
})();
