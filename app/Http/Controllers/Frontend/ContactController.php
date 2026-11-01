<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\WebsiteInfo;

class ContactController extends Controller
{
    public function frontend_Contact_Index()
    {
        $websiteInfo = WebsiteInfo::query()->first();

        return view('frontend.contact.index', compact('websiteInfo'));
    }
}
