<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MetaPixelConsentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'consent' => ['required', Rule::in(['granted', 'denied'])],
        ]);

        $minutes = 60 * 24 * 180;
        $secure = $request->isSecure();
        $response = response()->json(['saved' => true]);

        $response->withCookie(cookie(
            'meta_marketing_consent',
            $data['consent'],
            $minutes,
            '/',
            null,
            $secure,
            true,
            false,
            'Lax'
        ));

        if ($data['consent'] === 'granted') {
            // First-party, consent-gated pseudonymous identifier. Never stored
            // in MySQL and never exposed to browser JavaScript (HttpOnly).
            $visitorId = (string) $request->cookie('meta_marketing_visitor', '');
            if (!Str::isUuid($visitorId)) {
                $visitorId = (string) Str::uuid();
            }

            $response->withCookie(cookie(
                'meta_marketing_visitor',
                $visitorId,
                $minutes,
                '/',
                null,
                $secure,
                true,
                false,
                'Lax'
            ));
        } else {
            // Removing marketing consent also removes its persistent ID.
            $response->withCookie(cookie()->forget('meta_marketing_visitor', '/', null));
        }

        return $response;
    }
}
