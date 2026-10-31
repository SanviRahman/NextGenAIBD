<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSuccessStoryController extends Controller
{
    public function backend_Success_Story_Index()
    {
        $successStory = SuccessStory::first();

        return view('backend.success-story.index', compact(
            'successStory',
        ));
    }


    public function backend_Success_Story_Edit()
    {
        $successStory = SuccessStory::first();
        if (!$successStory) {
            abort(404);
        }
        return view('backend.success-story.edit', compact(
            'successStory',
        ));
    }

    public function backend_Success_Story_Update(Request $request)
    {
        try {

            $successStory = SuccessStory::first();

            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'status' => ['required', 'min:1', 'max:20000'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            // if ($image = $request->file('image')) {
            //     $deleteOldImage = 'media/successStory/' . $successStory->image;
            //     if (File::exists($deleteOldImage)) {
            //         File::delete($deleteOldImage);
            //     }
            //     $imgPath = 'media/successStory/';
            //     $proImage = date('YmdHis') . "-image-appupnext." . $image->getClientOriginalExtension();
            //     $image->move($imgPath, $proImage);
            //     $input['image'] = "$proImage";
            // } else {
            //      unset($input['image']);
            // }

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/successStory/' . $successStory->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/successStory/';
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
                $existingImage = 'media/successStory/' . $successStory->image;
                $newImageName = Str::slug($request->title) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($successStory->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/successStory/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName,
                // 'image' => $proImage,
                'title' => $request->title ?? null,
                'sort_title' => $request->sort_title ?? null,
                'description' => $request->description ?? null,
                'video_url' => $request->video_url ?? null,
                'status' => $request->status ?? 0,
            ];

            $value = $successStory->update($dataToUpdate);
            if (!$successStory) {
                abort(404);
            }
            return redirect()->back()->with('success', 'Success Story Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Success_Story_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        SuccessStory::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Success Story Status Change Successfully');
    }
}

