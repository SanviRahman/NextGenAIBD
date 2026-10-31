<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminAboutController extends Controller
{
    public function backend_Abouts_Index()
    {
        $about = About::first();

        return view('backend.abouts.index', compact(
            'about',
        ));
    }


    public function backend_Abouts_Edit()
    {
        $about = About::first();
        if (!$about) {
            abort(404);
        }
        return view('backend.abouts.edit', compact(
            'about',
        ));
    }

    public function backend_Abouts_Update(Request $request)
    {
        try {

            $about = About::first();

            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'status' => ['required', 'min:1', 'max:20000'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/about/' . $about->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/about/';
                $proImage = date('YmdHis') . "-image-appupnext." . $image->getClientOriginalExtension();
                $image->move($imgPath, $proImage);
                $input['image'] = "$proImage";
            } else {
                 unset($input['image']);
            }

            $dataToUpdate = [
                // 'image' => $proImage ?? $newImageName,
                'image' => $proImage,
                'description' => $request->description ?? null,
                'title' => $request->title ?? null,
                'video_url' => $request->video_url ?? null,
                'status' => $request->status ?? 0,
            ];

            $value = $about->update($dataToUpdate);
            if (!$about) {
                abort(404);
            }
            return redirect()->back()->with('success', 'About Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Abouts_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        About::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'About Status Change Successfully');
    }

}
