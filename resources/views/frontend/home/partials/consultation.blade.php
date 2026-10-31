{{-- Step-04: Responsive, accessible 3-step consultation form. No lead is saved until final submit. --}}
@php
    $consultationSettings = $consultationSettings ?? new \App\Models\HomepageConsultationSetting();
    $consultationVisible = $consultationSettings->exists ? (bool)$consultationSettings->is_active : true;
    $consultationImage = \App\Support\WebsiteMedia::exists($consultationSettings->image_path)
        ? $consultationSettings->image_path
        : 'frontend/assets/images/consultation/testi-con.png';
    $recaptchaSiteKey = (string)config('sda_consultation.recaptcha.site_key');
    $recaptchaEnabled = $recaptchaSiteKey !== '' && (string)config('sda_consultation.recaptcha.secret_key') !== '';
    $errorKeys = $errors->keys();
    $errorStep = count(array_intersect($errorKeys, ['name','company_name','designation'])) ? 1 :
        (count(array_intersect($errorKeys, ['phone','email'])) ? 2 : 3);
@endphp
@if($consultationVisible)
<section class="sda-consult" id="consultation" aria-labelledby="sda-consult-title"
    style="--sda-consult-bg:{{ $consultationSettings->background_color ?: '#0B0F16' }};--sda-consult-accent:{{ $consultationSettings->accent_color ?: '#C74659' }}">
    <div class="sda-consult__container">
        <div class="sda-consult__visual" data-sda-consult-reveal>
            <div class="sda-consult__photo-frame">
                <img class="sda-consult__photo" loading="lazy" decoding="async"
                     src="{{ asset($consultationImage) }}"
                     alt="{{ $consultationSettings->image_alt ?: 'Sunnah Digital Agency team collaborating' }}">
                <div class="sda-consult__photo-shade" aria-hidden="true"></div>
            </div>
            <div class="sda-consult__photo-caption">
                <span class="sda-consult__photo-marker" aria-hidden="true"></span>
                <span>Let's build something meaningful together.</span>
            </div>
        </div>

        <div class="sda-consult__body" data-sda-consult-reveal>
            @if($consultationSettings->eyebrow)
                <p class="sda-consult__eyebrow">{{ $consultationSettings->eyebrow }}</p>
            @endif
            <h2 class="sda-consult__title" id="sda-consult-title">
                {{ $consultationSettings->heading ?: 'Book a Free Consultation' }}<span aria-hidden="true">.</span>
            </h2>
            @if($consultationSettings->description)
                <p class="sda-consult__intro">{{ $consultationSettings->description }}</p>
            @endif

            @if(session('consultation_success'))
                <div class="sda-consult__alert sda-consult__alert--success" role="status">{{ session('consultation_success') }}</div>
            @endif
            @if($errors->any())
                <div class="sda-consult__alert sda-consult__alert--error" role="alert">
                    Please correct the highlighted fields before submitting.
                    @if($errors->has('captcha')) <div>{{ $errors->first('captcha') }}</div> @endif
                </div>
            @endif

            <form id="sda-consult-form" class="sda-consult__form" method="POST"
                  action="{{ route('consultations.store') }}" data-sda-consult-form
                  data-start-step="{{ $errors->any() ? $errorStep : 1 }}" novalidate>
                @csrf
                <div class="sda-consult__steps" role="list" aria-label="Consultation form progress">
                    @foreach(['Personal Info','Contact Info','Your Project'] as $stepTitle)
                    <div class="sda-consult__step" role="listitem" data-sda-consult-indicator="{{ $loop->iteration }}">
                        <span class="sda-consult__step-count" aria-hidden="true">{{ $loop->iteration }}</span>
                        <span class="sda-consult__step-name">{{ $stepTitle }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="sda-consult__progress" aria-hidden="true"><span data-sda-consult-progress></span></div>

                {{-- Prevent bots: hidden honeypot. Real users never fill this field. --}}
                <div class="sda-consult__trap" aria-hidden="true"><label for="sda-consult-website">Website</label><input id="sda-consult-website" name="website" type="text" autocomplete="off" tabindex="-1"></div>

                <fieldset class="sda-consult__panel" data-sda-consult-panel="1" aria-label="Step 1: Personal information">
                    <legend class="visually-hidden">Personal information</legend>
                    <div class="sda-consult__field">
                        <label for="sda-c-name">Your name <span aria-hidden="true">*</span></label>
                        <input id="sda-c-name" type="text" name="name" maxlength="140" minlength="2" autocomplete="name"
                            placeholder="Your full name" value="{{ old('name') }}" required
                            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
                        <small data-sda-consult-error="name">{{ $errors->first('name') }}</small>
                    </div>
                    <div class="sda-consult__field">
                        <label for="sda-c-company">Company name <span class="sda-consult__optional">(optional)</span></label>
                        <input id="sda-c-company" type="text" name="company_name" maxlength="180" autocomplete="organization"
                            placeholder="Your business or organization" value="{{ old('company_name') }}">
                        <small data-sda-consult-error="company_name">{{ $errors->first('company_name') }}</small>
                    </div>
                    <div class="sda-consult__field">
                        <label for="sda-c-designation">Designation <span class="sda-consult__optional">(optional)</span></label>
                        <input id="sda-c-designation" type="text" name="designation" maxlength="120" autocomplete="organization-title"
                            placeholder="Your role or position" value="{{ old('designation') }}">
                        <small data-sda-consult-error="designation">{{ $errors->first('designation') }}</small>
                    </div>
                    <div class="sda-consult__actions"><button type="button" class="sda-consult__btn sda-consult__btn--next" data-sda-consult-next>Continue <span aria-hidden="true">→</span></button></div>
                </fieldset>

                <fieldset class="sda-consult__panel" data-sda-consult-panel="2" aria-label="Step 2: Contact information">
                    <legend class="visually-hidden">Contact information</legend>
                    <p class="sda-consult__help">Please provide at least one way to contact you.</p>
                    <div class="sda-consult__field">
                        <label for="sda-c-phone">Phone / WhatsApp</label>
                        <input id="sda-c-phone" type="tel" name="phone" maxlength="32" autocomplete="tel"
                            placeholder="e.g. +880 17XXXXXXXX" value="{{ old('phone') }}">
                        <small data-sda-consult-error="phone">{{ $errors->first('phone') }}</small>
                    </div>
                    <div class="sda-consult__field">
                        <label for="sda-c-email">Email address</label>
                        <input id="sda-c-email" type="email" name="email" maxlength="254" autocomplete="email"
                            placeholder="e.g. hello@yourcompany.com" value="{{ old('email') }}">
                        <small data-sda-consult-error="email">{{ $errors->first('email') }}</small>
                    </div>
                    <div class="sda-consult__actions sda-consult__actions--split">
                        <button type="button" class="sda-consult__btn sda-consult__btn--back" data-sda-consult-prev>← Back</button>
                        <button type="button" class="sda-consult__btn sda-consult__btn--next" data-sda-consult-next>Continue <span aria-hidden="true">→</span></button>
                    </div>
                </fieldset>

                <fieldset class="sda-consult__panel" data-sda-consult-panel="3" aria-label="Step 3: Your project">
                    <legend class="visually-hidden">Your project</legend>
                    <div class="sda-consult__field">
                        <label for="sda-c-message">Tell us about your project <span aria-hidden="true">*</span></label>
                        <textarea id="sda-c-message" name="message" required minlength="20" maxlength="4000" rows="5"
                            placeholder="What do you want to achieve? Describe your needs, timeline or goals.">{{ old('message') }}</textarea>
                        <small data-sda-consult-error="message">{{ $errors->first('message') }}</small>
                    </div>
                    <label class="sda-consult__consent" for="sda-c-consent">
                        <input id="sda-c-consent" type="checkbox" name="consent" value="1" required {{ old('consent') ? 'checked' : '' }}>
                        <span>I agree to be contacted by Sunnah Digital Agency about this consultation request.</span>
                    </label>
                    <small class="sda-consult__consent-error" data-sda-consult-error="consent">{{ $errors->first('consent') }}</small>
                    @if($recaptchaEnabled)
                        <div class="sda-consult__captcha"><div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div></div>
                    @endif
                    <div class="sda-consult__actions sda-consult__actions--split">
                        <button type="button" class="sda-consult__btn sda-consult__btn--back" data-sda-consult-prev>← Back</button>
                        <button type="submit" class="sda-consult__btn sda-consult__btn--submit" data-sda-consult-submit>Send Request <span aria-hidden="true">↗</span></button>
                    </div>
                </fieldset>
                <p class="sda-consult__bottom-note">No obligation. Your information is used only to respond to your inquiry.</p>
            </form>
        </div>
    </div>
</section>
@once
    @push('homepage_hero_after_main')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/sda-consultation.css') }}?v=1.0">
    @endpush
    @push('homepage_hero_scripts')
        <script src="{{ asset('frontend/assets/js/sda-consultation.js') }}?v=1.0" defer></script>
        @if($recaptchaEnabled)
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endif
    @endpush
@endonce
@endif
