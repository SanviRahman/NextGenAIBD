(function (window, document) {
    'use strict';

    var config = window.NEXTGEN_META || {};
    var dataLayer = window.dataLayer = window.dataLayer || [];
    var pixelIds = Array.isArray(config.pixelIds) ? config.pixelIds.filter(Boolean) : [];
    var consent = config.consent === 'granted';
    var pixelInitialized = false;
    var serverQueue = [];
    var flushTimer = null;
    var trackedForms = typeof WeakSet !== 'undefined' ? new WeakSet() : null;
    var scrollMilestones = [25, 50, 75, 90];
    var sentScrollMilestones = {};
    var initialEventsPrepared = false;
    var initialMetaEventsSent = false;
    var pageViewEventId = makeId();
    var pageEventsWithIds = (Array.isArray(config.pageEvents) ? config.pageEvents : []).map(function (event) {
        return Object.assign({}, event, { event_id: makeId() });
    });
    var serverConfirmedEvents = Array.isArray(config.serverConfirmedEvents) ? config.serverConfirmedEvents : [];
    // Only Laravel-confirmed, successfully saved actions may emit these conversions.
    var serverOnlyConversions = {
        lead: true,
        completeregistration: true,
        submitapplication: true,
        purchase: true
    };
    var sentEventKeys = Object.create(null);
    var sentEventOrder = [];
    var gtmDomEventId = null;
    var gtmLoadEventId = null;

    var standardEvents = {
        PageView: true,
        ViewContent: true,
        Search: true,
        Contact: true,
        Lead: true,
        CompleteRegistration: true,
        Schedule: true,
        SubmitApplication: true
    };

    function makeId() {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            return window.crypto.randomUUID();
        }
        var hex = function (length) { return Array.from({ length: length }, function () { return Math.floor(Math.random() * 16).toString(16); }).join(''); };
        return hex(8) + '-' + hex(4) + '-4' + hex(3) + '-' + (8 + Math.floor(Math.random() * 4)).toString(16) + hex(3) + '-' + hex(12);
    }

    function safeSourceUrl() {
        try {
            var url = new URL(window.location.href);
            ['email', 'phone', 'password', 'token', 'code', 'name'].forEach(function (key) {
                url.searchParams.delete(key);
            });
            url.hash = '';
            return url.toString();
        } catch (error) {
            return window.location.origin + window.location.pathname;
        }
    }

    function ensurePixel() {
        if (!consent || !pixelIds.length || pixelInitialized) return;

        (function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = true;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = true;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s);
        })(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        pixelIds.forEach(function (pixelId) {
            window.fbq('init', pixelId);
        });
        pixelInitialized = true;
    }

    function enqueueServerEvent(eventName, eventId, customData) {
        if (!consent || !pixelIds.length || !config.eventUrl) return;

        serverQueue.push({
            event_name: eventName,
            event_id: eventId,
            event_time: Math.floor(Date.now() / 1000),
            event_source_url: safeSourceUrl(),
            custom_data: customData || {}
        });

        if (flushTimer) window.clearTimeout(flushTimer);
        flushTimer = window.setTimeout(flushServerQueue, 300);
    }

    function flushServerQueue() {
        if (!serverQueue.length || !consent || !config.eventUrl) return;
        var batch = serverQueue.splice(0, 20);
        if (flushTimer) {
            window.clearTimeout(flushTimer);
            flushTimer = null;
        }

        fetch(config.eventUrl, {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: true,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken || ''
            },
            body: JSON.stringify({ events: batch })
        }).catch(function () {
            // Tracking must never interrupt website navigation or form actions.
        });

        if (serverQueue.length) {
            flushTimer = window.setTimeout(flushServerQueue, 50);
        }
    }

    // Ignore accidental replays of the same browser event ID; distinct
    // interactions always use distinct UUIDs and continue tracking normally.
    function rememberEvent(eventName, eventId) {
        var key = eventName + ':' + eventId;
        if (sentEventKeys[key]) return false;
        sentEventKeys[key] = true;
        sentEventOrder.push(key);
        if (sentEventOrder.length > 256) {
            delete sentEventKeys[sentEventOrder.shift()];
        }
        return true;
    }

    // Back/reload and duplicate scripts must not replay a successful conversion.
    // Session storage is per tab, has a small size cap and contains no PII.
    function rememberConfirmedConversion(eventName, eventId) {
        var key = eventName + ':' + eventId;
        var now = Date.now();
        try {
            var storageKey = 'nextgen_meta_confirmed_v1';
            var stored = JSON.parse(window.sessionStorage.getItem(storageKey) || '{}');
            if (!stored || typeof stored !== 'object' || Array.isArray(stored)) stored = {};
            Object.keys(stored).forEach(function (name) {
                if (typeof stored[name] !== 'number' || now - stored[name] > 48 * 60 * 60 * 1000) {
                    delete stored[name];
                }
            });
            if (stored[key]) return false;
            stored[key] = now;
            var keys = Object.keys(stored).sort(function (a, b) { return stored[b] - stored[a]; });
            keys.slice(100).forEach(function (name) { delete stored[name]; });
            window.sessionStorage.setItem(storageKey, JSON.stringify(stored));
        } catch (error) {
            // Disabled storage should not break legitimate tracking.
        }
        return true;
    }

    function pushDataLayerEvent(eventName, eventId, customData) {
        var item = { event: eventName, event_id: eventId };
        Object.keys(customData || {}).forEach(function (key) {
            item[key] = customData[key];
        });
        dataLayer.push(item);
    }

    function track(eventName, customData, options) {
        customData = customData || {};
        options = options || {};

        var eventId = options.eventId || makeId();
        var metaEventName = options.metaEventName || eventName;
        var pushName = options.dataLayerName || eventName;

        // 'Lead'/'Purchase'/registration must represent a confirmed database
        // action, not a click, form attempt or arbitrary browser relay.
        if (serverOnlyConversions[String(metaEventName).toLowerCase()] && options.serverConfirmed !== true) {
            return null;
        }

        if (options.pushDataLayer !== false) {
            pushDataLayerEvent(pushName, eventId, customData);
        }

        if (!consent || !pixelIds.length) return eventId;
        // DataLayer-only preparation must NOT consume an event ID before its
        // actual browser/CAPI dispatch happens a few lines later.
        if (options.sendPixel === false && options.sendServer === false) return eventId;

        if (options.serverConfirmed === true && !rememberConfirmedConversion(metaEventName, eventId)) {
            return eventId;
        }
        if (!rememberEvent(metaEventName, eventId)) return eventId;

        ensurePixel();
        if (options.sendPixel !== false && window.fbq) {
            pixelIds.forEach(function (pixelId) {
                if (standardEvents[metaEventName]) {
                    window.fbq('trackSingle', pixelId, metaEventName, customData, { eventID: eventId });
                } else {
                    window.fbq('trackSingleCustom', pixelId, metaEventName, customData, { eventID: eventId });
                }
            });
        }

        if (options.sendServer !== false) {
            enqueueServerEvent(metaEventName, eventId, customData);
        }
        return eventId;
    }

    // Expose a safe helper so any application component can track a named event.
    window.nextGenTrack = function (eventName, customData) {
        if (!/^[A-Za-z][A-Za-z0-9_.]{0,49}$/.test(String(eventName || ''))) return null;
        var metaName = String(eventName).replace(/\./g, '_');
        return track(String(eventName), customData || {}, { metaEventName: metaName });
    };

    // GTM-style lifecycle events are visible in DataLayer Checker. The Meta event
    // name uses underscores, while the dataLayer keeps the exact GTM event name.
    function lifecycle(dataLayerName, metaName, eventId, extra, pushDataLayer) {
        var customData = extra || { lifecycle: dataLayerName };
        var id = eventId || makeId();
        track(dataLayerName, customData, {
            eventId: id,
            dataLayerName: dataLayerName,
            metaEventName: metaName,
            pushDataLayer: pushDataLayer !== false
        });
        return id;
    }

    function startMetaAfterConsent() {
        if (!consent || !pixelIds.length || initialMetaEventsSent) return;
        ensurePixel();
        // Consent was granted after the page/lifecycle events occurred. Start
        // Meta measurement now instead of retroactively sending pre-consent events.
        // Reuse the IDs already placed in dataLayer before consent, rather
        // than generating unrelated IDs for the same page action.
        track('PageView', { page_path: window.location.pathname }, { eventId: pageViewEventId, pushDataLayer: false });
        pageEventsWithIds.forEach(function (event) {
            if (event && event.event_name) {
                track(event.event_name, event.custom_data || {}, { eventId: event.event_id, pushDataLayer: false });
            }
        });
        serverConfirmedEvents.forEach(function (event) {
            if (!event || !event.event_name || !event.event_id) return;
            track(event.event_name, event.custom_data || {}, {
                eventId: event.event_id,
                serverConfirmed: true,
                sendServer: false,
                pushDataLayer: false
            });
        });
        initialMetaEventsSent = true;
    }

    function fireInitialEvents() {
        if (!initialEventsPrepared) {
            // DataLayer records are created regardless of consent. Marketing
            // Pixel/CAPI delivery remains blocked until the visitor opts in.
            track('PageView', { page_path: window.location.pathname }, { eventId: pageViewEventId, sendPixel: false, sendServer: false });
            pageEventsWithIds.forEach(function (event) {
                if (event && event.event_name) {
                    track(event.event_name, event.custom_data || {}, { eventId: event.event_id, sendPixel: false, sendServer: false });
                }
            });
            serverConfirmedEvents.forEach(function (event) {
                if (!event || !event.event_name || !event.event_id) return;
                track(event.event_name, event.custom_data || {}, {
                    eventId: event.event_id,
                    serverConfirmed: true,
                    sendPixel: false,
                    sendServer: false
                });
            });
            initialEventsPrepared = true;
        }

        if (!consent || !pixelIds.length || initialMetaEventsSent) return;
        ensurePixel();
        track('PageView', { page_path: window.location.pathname }, { eventId: pageViewEventId, pushDataLayer: false });
        pageEventsWithIds.forEach(function (event) {
            if (event && event.event_name) {
                track(event.event_name, event.custom_data || {}, { eventId: event.event_id, pushDataLayer: false });
            }
        });
        serverConfirmedEvents.forEach(function (event) {
            if (!event || !event.event_name || !event.event_id) return;
            // The matching server Lead was already sent by Laravel. Send only
            // the browser Pixel copy with the same event ID for deduplication.
            track(event.event_name, event.custom_data || {}, {
                eventId: event.event_id,
                serverConfirmed: true,
                sendServer: false,
                pushDataLayer: false
            });
        });
        initialMetaEventsSent = true;
    }

    // gtm.js is pushed in the document head. Save IDs for delayed consent.
    if (pixelIds.length && consent) {
        lifecycle('gtm.js', 'GTM_js', config.gtmJsEventId, { lifecycle: 'gtm.js' }, false);
    }

    function onDomReady() {
        gtmDomEventId = lifecycle('gtm.dom', 'GTM_dom', null, { lifecycle: 'gtm.dom' });
        fireInitialEvents();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', onDomReady, { once: true });
    } else {
        onDomReady();
    }

    function onWindowLoad() {
        gtmLoadEventId = lifecycle('gtm.load', 'GTM_load', null, { lifecycle: 'gtm.load' });
        flushServerQueue();
    }
    if (document.readyState === 'complete') {
        onWindowLoad();
    } else {
        window.addEventListener('load', onWindowLoad, { once: true });
    }

    // All clicks are visible in dataLayer; high-intent clicks are also sent to Meta.
    document.addEventListener('click', function (event) {
        var element = event.target && event.target.closest
            ? event.target.closest('a,button,[role="button"],[data-meta-event]')
            : null;
        if (!element) return;

        var text = (element.innerText || element.getAttribute('aria-label') || element.getAttribute('title') || '').trim().replace(/\s+/g, ' ').slice(0, 120);
        var custom = {
            element_text: text,
            element_id: (element.id || '').slice(0, 100),
            element_type: element.tagName.toLowerCase()
        };
        var explicitEvent = element.getAttribute('data-meta-event');
        var href = element.getAttribute('href') || '';

        if (explicitEvent && /^[A-Za-z][A-Za-z0-9_.]{0,49}$/.test(explicitEvent)) {
            track(explicitEvent, custom, { metaEventName: explicitEvent.replace(/\./g, '_') });
            return;
        }

        if (element.tagName.toLowerCase() === 'a' && href) {
            try {
                var linkUrl = new URL(href, window.location.href);
                ['email', 'phone', 'password', 'token', 'code', 'name'].forEach(function (key) {
                    linkUrl.searchParams.delete(key);
                });
                custom.link_url = linkUrl.toString().slice(0, 200);
                if (linkUrl.hostname && linkUrl.hostname !== window.location.hostname) {
                    track('OutboundClick', custom);
                    return;
                }
            } catch (error) {
                custom.link_url = href.slice(0, 200);
            }
        }

        dataLayer.push(Object.assign({ event: 'click', event_id: makeId() }, custom));
        var isCta = element.matches('.btn,.cmn--btn,.cmn--btn-2,.cmn--btn-3,.link-btn,[data-cta],a[href^="mailto:"],a[href^="tel:"],a[href*="wa.me"],a[href*="whatsapp"]');
        if (isCta) track('CTA_Click', custom);
    }, true);

    // Track form engagement once per form and form submission attempts.
    document.addEventListener('focusin', function (event) {
        var form = event.target && event.target.closest ? event.target.closest('form') : null;
        if (!form || form.closest('#db-wrapper') || (trackedForms && trackedForms.has(form))) return;
        if (trackedForms) trackedForms.add(form);
        track('FormStart', {
            form_id: (form.id || form.getAttribute('name') || 'form').slice(0, 100)
        });
    });

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || form.tagName.toLowerCase() !== 'form' || form.closest('#db-wrapper')) return;
        track('FormSubmit', {
            form_id: (form.id || form.getAttribute('name') || 'form').slice(0, 100)
        });
    }, true);

    // Scroll milestones: 25%, 50%, 75%, 90% (one event per milestone per page).
    var scrollPending = false;
    window.addEventListener('scroll', function () {
        if (scrollPending) return;
        scrollPending = true;
        window.requestAnimationFrame(function () {
            scrollPending = false;
            var doc = document.documentElement;
            var maxScroll = Math.max(1, doc.scrollHeight - window.innerHeight);
            var percent = Math.min(100, Math.round((window.scrollY / maxScroll) * 100));
            scrollMilestones.forEach(function (milestone) {
                if (percent >= milestone && !sentScrollMilestones[milestone]) {
                    sentScrollMilestones[milestone] = true;
                    track('ScrollDepth', { scroll_percent: milestone });
                }
            });
        });
    }, { passive: true });

    // Consent controls. No Pixel script or CAPI request is made before acceptance.
    var banner = document.getElementById('nextgen-meta-consent');
    var manageButton = document.getElementById('nextgen-meta-consent-manage');
    var acceptButton = document.getElementById('nextgen-meta-accept');
    var rejectButton = document.getElementById('nextgen-meta-reject');

    function showConsentBanner() {
        if (banner) banner.hidden = false;
    }

    function hideConsentBanner() {
        if (banner) banner.hidden = true;
    }

    function saveConsent(choice) {
        return fetch(config.consentUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken || ''
            },
            body: JSON.stringify({ consent: choice })
        }).then(function (response) {
            if (!response.ok) throw new Error('Could not save consent preference');
            consent = choice === 'granted';
            config.consent = choice;
            hideConsentBanner();
            if (consent) {
                startMetaAfterConsent();
            }
        }).catch(function () {
            // Keep the banner visible if the preference could not be saved.
            showConsentBanner();
        });
    }

    if (acceptButton) acceptButton.addEventListener('click', function () { saveConsent('granted'); });
    if (rejectButton) rejectButton.addEventListener('click', function () { saveConsent('denied'); });
    if (manageButton) manageButton.addEventListener('click', showConsentBanner);

    if (consent) {
        hideConsentBanner();
    }

    window.addEventListener('pagehide', flushServerQueue);
})(window, document);
