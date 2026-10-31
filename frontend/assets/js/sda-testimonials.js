/* Isolated Step-08 client video modal. No remote embeds load until visitor presses Play. */
(() => {
    'use strict';
    document.querySelectorAll('[data-sda-testimonials]').forEach(section => {
        const grid = section.querySelector('#sda-testimonials-grid');
        const more = section.querySelector('[data-sda-testimonials-more]');
        const modal = section.querySelector('[data-sda-testimonials-modal]');
        const dialog = modal?.querySelector('[role="dialog"]');
        const frameBox = modal?.querySelector('[data-sda-testimonials-frame]');
        const title = modal?.querySelector('[data-sda-testimonials-modal-title]');
        const closeButton = modal?.querySelector('.sda-testimonials__close');
        let previousFocus = null;
        let oldBodyOverflow = '';
        if (!grid || !modal || !dialog || !frameBox || !title || !closeButton) return;

        more?.addEventListener('click', () => {
            const expanded = grid.classList.toggle('is-expanded');
            more.setAttribute('aria-expanded', String(expanded));
            more.textContent = expanded ? 'Show Less ↑' : 'View All ↗';
        });

        const trustedEmbed = (raw) => {
            try {
                const url = new URL(raw);
                const youtube = url.protocol === 'https:' && url.hostname === 'www.youtube-nocookie.com'
                    && /^\/embed\/[A-Za-z0-9_-]{11}$/.test(url.pathname);
                const vimeo = url.protocol === 'https:' && url.hostname === 'player.vimeo.com'
                    && /^\/video\/[0-9]{5,15}$/.test(url.pathname);
                return youtube || vimeo ? url.toString() : null;
            } catch (_) { return null; }
        };
        const close = () => {
            if (modal.hidden) return;
            frameBox.replaceChildren(); // Stop playback and remove all third-party iframes.
            modal.hidden = true;
            document.body.style.overflow = oldBodyOverflow;
            previousFocus?.focus();
            previousFocus = null;
        };
        section.querySelectorAll('[data-sda-testimonials-play]').forEach(button => {
            button.addEventListener('click', () => {
                const embed = trustedEmbed(button.dataset.embed);
                if (!embed) return;
                previousFocus = document.activeElement;
                oldBodyOverflow = document.body.style.overflow;
                title.textContent = button.dataset.title || 'Client testimonial';
                const iframe = document.createElement('iframe');
                iframe.src = embed;
                iframe.title = title.textContent;
                iframe.loading = 'eager';
                iframe.referrerPolicy = 'strict-origin-when-cross-origin';
                iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
                iframe.setAttribute('allowfullscreen', '');
                frameBox.replaceChildren(iframe);
                modal.hidden = false;
                document.body.style.overflow = 'hidden';
                closeButton.focus();
            });
        });
        modal.querySelectorAll('[data-sda-testimonials-dismiss]').forEach(button => {
            button.addEventListener('click', close);
        });
        // Only one modal in this section; trap Tab navigation in the accessible dialog.
        document.addEventListener('keydown', event => {
            if (modal.hidden) return;
            if (event.key === 'Escape') { event.preventDefault(); close(); return; }
            if (event.key === 'Tab') {
                const focusables = [...dialog.querySelectorAll('button:not([disabled]),a[href],iframe')];
                const first = focusables[0];
                const last = focusables[focusables.length - 1];
                if (!first) { event.preventDefault(); dialog.focus(); return; }
                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault(); last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault(); first.focus();
                }
            }
        });
    });
})();
