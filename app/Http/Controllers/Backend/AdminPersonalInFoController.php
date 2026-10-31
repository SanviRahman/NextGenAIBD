<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PersonalInfo;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPersonalInFoController extends Controller
{
    public function backend_Personal_Info_Index()
    {
        $personalinfo = PersonalInfo::first();
        return view('backend.personal-info.index', compact(
            'personalinfo',
        ));
    }


    public function backend_Personal_Info_Edit()
    {
        $personalinfo = PersonalInfo::first();
        if (!$personalinfo) {
            abort(404);
        }
        return view('backend.personal-info.edit', compact(
            'personalinfo',
        ));
    }

    public function backend_Personal_Info_Update(Request $request)
    {
        try {

            $personalinfo = PersonalInfo::first();

            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'status' => ['required', 'min:1', 'max:20000'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/banner/' . $personalinfo->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/banner/';
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
                $existingImage = 'media/banner/' . $personalinfo->image;
                $newImageName = Str::slug($request->title) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($personalinfo->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/banner/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName,
                // 'image' => $proImage,
                'name' => $request->name ?? null,
                'address' => $request->address ?? null,
                'email' => $request->email ?? null,
                'number' => $request->number ?? null,
                'wattsup_number' => $request->wattsup_number ?? null,
                'facebook' => $request->facebook ?? null,
                'facebook_page_url' => $request->facebook_page_url ?? null,
                'fiverr_url' => $request->fiverr_url ?? null,
                'video_url' => $request->video_url ?? null,
                'youtube' => $request->youtube ?? null,
                'instagram' => $request->instagram ?? null,
                'twitter' => $request->twitter ?? null,
                'linkdin' => $request->linkdin ?? null,
                'radit' => $request->radit ?? null,
                'tiktok' => $request->tiktok ?? null,
                'pintarest' => $request->pintarest ?? null,
                'sort_description' => $request->sort_description ?? null,
                'description' => $request->description ?? null,
                'skills' => $request->skills ?? null,
            ];

            $value = $personalinfo->update($dataToUpdate);
            if (!$personalinfo) {
                abort(404);
            }
            return redirect()->back()->with('success', 'PersonalInfo Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Personal_Info_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        PersonalInfo::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'PersonalInfo Status Change Successfully');
    }
}
