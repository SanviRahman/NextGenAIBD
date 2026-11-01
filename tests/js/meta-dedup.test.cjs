'use strict';
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.join(__dirname, '../../frontend/assets/js/meta-tracking.js'), 'utf8');

function createEnvironment(options = {}) {
    const calls = [];
    const network = [];
    const listeners = {};
    const dataLayer = options.dataLayer || [{ event: 'gtm.js', event_id: '00000000-0000-4000-8000-000000000002' }];
    const storage = options.storage || new Map();
    let count = 10;
    const fbq = (...args) => calls.push(args);
    const window = {
        NEXTGEN_META: {
            pixelIds: options.pixels || ['2100861233911035'],
            consent: options.consent || 'granted',
            eventUrl: '/tracking/meta/events',
            consentUrl: '/tracking/meta/consent',
            csrfToken: 'csrf-test',
            gtmJsEventId: '00000000-0000-4000-8000-000000000002',
            pageEvents: options.pageEvents || [{ event_name: 'ViewContent', custom_data: { content_category: 'Service' } }],
            serverConfirmedEvents: options.confirmed || [],
        },
        dataLayer,
        fbq,
        crypto: {randomUUID: () => '00000000-0000-4000-8000-' + String(++count).padStart(12, '0')},
        location: { href: 'http://127.0.0.1:8000/service', origin:'http://127.0.0.1:8000', pathname:'/service' },
        setTimeout: () => 1,
        clearTimeout: () => undefined,
        addEventListener: (event, cb) => { listeners[event] = cb; },
        requestAnimationFrame: () => undefined,
        innerHeight: 800,
        scrollY: 0,
        sessionStorage: {
            getItem: key => storage.get(key) || null,
            setItem: (key, value) => storage.set(key, value),
        }
    };
    const elements = {};
    if (options.consent === 'denied') {
        for (const key of ['nextgen-meta-consent', 'nextgen-meta-accept', 'nextgen-meta-reject', 'nextgen-meta-consent-manage']) {
            elements[key] = { hidden: true, listeners: {}, addEventListener(name, cb) {this.listeners[name] = cb;} };
        }
    }
    const document = {
        readyState:'complete',
        documentElement: {scrollHeight:1600},
        getElementsByTagName: () => [{parentNode: {insertBefore: () => undefined}}],
        createElement: () => ({}),
        getElementById: name => elements[name] || null,
        addEventListener: (event, cb) => { listeners[event] = cb; },
    };
    const context = {window, document, URL, Date, Math, Object, JSON, Array, String, fetch: async (url, options) => {
        network.push({ url, body: JSON.parse(options.body) });
        return {ok:true};
    }};
    vm.createContext(context);
    vm.runInContext(source, context);
    const pixel = name => calls.filter(x => (x[0] === 'trackSingle' || x[0] === 'trackSingleCustom') && x[2] === name);
    const relayEvents = () => network.filter(x => x.url === '/tracking/meta/events').flatMap(x => x.body.events);
    return { calls, network, window, document, listeners, elements, storage, dataLayer, pixel, relayEvents };
}

(async () => {
    const confirmedId = 'ab12cd34-0000-5000-8000-123456789abc';
    const e = createEnvironment({confirmed: [
        {event_name:'Lead', event_id:confirmedId, custom_data:{content_name:'Free Consultation'}},
        {event_name:'Lead', event_id:confirmedId, custom_data:{content_name:'Free Consultation'}},
    ]});
    const page = e.pixel('PageView');
    assert.equal(page.length, 1, 'One PageView on initial load');
    assert.equal(e.relayEvents().find(x => x.event_name === 'PageView').event_id, page[0][4].eventID, 'Pixel and CAPI PageView share the same event ID');
    assert.equal(e.dataLayer.find(x => x.event === 'PageView').event_id, page[0][4].eventID, 'DataLayer PageView has the same ID');
    assert.equal(e.pixel('ViewContent')[0][4].eventID, e.relayEvents().find(x => x.event_name === 'ViewContent').event_id, 'Page-specific event shares ID');
    assert.equal(e.pixel('Lead').length, 1, 'Confirmed Lead only emits one browser conversion');
    assert.equal(e.pixel('Lead')[0][4].eventID, confirmedId, 'Confirmed Lead uses PHP-generated event ID');
    assert.equal(e.relayEvents().filter(x => x.event_name === 'Lead').length, 0, 'Confirmed Lead is not sent through the generic browser relay');
    assert.equal(e.window.nextGenTrack('Purchase', {value: 1}), null, 'Manual Purchase without a saved order is blocked');
    assert.equal(e.window.nextGenTrack('lead', {}), null, 'Case-variant manual Lead is blocked');
    assert.equal(e.pixel('Purchase').length, 0, 'Unconfirmed Purchase never reaches the Pixel');

    const storage = e.storage;
    const again = createEnvironment({confirmed:[{event_name:'Lead',event_id:confirmedId}],storage});
    assert.equal(again.pixel('Lead').length, 0, 'Back/reload in same tab cannot replay confirmed Lead');
    const other = createEnvironment({confirmed:[{event_name:'Lead',event_id:'bb12cd34-0000-5000-8000-123456789abc'}],storage});
    assert.equal(other.pixel('Lead').length, 1, 'Genuinely new saved Lead is still tracked');

    const multi = createEnvironment({pixels:['2100861233911035','999999999999999']});
    assert.equal(multi.pixel('PageView').length, 2, 'Both configured Pixels receive the event');
    assert.equal(multi.pixel('PageView')[0][4].eventID, multi.pixel('PageView')[1][4].eventID, 'Both Pixels receive same event ID for same action');

    const opt = createEnvironment({consent:'denied'});
    assert.equal(opt.pixel('PageView').length, 0, 'Nothing sent to Meta before consent');
    assert.equal(opt.relayEvents().length, 0, 'No server tracking before consent');
    const consentId = opt.dataLayer.find(x => x.event === 'PageView').event_id;
    await opt.elements['nextgen-meta-accept'].listeners.click();
    // The listener starts a Promise chain; wait for asynchronous consent save.
    await new Promise(resolve => setImmediate(resolve));
    opt.listeners.pagehide();
    assert.equal(opt.pixel('PageView').length, 1, 'One PageView once consent is accepted');
    assert.equal(opt.pixel('PageView')[0][4].eventID, consentId, 'Consent grant reuses original DataLayer event id');
    assert.equal(opt.relayEvents().find(x => x.event_name === 'PageView').event_id, consentId, 'Post-consent relay also reuses the same ID');

    const customId = e.window.nextGenTrack('CTA_Click', {content_name:'Get Started'});
    e.listeners.pagehide();
    assert.equal(typeof customId, 'string', 'Custom interaction returns generated event ID');
    assert.equal(e.pixel('CTA_Click').length, 1, 'Custom events remain functional');
    assert.equal(e.relayEvents().find(x => x.event_name === 'CTA_Click').event_id, customId, 'Custom Browser + Server IDs match');

    console.log('PASS: 22 Meta Pixel / CAPI event deduplication assertions');
})().catch(error => {console.error(error);process.exitCode = 1;});
