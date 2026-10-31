<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function frontend_Contact_Index()
    {
        return view('frontend.contact.index');
    }
}
