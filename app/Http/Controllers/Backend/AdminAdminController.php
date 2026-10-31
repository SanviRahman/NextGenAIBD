<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAdminController extends Controller
{
    public function backend_Admins_Index()
    {
        $admins = Admin::paginate(20);
        $admin = Admin::count();
        $active_admins = Admin::where('status', 1)->count();
        $pending_admins = Admin::where('status', 2)->count();
        $draft_admins = Admin::where('status', 0)->count();

        return view('backend.admins.index', compact(
            'admins',
            'admin',
            'active_admins',
            'pending_admins',
            'draft_admins',
        ));
    }

    public function backend_Admins_Active()
    {
        $admins = Admin::where('status', 1)->paginate(20);
        $admin = Admin::count();
        $active_admins = Admin::where('status', 1)->count();
        $pending_admins = Admin::where('status', 2)->count();
        $draft_admins = Admin::where('status', 0)->count();

        return view('backend.admins.index', compact(
            'admins',
            'admin',
            'active_admins',
            'pending_admins',
            'draft_admins',
        ));
    }

    public function backend_Admins_Pending()
    {
        $admins = Admin::where('status', 2)->paginate(20);
        $admin = Admin::count();
        $active_admins = Admin::where('status', 1)->count();
        $pending_admins = Admin::where('status', 2)->count();
        $draft_admins = Admin::where('status', 0)->count();

        return view('backend.admins.index', compact(
            'admins',
            'admin',
            'active_admins',
            'pending_admins',
            'draft_admins',
        ));
    }

    public function backend_Admins_Drafts()
    {
        $admins = Admin::where('status', 0)->paginate(20);
        $admin = Admin::count();
        $active_admins = Admin::where('status', 1)->count();
        $pending_admins = Admin::where('status', 2)->count();
        $draft_admins = Admin::where('status', 0)->count();

        return view('backend.admins.index', compact(
            'admins',
            'admin',
            'active_admins',
            'pending_admins',
            'draft_admins',
        ));
    }

    public function backend_Admins_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $admins = Admin::where('id', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->orWhere('account_name', 'like', '%' . $query . '%')
            ->orWhere('account_number', 'like', '%' . $query . '%')
            ->paginate(20);

        $admin = Admin::count();
        $active_admins = Admin::where('status', 1)->count();
        $pending_admins = Admin::where('status', 2)->count();
        $draft_admins = Admin::where('status', 0)->count();

        return view('backend.admins.index', compact(
            'admins',
            'admin',
            'active_admins',
            'pending_admins',
            'draft_admins',
        ));
    }

    public function backend_Admins_Create()
    {
        $countries = Country::where('status', 1)->get();
        return view('backend.admins.create', compact(
            'countries',
        ));
    }

    public function backend_Admins_Store(Request $request)
    {
        $request->validate([
            // 'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'name' => ['required', 'min:1', 'max:255'],
            'username' => ['required', 'min:1', 'max:255'],
            'type' => ['required', 'min:1', 'max:255'],
            'country' => ['required', 'min:1', 'max:255'],
            // 'phonecode' => ['required', 'min:1', 'max:255'],
            'mobile' => ['required', 'min:1', 'max:255'],
            'email' => ['required', 'min:1', 'max:255'],
            'password' => ['required', 'min:1', 'max:255'],
            // 'password_confirmation' => ['required', 'min:1', 'max:255'],
            'status' => ['required', 'min:1', 'max:255'],
        ]);

        if ($avatar = $request->file('avatar')) {
            $imgPath = 'media/admin/';
            $proImage = Str::slug($request->name) . '.' . $avatar->getClientOriginalExtension();
            # Move the original image to the destination folder
            $avatar->move($imgPath, $proImage);
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

        $admin = Admin::create([
            'avatar' => $databadgeImagePath ?? 'avatar.png',
            'name' => $request->name ?? null,
            'username' => Str::slug($request->username)  ?? Str::slug($request->name),
            'type' => $request->type ?? null,
            'country' => $request->country ?? null,
            'phonecode' => $request->phonecode ?? null,
            'mobile' => $request->mobile ?? null,
            'email' => $request->email ?? null,
            'password' => Hash::make($request->password) ?? null,
            'password_confirmation' => $request->password ?? null,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->back()->with('success', 'Admin Adding Has Been Successfully');
    }

    public function backend_Admins_Edit($id)
    {
        $admin = Admin::find($id);
        $countries = Country::where('status', 1)->get();

        return view('backend.admins.edit', compact(
            'admin',
            'countries',
        ));
    }

    public function backend_Admins_Update(Request $request, Admin $admin)
    {
        try {
            $request->validate([
                // 'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'name' => ['required', 'min:1', 'max:255'],
                'username' => ['required', 'min:1', 'max:255'],
                'type' => ['required', 'min:1', 'max:255'],
                'country' => ['required', 'min:1', 'max:255'],
                // 'phonecode' => ['required', 'min:1', 'max:255'],
                'mobile' => ['required', 'min:1', 'max:255'],
                'email' => ['required', 'min:1', 'max:255'],
                'password' => ['required', 'min:1', 'max:255'],
                // 'password_confirmation' => ['required', 'min:1', 'max:255'],
                'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($avatar = $request->file('avatar')) {

                $excludedBanners = [
                    "avatar.png",
                ];
                if (!in_array($admin->avatar, $excludedBanners)) {

                    $deleteOldImage = 'media/admin/' . $admin->avatar;
                    # Check if the file exists and its name is not "avatar.png"
                    if (File::exists($deleteOldImage)) {
                        File::delete($deleteOldImage);
                    }
                }

                $imgPath = 'media/admin/';
                $proImage = Str::slug($request->name) . '-appupnext' . '.jpg';
                $avatar->move($imgPath, $proImage);
                # Compress the newly uploaded image
                $img = Image::make($imgPath . $proImage);
                $maxFileSize = 100 * 1024;
                $quality = 90;
                while (strlen($img->encode('jpg', $quality)) > $maxFileSize && $quality >= 5) {
                    $quality -= 5;
                }
                $compressedImagePath = $imgPath . Str::slug($request->name) . '-appupnext' . '.jpg';
                $img->encode('jpg', $quality)->save($compressedImagePath);
                $input['avatar'] = $compressedImagePath; # Store the original image name
            } else {
                $existingImage = 'media/admin/' . $admin->avatar;
                $newImageName = Str::slug($request->name) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                
                if ($admin->image !== $newImageName && $newImageName !== 'avatar.png' && File::exists($existingImage)) {
                    File::move($existingImage, 'media/admin/' . $newImageName);
                }
                $input['avatar'] = $newImageName;
            }

            $dataToUpdate = [
                'avatar' => $proImage ?? $newImageName,
                'name' => $request->name ?? null,
                'username' => Str::slug($request->username)  ?? Str::slug($request->name),
                'type' => $request->type ?? null,
                'country' => $request->country ?? null,
                'phonecode' => $request->phonecode ?? null,
                'mobile' => $request->mobile ?? null,
                'email' => $request->email ?? null,
                'password' => Hash::make($request->password) ?? null,
                'password_confirmation' => $request->password ?? null,
                'status' => $request->status ?? 1,
            ];

            $value = $admin->update($dataToUpdate);
            return redirect()->back()->with('success', 'Admin Adding Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Admins_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Admin::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Admin Status Change Successfully');
    }

    public function backend_Admins_Destory($id)
    {
        $admin = Admin::find($id);
        $deleteOldImage = 'media/admin/' . $admin->avatar;
        # Check if the file exists and its name is not "avatar.png"
        if (File::exists($deleteOldImage) && $admin->avatar != 'avatar.png') {
            File::delete($deleteOldImage);
        }
        $admin->delete();
        return redirect()->back()->with('success', 'Admin Permanently Delete Successfully');
    }
}
