<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class SettingsCacheController extends Controller
{
    public function admin_Settings_Cache()
    {
        return view('backend.theme-option.cache');
    }

    public function admin_Settings_Cache_Clear()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('event:clear');
        Artisan::call('optimize:clear');
        Artisan::call('optimize');

        return redirect()->back()->with('success', 'Application cache has been cleared');
    }

    public function admin_Settings_Cache_Cache()
    {
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        Artisan::call('config:cache');
        Artisan::call('event:cache');

        return redirect()->back()->with('success', 'Application cache has been Cached');
    }

    public function admin_Settings_Cache_Optimize()
    {

        Artisan::call('optimize');

        return redirect()->back()->with('success', 'Application cache has been Optimize');
    }
}
