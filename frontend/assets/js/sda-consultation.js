/* Step-04 multi-step form: progressive enhancement; server remains authority. */
(function () {
    'use strict';
    function init(form) {
        var section = form.closest('.sda-consult');
        var panels = Array.from(form.querySelectorAll('[data-sda-consult-panel]'));
        var indicators = Array.from(form.querySelectorAll('[data-sda-consult-indicator]'));
        var progress = form.querySelector('[data-sda-consult-progress]');
        var current = Number(form.dataset.startStep) || 1;
        var submitting = false;
        var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function error(name, message) {
            var node = form.querySelector('[data-sda-consult-error="' + name + '"]');
            if (node) node.textContent = message || '';
            var field = form.querySelector('[name="' + name + '"]');
            if (field) {
                field.setAttribute('aria-invalid', message ? 'true' : 'false');
                var wrapper = field.closest('.sda-consult__field');
                if (wrapper) wrapper.classList.toggle('is-invalid', !!message);
            }
        }
        function showStep(step, focus) {
            current = Math.max(1, Math.min(3, step));
            panels.forEach(function (panel, i) {
                var active = i + 1 === current;
                panel.hidden = !active;
                panel.classList.remove('is-entering');
                if (active && !reduced) {
                    void panel.offsetWidth;
                    panel.classList.add('is-entering');
                }
            });
            indicators.forEach(function (item, i) {
                var num = i + 1;
                item.classList.toggle('is-current', num === current);
                item.classList.toggle('is-done', num < current);
                if (num === current) item.setAttribute('aria-current', 'step');
                else item.removeAttribute('aria-current');
            });
            if (progress) progress.style.width = ((current - 1) / 2 * 100) + '%';
            if (focus) {
                var input = panels[current - 1].querySelector('input:not([type="hidden"]), textarea');
                if (input) input.focus({ preventScroll: true });
            }
        }
        function check(step) {
            var invalid = null;
            var names = step === 1 ? ['name', 'company_name', 'designation'] :
                step === 2 ? ['phone', 'email'] : ['message', 'consent'];
            names.forEach(function (name) { error(name, ''); });
            if (step === 1) {
                var name = form.elements.namedItem('name');
                if (name.value.trim().length < 2) { error('name', 'Please enter your name (at least 2 characters).'); invalid = name; }
            }
            if (step === 2) {
                var phone = form.elements.namedItem('phone');
                var email = form.elements.namedItem('email');
                if (!phone.value.trim() && !email.value.trim()) {
                    error('phone', 'Add a phone number or email address.'); invalid = phone;
                } else {
                    if (phone.value.trim() && !/^\+?[0-9()\- ]{7,32}$/.test(phone.value.trim())) {
                        error('phone', 'Enter a valid phone number.'); invalid = invalid || phone;
                    }
                    if (email.value.trim() && !email.checkValidity()) {
                        error('email', 'Enter a valid email address.'); invalid = invalid || email;
                    }
                }
            }
            if (step === 3) {
                var message = form.elements.namedItem('message');
                var consent = form.elements.namedItem('consent');
                if (message.value.trim().length < 20) {
                    error('message', 'Please describe your project in at least 20 characters.'); invalid = message;
                }
                if (!consent.checked) {
                    error('consent', 'Please agree to be contacted about your request.'); invalid = invalid || consent;
                }
            }
            if (invalid && !invalid.closest("[data-sda-consult-panel]").hidden) invalid.focus();
            return !invalid;
        }
        form.querySelectorAll('[data-sda-consult-next]').forEach(function (button) {
            button.addEventListener('click', function () { if (check(current)) showStep(current + 1, true); });
        });
        form.querySelectorAll('[data-sda-consult-prev]').forEach(function (button) {
            button.addEventListener('click', function () { showStep(current - 1, true); });
        });
        form.addEventListener('submit', function (event) {
            if (submitting) { event.preventDefault(); return; }
            for (var step = 1; step <= 3; step++) {
                if (!check(step)) { event.preventDefault(); showStep(step, true); return; }
            }
            submitting = true;
            var submit = form.querySelector('[data-sda-consult-submit]');
            if (submit) { submit.disabled = true; submit.textContent = 'Sending…'; }
        });
        showStep(current, false);

        if (window.IntersectionObserver && !reduced) {
            section.classList.add('is-js-ready');
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {entry.target.classList.add('is-visible');observer.unobserve(entry.target);}
                });
            }, { threshold: .12 });
            section.querySelectorAll('[data-sda-consult-reveal]').forEach(function (el) { observer.observe(el); });
        }
        if (section.querySelector('.sda-consult__alert--error') || section.querySelector('.sda-consult__alert--success')) {
            requestAnimationFrame(function () { section.scrollIntoView({ behavior: 'instant', block: 'start' }); });
        }
    }
    function start() { document.querySelectorAll('[data-sda-consult-form]').forEach(init); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
