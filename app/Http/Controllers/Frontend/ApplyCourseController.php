<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\ApplyCourse;
use App\Models\Course;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplyCourseController extends Controller
{
    public function frontend_Apply_Index()
    {
        $course = Course::count();
        $courses = Course::where('status', 1)->get();
        $about = About::first();
        return view('frontend.apply.index', compact(
            'about',
            'course',
            'courses',
        ));
    }

    public function frontend_ApplyDB_Index(Request $request)
    {
        $request->validate([
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'name' => ['required', 'min:1', 'max:255'],
            // 'sort_description' => ['required', 'min:1', 'max:2000'],
            // 'description' => ['required', 'min:1', 'max:20000'],
            // 'video_url' => ['required', 'min:1', 'max:355'],
            // 'views' => ['required', 'min:1', 'max:255'],
        ]);

        if ($image = $request->file('image')) {
            $imgPath = 'media/courseapply/';
            $proImage = Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            # Move the original image to the destination folder
            $image->move($imgPath, $proImage);
            $img = Image::make($imgPath . $proImage);
            $maxFileSize = 100 * 1024;
            # Resize Image
            $quality = 90;
            while (strlen($img->encode('jpg', $quality)) > $maxFileSize && $quality >= 5) {
                $quality -= 5;
            }
            $databadgeImagePath = Str::slug($request->name) . '-' . 'appupnext' . '.jpg';
            $compressedImagePath = $imgPath . Str::slug($request->name) . '-' . 'appupnext' . '.jpg';
            $img->encode('jpg', $quality)->save($compressedImagePath);
            # Delete the original image after compression
            if (file_exists($imgPath . $proImage)) {
                unlink($imgPath . $proImage);
            }
        }

        $courseApply = ApplyCourse::create([
            'image' => $databadgeImagePath ?? 'default.png',
            'name' => $request->name ?? null,
            'slug' => Str::slug($request->name) ?? null,
            'number' => $request->number ?? null,
            'address' => $request->address ?? null,
            'email' => $request->email ?? null,
            'password' => $request->password ?? null,
            'educational_institute' => $request->educational_institute ?? null,
            'course' => $request->course ?? null,
            'course_fee' => $request->course_fee ?? null,
            'course_fee_paid' => $request->course_fee_paid ?? null,
            'course_fee_unpaid' => $request->course_fee_unpaid ?? null,
            'description' => $request->description ?? null,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->back()->with('success', 'Course Apply Adding Has Been Successfully');
    }
}
