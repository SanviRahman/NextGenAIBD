(function () {
    'use strict';
    function init() {
        var section = document.querySelector('.sda-services');
        if (!section) return;
        var elements = section.querySelectorAll('[data-sda-service-reveal]');
        section.querySelectorAll('[data-sda-service-icon]').forEach(function (img) {
            function fallback() { img.classList.add('is-broken'); }
            img.addEventListener('error', fallback);
            if (img.complete && !img.naturalWidth) fallback();
        });
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;
        // Mark as enhanced only when observer will run. Non-JS rendering remains fully visible.
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: .12, rootMargin: '0px 0px 65px 0px' });
        section.classList.add('is-enhanced');
        elements.forEach(function (element) { observer.observe(element); });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
}());
