<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public consultation inquiries (rate-limited route).
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:140'],
            'company_name' => ['nullable', 'string', 'max:180'],
            'designation' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:32', 'regex:/^\+?[0-9()\- ]{7,32}$/D'],
            'email' => ['nullable', 'required_without:phone', 'email:rfc', 'max:254'],
            'message' => ['required', 'string', 'min:20', 'max:4000'],
            'consent' => ['required', 'accepted'],
            'website' => ['nullable', 'string', 'max:200'], // Honeypot: not persisted.
            'g-recaptcha-response' => ['nullable', 'string', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => 'Add an email or phone number so we can contact you.',
            'phone.required_without' => 'Add a phone number or email so we can contact you.',
            'message.min' => 'Please describe your project in at least 20 characters.',
            'consent.accepted' => 'Please agree to be contacted about your request.',
        ];
    }
}
