<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminIpAddressController extends Controller
{
    public function backend_IP_Address_Store(Request $request)
    {
        $request->validate([
            'ip' => ['required', 'min:1', 'max:255'],
            'device' => ['required', 'min:1', 'max:255'],
            'browser' => ['required', 'min:1', 'max:255'],
            'country' => ['required', 'min:1', 'max:255'],
            'status' => ['required', 'min:1', 'max:255'],
        ]);
    }
}
