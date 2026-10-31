<?php

return [
    // Set BOTH keys to enable and enforce the real reCAPTCHA v2 checkbox.
    // With neither configured the form still uses honeypot + server rate limiting.
    'recaptcha' => [
        'site_key' => env('SDA_RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('SDA_RECAPTCHA_SECRET_KEY', ''),
    ],
];
