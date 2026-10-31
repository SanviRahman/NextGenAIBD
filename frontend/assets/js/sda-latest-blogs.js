/* Progressive enhancement only: keep all articles visible without JS. */
(function () {
    'use strict';
    var root = document.querySelector('[data-sda-latest-blogs]');
    if (!root || !('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    var cards = root.querySelectorAll('[data-sda-blog-card]');
    if (!cards.length) return;
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px 30px 0px' });
    root.classList.add('js-ready');
    cards.forEach(function (card) { observer.observe(card); });
})();
