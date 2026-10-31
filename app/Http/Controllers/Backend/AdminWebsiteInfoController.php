<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\WebsiteInfo;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminWebsiteInfoController extends Controller
{
    public function backend_Website_Info_Index()
    {
        $websiteinfo = WebsiteInfo::first();
        return view('backend.website-info.index', compact(
            'websiteinfo',
        ));
    }


    public function backend_Website_Info_Edit()
    {
        $websiteinfo = WebsiteInfo::first();
        if (!$websiteinfo) {
            abort(404);
        }
        return view('backend.website-info.edit', compact(
            'websiteinfo',
        ));
    }

    public function backend_Website_Info_Update(Request $request)
    {
        try {

            $websiteinfo = WebsiteInfo::first();

            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'status' => ['required', 'min:1', 'max:20000'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/banner/' . $websiteinfo->image;
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
                $existingImage = 'media/banner/' . $websiteinfo->image;
                $newImageName = Str::slug($request->title) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($websiteinfo->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/banner/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName,
                // 'image' => $proImage,
                'address' => $request->address ?? null,
                'email' => $request->email ?? null,
                'number' => $request->number ?? null,
                'wattsup_number' => $request->wattsup_number ?? null,
                'facebook' => $request->facebook ?? null,
                'instagram' => $request->instagram ?? null,
                'twitter' => $request->twitter ?? null,
                'linkdin' => $request->linkdin ?? null,
                'radit' => $request->radit ?? null,
                'tiktok' => $request->tiktok ?? null,
                'pintarest' => $request->pintarest ?? null,
                'sort_descroption' => $request->sort_descroption ?? null,
            ];

            $value = $websiteinfo->update($dataToUpdate);
            if (!$websiteinfo) {
                abort(404);
            }
            return redirect()->back()->with('success', 'WebsiteInfo Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Website_Info_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        WebsiteInfo::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'WebsiteInfo Status Change Successfully');
    }
}
