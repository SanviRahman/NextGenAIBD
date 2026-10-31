(function () {
    'use strict';
    const start = function () {
        const section = document.querySelector('[data-sda-benefits]');
        if (!section || section.dataset.benefitsReady === '1') return;
        section.dataset.benefitsReady = '1';
        if (!('IntersectionObserver' in window) || (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) return;
        const targets = Array.from(section.querySelectorAll('[data-benefits-reveal]'));
        const observer = new IntersectionObserver(function (entries, watcher) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    watcher.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -28px 0px' });
        section.classList.add('sda-benefits--enhanced');
        targets.forEach(function (target) { observer.observe(target); });
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start, { once: true });
    else start();
}());
