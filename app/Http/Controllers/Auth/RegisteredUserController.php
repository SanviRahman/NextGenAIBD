<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Services\MetaConversionsService;
use App\Support\MetaEventId;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $countries = Country::where('status', 1)->get();
        return view('auth.register', compact(
            'countries',
        ));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // 'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'country' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            // 'username' => $request->username,
            'name' => $request->name,
            'username' => Str::slug($request->name . now()) ,
            'email' => $request->email,
            'country' => $request->country,
            'number' => $request->number,
            'password' => Hash::make($request->password),
            'password_confirmation' => $request->password,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $metaPixelEvents = [];
        if ($request->cookie('meta_marketing_consent') === 'granted') {
            // Reuse this event ID if the saved registration is ever retried.
            $eventId = MetaEventId::forRecord('CompleteRegistration', $user->getKey());
            app(MetaConversionsService::class)->queueRegistration($request, [
                'email' => $user->email,
                'phone' => $user->number,
            ], $eventId);
            $metaPixelEvents[] = [
                'event_name' => 'CompleteRegistration',
                'event_id' => $eventId,
                'custom_data' => ['content_name' => 'Website Registration', 'status' => 'completed'],
            ];
        }

        return redirect(RouteServiceProvider::HOME)->with('meta_pixel_events', $metaPixelEvents);
    }
}
