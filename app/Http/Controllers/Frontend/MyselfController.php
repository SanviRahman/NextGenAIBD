<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Course;
use App\Models\CourseBundle;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class MyselfController extends Controller
{
    public function frontend_MySelf_Index()
    {
        $course = Course::count();
        $about = About::first();
        $personalInfo = PersonalInfo::first();
        return view('frontend.myself.index', compact(
            'course',
            'about',
            'personalInfo',
        ));
    }
}
