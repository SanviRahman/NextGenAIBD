<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Course;
use App\Models\CourseBundle;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function frontend_About_Index()
    {
        $course = Course::count();
        $about = About::first();
        return view('frontend.about.index', compact(
            'about',
        ));
    }
}
