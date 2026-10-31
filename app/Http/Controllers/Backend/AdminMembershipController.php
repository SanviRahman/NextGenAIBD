<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class AdminMembershipController extends Controller
{
    public function backend_Membership_Index()
    {
        $memberships = Membership::latest('id')->paginate(10);
        $membership = Membership::count();
        $active_memberships = Membership::where('status', 1)->count();
        $pending_memberships = Membership::where('status', 2)->count();
        $draft_memberships = Membership::where('status', 3)->count();

        return view('backend.membership.index', compact(
            'memberships',
            'membership',
            'active_memberships',
            'pending_memberships',
            'draft_memberships',
        ));
    }

    public function backend_Membership_Active()
    {
        $memberships = Membership::where('status', 1)->latest('id')->paginate(10);
        $membership = Membership::count();
        $active_memberships = Membership::where('status', 1)->count();
        $pending_memberships = Membership::where('status', 2)->count();
        $draft_memberships = Membership::where('status', 3)->count();

        return view('backend.membership.index', compact(
            'memberships',
            'membership',
            'active_memberships',
            'pending_memberships',
            'draft_memberships',
        ));
    }

    public function backend_Membership_Pending()
    {
        $memberships = Membership::where('status', 2)->latest('id')->paginate(10);
        $membership = Membership::count();
        $active_memberships = Membership::where('status', 1)->count();
        $pending_memberships = Membership::where('status', 2)->count();
        $draft_memberships = Membership::where('status', 3)->count();

        return view('backend.membership.index', compact(
            'memberships',
            'membership',
            'active_memberships',
            'pending_memberships',
            'draft_memberships',
        ));
    }

    public function backend_Membership_Drafts()
    {
        $memberships = Membership::where('status', 3)->latest('id')->paginate(10);
        $membership = Membership::count();
        $active_memberships = Membership::where('status', 1)->count();
        $pending_memberships = Membership::where('status', 2)->count();
        $draft_memberships = Membership::where('status', 3)->count();

        return view('backend.membership.index', compact(
            'memberships',
            'membership',
            'active_memberships',
            'pending_memberships',
            'draft_memberships',
        ));
    }

    public function backend_Membership_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $memberships = Membership::where('id', 'like', '%' . $query . '%')
            ->orWhere('title', 'like', '%' . $query . '%')
            ->paginate(10);

        $membership = Membership::count();
        $active_memberships = Membership::where('status', 1)->count();
        $pending_memberships = Membership::where('status', 2)->count();
        $draft_memberships = Membership::where('status', 3)->count();

        return view('backend.membership.index', compact(
            'memberships',
            'membership',
            'active_memberships',
            'pending_memberships',
            'draft_memberships',
        ));
    }

    public function backend_Membership_Create()
    {
        return view('backend.membership.create');
    }

    public function backend_Membership_Store(Request $request)
    {
        $request->validate([
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'title' => ['required', 'min:1', 'max:255'],
            // 'sort_description' => ['required', 'min:1', 'max:2000'],
            // 'description' => ['required', 'min:1', 'max:20000'],
            // 'video_url' => ['required', 'min:1', 'max:355'],
            // 'views' => ['required', 'min:1', 'max:255'],
        ]);

        if ($image = $request->file('image')) {
            $imgPath = 'media/membership/';
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

        $membership = Membership::create([
            'image' => $databadgeImagePath ?? 'default.png',
            'title' => $request->title ?? null,
            'slug' => Str::slug($request->title) ?? null,
            'number' => $request->number ?? null,
            'website_url' => $request->website_url ?? null,
            'page_url' => $request->page_url ?? null,
            'address' => $request->address ?? null,
            'email' => $request->email ?? null,
            'password' => $request->password ?? null,
            'total_costing' => $request->total_costing ?? null,
            'total_service' => $request->total_service ?? null,
            'sort_description' => $request->sort_description ?? null,
            'description' => $request->description ?? null,
            'video_url' => $request->video_url ?? null,
            'status' => $request->status ?? 1,

            'meta_title' => $request->title ?? null,
            'meta_keywords' => $request->description ?? null,
            'meta_description' => $request->description ?? null,
        ]);

        return redirect()->back()->with('success', 'membership Adding Has Been Successfully');
    }

    public function backend_Membership_Edit($id)
    {
        $membership = Membership::find($id);
        if (!$membership) {
            abort(404);
        }
        return view('backend.membership.edit', compact(
            'membership',
        ));
    }

    public function backend_Membership_Update(Request $request, Membership $membership)
    {
        try {
            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'title' => ['required', 'min:1', 'max:255'],
                // 'sort_description' => ['required', 'min:1', 'max:2000'],
                // 'description' => ['required', 'min:1', 'max:20000'],
                // 'video_url' => ['required', 'min:1', 'max:255'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/membership/' . $membership->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/membership/';
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
                $existingImage = 'media/membership/' . $membership->image;
                $newImageName = Str::slug($request->title) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($membership->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/membership/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName ?? null,
                'title' => $request->title ?? null,
                'slug' => Str::slug($request->title) ?? null,
                'number' => $request->number ?? null,
                'website_url' => $request->website_url ?? null,
                'page_url' => $request->page_url ?? null,
                'address' => $request->address ?? null,
                'email' => $request->email ?? null,
                'password' => $request->password ?? null,
                'total_costing' => $request->total_costing ?? null,
                'total_service' => $request->total_service ?? null,
                'sort_description' => $request->sort_description ?? null,
                'description' => $request->description ?? null,
                'video_url' => $request->video_url ?? null,
                'status' => $request->status ?? 0,

                'meta_title' => $request->title ?? null,
                'meta_keywords' => $request->description ?? null,
                'meta_description' => $request->description ?? null,
            ];

            $value = $membership->update($dataToUpdate);
            if (!$membership) {
                abort(404);
            }

            return redirect()->back()->with('success', 'membership Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Membership_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Membership::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'membership Status Change Successfully');
    }

    public function backend_Membership_Destory($id)
    {

        $membership = Membership::find($id);
        $excludedBanners = [
            "banner.png",
        ];
        if (!in_array($membership->image, $excludedBanners)) {
            $deleteOldImage = 'media/membership/' . $membership->image;
            if (File::exists($deleteOldImage)) {
                File::delete($deleteOldImage);
            }
        }
        $membership->delete();
        return redirect()->back()->with('success', 'membership Permanently Delete Successfully');
    }
}
