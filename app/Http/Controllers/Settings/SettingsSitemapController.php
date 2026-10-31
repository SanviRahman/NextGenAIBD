<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class SettingsSitemapController extends Controller
{

    public function admin_Settings_Sitemap()
    {
        return view('backend.theme-option.sitemap');
    }


    public function admin_Settings_Sitemap_Generate()
    {

        $sitemapPath = 'sitemap.xml';
        # Check if the old sitemap file exists and delete it
        if (File::exists($sitemapPath)) {
            File::delete($sitemapPath);
        }

        SitemapGenerator::create(config('app.url'))
            ->writeToFile('sitemap.xml');

        # Optionally, you can return a response or redirect the user
        return redirect()->route('admin_Settings_Sitemap')->with('success', 'Sitemap generated successfully!');
    }
}
