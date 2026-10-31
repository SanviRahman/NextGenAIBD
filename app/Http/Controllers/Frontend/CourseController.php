<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function frontend_Course_Index()
    {
        $courses = Course::where('status', 1)->orWhere('status', 2)->latest('id')->paginate(9);
        return view('frontend.course.index', compact(
            'courses',
        ));
    }

    public function frontend_Course_Single($slug)
    {
        $course = Course::where('slug', $slug)->first();
        if (!$course) {
            abort(404);
        }

        $courses = Course::where('status', 1)->inRandomOrder()->limit(3)->get();

        return view('frontend.course.single', compact(
            'course',
            'courses',
        ));
    }
}
