<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function frontend_Service_Index()
    {
        $services = Service::where('status', 1)->latest('id')->paginate(9);;
        return view('frontend.services.index', compact(
            'services',
        ));
    }

    public function frontend_Service_Single($slug)
    {
        $service = Service::where('slug', $slug)->first();
        $services = Service::where('status', 1)->latest('id')->paginate(9);
        return view('frontend.services.single', compact(
            'service',
            'services',
        ));
    }
}
