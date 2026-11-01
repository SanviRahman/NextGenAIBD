<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationRequest;
use App\Models\ConsultationRequest;
use App\Services\MetaConversionsService;
use App\Support\MetaEventId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ConsultationController extends Controller
{
    public function store(StoreConsultationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $destination = route('home_Index') . '#consultation';

        // A hidden honeypot field. Do not save spam or expose the detection to bots.
        if (filled($data['website'] ?? null)) {
            return redirect()->to($destination)->with('consultation_success',
                'Thank you. If your request is eligible, our team will follow up.');
        }

        $siteKey = trim((string) config('sda_consultation.recaptcha.site_key'));
        $secretKey = trim((string) config('sda_consultation.recaptcha.secret_key'));
        if (($siteKey !== '') !== ($secretKey !== '')) {
            throw ValidationException::withMessages([
                'captcha' => 'Verification is temporarily unavailable. Please contact us directly.',
            ]);
        }
        if ($siteKey !== '' && $secretKey !== '') {
            if (empty($data['g-recaptcha-response'])) {
                throw ValidationException::withMessages(['captcha' => 'Complete the reCAPTCHA verification.']);
            }
            try {
                $result = Http::asForm()->timeout(7)->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $data['g-recaptcha-response'],
                    'remoteip' => $request->ip(),
                ]);
            } catch (\Throwable $e) {
                throw ValidationException::withMessages(['captcha' => 'Verification is unavailable. Please try again.']);
            }
            if (!$result->ok() || $result->json('success') !== true) {
                throw ValidationException::withMessages(['captcha' => 'Verification failed. Please try again.']);
            }
        }

        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $phone = trim((string) ($data['phone'] ?? ''));
        $message = trim($data['message']);

        // Same contact + same message within 3 minutes: redirect success, do not duplicate.
        $duplicate = ConsultationRequest::query()
            ->where('created_at', '>=', now()->subMinutes(3))
            ->where('message', $message)
            ->where(function ($query) use ($email, $phone) {
                if ($email !== '') $query->orWhere('email', $email);
                if ($phone !== '') $query->orWhere('phone', $phone);
            })->exists();

        $createdLead = null;
        if (!$duplicate) {
            $createdLead = ConsultationRequest::create([
                'public_id' => (string) Str::uuid(),
                'name' => trim($data['name']),
                'company_name' => $data['company_name'] ?? null,
                'designation' => $data['designation'] ?? null,
                'phone' => $phone !== '' ? $phone : null,
                'email' => $email !== '' ? $email : null,
                'message' => $message,
                'source_page' => 'homepage',
                'status' => 'new',
                'consented_at' => now(),
            ]);
        }

        $metaPixelEvents = [];
        if ($createdLead && $request->cookie('meta_marketing_consent') === 'granted') {
            // A single saved consultation is one Lead across Browser and CAPI.
            $eventId = MetaEventId::forRecord('Lead', $createdLead->public_id);
            app(MetaConversionsService::class)->queueLead($request, [
                'email' => $createdLead->email,
                'phone' => $createdLead->phone,
            ], $eventId);
            $metaPixelEvents[] = [
                'event_name' => 'Lead',
                'event_id' => $eventId,
                'custom_data' => [
                    'content_name' => 'Free Consultation',
                    'content_category' => 'Lead',
                ],
            ];
        }

        return redirect()->to($destination)
            ->with('consultation_success', 'Thank you! Your consultation request has been received. Our team will contact you.')
            ->with('meta_pixel_events', $metaPixelEvents);
    }
}
