<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ApplyCourse;
use App\Models\Course;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminApplyCourseController extends Controller
{
    public function backend_ApplyCourse_Index()
    {
        $courseApplys = ApplyCourse::latest('id')->paginate(10);
        $courseApply = ApplyCourse::count();
        $active_courseApplys = ApplyCourse::where('status', 1)->count();
        $pending_courseApplys = ApplyCourse::where('status', 2)->count();
        $draft_courseApplys = ApplyCourse::where('status', 3)->count();

        return view('backend.apply-course.index', compact(
            'courseApplys',
            'courseApply',
            'active_courseApplys',
            'pending_courseApplys',
            'draft_courseApplys',
        ));
    }

    public function backend_ApplyCourse_Active()
    {
        $courseApplys = ApplyCourse::where('status', 1)->latest('id')->paginate(10);
        $courseApply = ApplyCourse::count();
        $active_courseApplys = ApplyCourse::where('status', 1)->count();
        $pending_courseApplys = ApplyCourse::where('status', 2)->count();
        $draft_courseApplys = ApplyCourse::where('status', 3)->count();

        return view('backend.apply-course.index', compact(
            'courseApplys',
            'courseApply',
            'active_courseApplys',
            'pending_courseApplys',
            'draft_courseApplys',
        ));
    }

    public function backend_ApplyCourse_Pending()
    {
        $courseApplys = ApplyCourse::where('status', 2)->latest('id')->paginate(10);
        $courseApply = ApplyCourse::count();
        $active_courseApplys = ApplyCourse::where('status', 1)->count();
        $pending_courseApplys = ApplyCourse::where('status', 2)->count();
        $draft_courseApplys = ApplyCourse::where('status', 3)->count();

        return view('backend.apply-course.index', compact(
            'courseApplys',
            'courseApply',
            'active_courseApplys',
            'pending_courseApplys',
            'draft_courseApplys',
        ));
    }

    public function backend_ApplyCourse_Drafts()
    {
        $courseApplys = ApplyCourse::where('status', 3)->latest('id')->paginate(10);
        $courseApply = ApplyCourse::count();
        $active_courseApplys = ApplyCourse::where('status', 1)->count();
        $pending_courseApplys = ApplyCourse::where('status', 2)->count();
        $draft_courseApplys = ApplyCourse::where('status', 3)->count();

        return view('backend.apply-course.index', compact(
            'courseApplys',
            'courseApply',
            'active_courseApplys',
            'pending_courseApplys',
            'draft_courseApplys',
        ));
    }

    public function backend_ApplyCourse_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $courseApplys = ApplyCourse::where('id', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->orWhere('number', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->orWhere('educational_institute', 'like', '%' . $query . '%')
            ->orWhere('course', 'like', '%' . $query . '%')
            ->orWhere('course_fee', 'like', '%' . $query . '%')
            ->paginate(10);

        $courseApply = ApplyCourse::count();
        $active_courseApplys = ApplyCourse::where('status', 1)->count();
        $pending_courseApplys = ApplyCourse::where('status', 2)->count();
        $draft_courseApplys = ApplyCourse::where('status', 3)->count();

        return view('backend.apply-course.index', compact(
            'courseApplys',
            'courseApply',
            'active_courseApplys',
            'pending_courseApplys',
            'draft_courseApplys',
        ));
    }

    public function backend_ApplyCourse_Create()
    {
        $courses = Course::where('status', 1)->get();
        return view('backend.apply-course.create', compact(
            'courses'
        ));
    }

    public function backend_ApplyCourse_Store(Request $request)
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

    public function backend_ApplyCourse_Edit($id)
    {
        $courseApply = ApplyCourse::find($id);
        if (!$courseApply) {
            abort(404);
        }

        $courses = Course::where('status', 1)->get();

        return view('backend.apply-course.edit', compact(
            'courseApply',
            'courses',
        ));
    }

    public function backend_ApplyCourse_Update(Request $request, ApplyCourse $applyCourse)
    {
        try {
            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'name' => ['required', 'min:1', 'max:255'],
                // 'description' => ['required', 'min:1', 'max:20000'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/courseapply/' . $applyCourse->image;

                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }

                $imgPath = 'media/courseapply/';
                $proImage = Str::slug($request->name) . '-appupnext' . '.jpg';
                $image->move($imgPath, $proImage);

                // Compress the newly uploaded image
                $img = Image::make($imgPath . $proImage);
                $maxFileSize = 100 * 1024;
                $quality = 90;
                while (strlen($img->encode('jpg', $quality)) > $maxFileSize && $quality >= 5) {
                    $quality -= 5;
                }
                $compressedImagePath = $imgPath . $proImage;
                $img->encode('jpg', $quality)->save($compressedImagePath);

                $input['image'] = $compressedImagePath; // Store the compressed image
            } else {
                $existingImage = 'media/courseapply/' . $applyCourse->image;
                $newImageName = Str::slug($request->name) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);

                if ($applyCourse->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/courseapply/' . $newImageName);
                }

                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName ?? null,
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
            ];

            $value = $applyCourse->update($dataToUpdate);
            if (!$applyCourse) {
                abort(404);
            }

            return redirect()->back()->with('success', 'Course Apply Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_ApplyCourse_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        ApplyCourse::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Course Apply Status Change Successfully');
    }

    public function backend_ApplyCourse_Destory($id)
    {

        $courseApply = ApplyCourse::find($id);
        $excludedBanners = [
            "banner.png",
        ];
        if (!in_array($courseApply->image, $excludedBanners)) {
            $deleteOldImage = 'media/courseapply/' . $courseApply->image;
            if (File::exists($deleteOldImage)) {
                File::delete($deleteOldImage);
            }
        }
        $courseApply->delete();
        return redirect()->back()->with('success', 'Course Apply Permanently Delete Successfully');
    }
}
