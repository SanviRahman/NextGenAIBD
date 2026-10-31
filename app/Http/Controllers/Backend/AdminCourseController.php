<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCourseController extends Controller
{
    public function backend_Courses_Index()
    {
        $courses = Course::latest('id')->paginate(10);
        $course = Course::count();
        $active_courses = Course::where('status', 1)->count();
        $pending_courses = Course::where('status', 2)->count();
        $draft_courses = Course::where('status', 3)->count();

        return view('backend.courses.index', compact(
            'courses',
            'course',
            'active_courses',
            'pending_courses',
            'draft_courses',
        ));
    }

    public function backend_Courses_Active()
    {
        $courses = Course::where('status', 1)->latest('id')->paginate(10);
        $course = Course::count();
        $active_courses = Course::where('status', 1)->count();
        $pending_courses = Course::where('status', 2)->count();
        $draft_courses = Course::where('status', 3)->count();

        return view('backend.courses.index', compact(
            'courses',
            'course',
            'active_courses',
            'pending_courses',
            'draft_courses',
        ));
    }

    public function backend_Courses_Pending()
    {
        $courses = Course::where('status', 2)->latest('id')->paginate(10);
        $course = Course::count();
        $active_courses = Course::where('status', 1)->count();
        $pending_courses = Course::where('status', 2)->count();
        $draft_courses = Course::where('status', 3)->count();

        return view('backend.courses.index', compact(
            'courses',
            'course',
            'active_courses',
            'pending_courses',
            'draft_courses',
        ));
    }

    public function backend_Courses_Drafts()
    {
        $courses = Course::where('status', 3)->latest('id')->paginate(10);
        $course = Course::count();
        $active_courses = Course::where('status', 1)->count();
        $pending_courses = Course::where('status', 2)->count();
        $draft_courses = Course::where('status', 3)->count();

        return view('backend.courses.index', compact(
            'courses',
            'course',
            'active_courses',
            'pending_courses',
            'draft_courses',
        ));
    }

    public function backend_Courses_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $courses = Course::where('id', 'like', '%' . $query . '%')
            ->orWhere('title', 'like', '%' . $query . '%')
            ->paginate(10);

        $course = Course::count();
        $active_courses = Course::where('status', 1)->count();
        $pending_courses = Course::where('status', 2)->count();
        $draft_courses = Course::where('status', 3)->count();

        return view('backend.courses.index', compact(
            'courses',
            'course',
            'active_courses',
            'pending_courses',
            'draft_courses',
        ));
    }

    public function backend_Courses_Create()
    {
        return view('backend.courses.create');
    }

    public function backend_Courses_Store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'title' => ['required', 'min:1', 'max:255'],
            'description' => ['required', 'min:1'],
            'categories' => ['required'],
            // 'video_url' => ['required'],
            'price' => ['required', 'min:1', 'max:255'],
            'discount' => ['required', 'min:1', 'max:255'],
            'duration_class' => ['required', 'min:1', 'max:255'],
            'weekly_class' => ['required', 'min:1', 'max:255'],
            'skill_requirements' => ['required', 'min:1', 'max:255'],
            'total_student' => ['required', 'min:1', 'max:255'],
            'language' => ['required', 'min:1', 'max:255'],
            'assignment' => ['required', 'min:1', 'max:255'],
            'status' => ['required', 'min:1', 'max:255'],
        ]);

        if ($image = $request->file('image')) {
            $imgPath = 'media/course/';
            $proImage = Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            # Move the original image to the destination folder
            $image->move($imgPath, $proImage);
            $img = Image::make($imgPath . $proImage);
            $maxFileSize = 100 * 1024;
            # Resize Image
            $quality = 90;
            while (strlen($img->encode('jpg', $quality)) > $maxFileSize && $quality >= 5) {
                $quality -= 5;
            }
            $databadgeImagePath = Str::slug($request->title) . '-' . 'appupnext' . '.jpg';
            $compressedImagePath = $imgPath . Str::slug($request->title) . '-' . 'appupnext' . '.jpg';
            $img->encode('jpg', $quality)->save($compressedImagePath);
            # Delete the original image after compression
            if (file_exists($imgPath . $proImage)) {
                unlink($imgPath . $proImage);
            }
        }

        $course = Course::create([
            'image' => $databadgeImagePath ?? null,
            'title' => $request->title ?? null,
            'slug' => Str::slug($request->title) ?? null,
            'description' => $request->description ?? null,
            'sort_description' => $request->sort_description ?? null,
            'categories' => $request->categories ?? null,
            'video_url' => $request->video_url ?? null,
            'price' => $request->price ?? null,
            'discount' => $request->discount ?? null,
            'duration_class' => $request->duration_class ?? null,
            'weekly_class' => $request->weekly_class ?? null,
            'skill_requirements' => $request->skill_requirements ?? null,
            'total_student' => $request->total_student ?? null,
            'language' => $request->language ?? null,
            'assignment' => $request->assignment ?? null,
            'status' => $request->status ?? 0,

            'meta_title' => $request->title ?? null,
            'meta_keywords' => $request->description ?? null,
            'meta_description' => $request->description ?? null,
        ]);

        return redirect()->back()->with('success', 'Course Adding Has Been Successfully');
    }

    public function backend_Courses_Edit($id)
    {
        $course = Course::find($id);
        if (!$course) {
            abort(404);
        }
        return view('backend.courses.edit', compact(
            'course',
        ));
    }

    public function backend_Courses_Update(Request $request, Course $course)
    {
        try {
            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'title' => ['required', 'min:1', 'max:255'],
                'description' => ['required', 'min:1'],
                'categories' => ['required'],
                // 'video_url' => ['required'],
                'price' => ['required', 'min:1', 'max:255'],
                'discount' => ['required', 'min:1', 'max:255'],
                'duration_class' => ['required', 'min:1', 'max:255'],
                'weekly_class' => ['required', 'min:1', 'max:255'],
                'skill_requirements' => ['required', 'min:1', 'max:255'],
                'total_student' => ['required', 'min:1', 'max:255'],
                'language' => ['required', 'min:1', 'max:255'],
                'assignment' => ['required', 'min:1', 'max:255'],
                'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/course/' . $course->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/course/';
                $proImage = Str::slug($request->title) . '-appupnext' . '.jpg';
                $image->move($imgPath, $proImage);
                # Compress the newly uploaded image
                $img = Image::make($imgPath . $proImage);
                $maxFileSize = 100 * 1024;
                $quality = 90;
                while (strlen($img->encode('jpg', $quality)) > $maxFileSize && $quality >= 5) {
                    $quality -= 5;
                }
                $compressedImagePath = $imgPath . Str::slug($request->title) . '-appupnext' . '.jpg';
                $img->encode('jpg', $quality)->save($compressedImagePath);
                $input['image'] = $compressedImagePath; # Store the original image name
            } else {
                $existingImage = 'media/course/' . $course->image;
                $newImageName = Str::slug($request->title) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($course->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/course/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName,
                'title' => $request->title ?? null,
                'slug' => Str::slug($request->title) ?? null,
                'description' => $request->description ?? null,
                'sort_description' => $request->sort_description ?? null,
                'categories' => $request->categories ?? null,
                'video_url' => $request->video_url ?? null,
                'price' => $request->price ?? null,
                'discount' => $request->discount ?? null,
                'duration_class' => $request->duration_class ?? null,
                'weekly_class' => $request->weekly_class ?? null,
                'skill_requirements' => $request->skill_requirements ?? null,
                'total_student' => $request->total_student ?? null,
                'language' => $request->language ?? null,
                'assignment' => $request->assignment ?? null,
                'status' => $request->status ?? 0,

                'meta_title' => $request->title ?? null,
                'meta_keywords' => $request->description ?? null,
                'meta_description' => $request->description ?? null,
            ];

            $value = $course->update($dataToUpdate);
            if (!$course) {
                abort(404);
            }

            return redirect()->back()->with('success', 'Course Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Courses_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Course::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Course Status Change Successfully');
    }

    public function backend_Courses_Destory($id)
    {

        $course = Course::find($id);
        $excludedBanners = [
            "banner.png",
        ];
        if (!in_array($course->image, $excludedBanners)) {
            $deleteOldImage = 'media/course/' . $course->image;
            if (File::exists($deleteOldImage)) {
                File::delete($deleteOldImage);
            }
        }
        $course->delete();
        return redirect()->back()->with('success', 'Course Permanently Delete Successfully');
    }
}
