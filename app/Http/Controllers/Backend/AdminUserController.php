<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function backend_Users_Index()
    {
        $users = User::latest('id')->paginate(20);
        $user = User::count();
        $active_users = User::where('status', 1)->count();
        $pending_users = User::where('status', 2)->count();
        $draft_users = User::where('status', 0)->count();

        return view('backend.users.index', compact(
            'users',
            'user',
            'active_users',
            'pending_users',
            'draft_users',
        ));
    }

    public function backend_Users_Active()
    {
        $users = User::where('status', 1)->latest('id')->paginate(20);
        $user = User::count();
        $active_users = User::where('status', 1)->count();
        $pending_users = User::where('status', 2)->count();
        $draft_users = User::where('status', 0)->count();

        return view('backend.users.index', compact(
            'users',
            'user',
            'active_users',
            'pending_users',
            'draft_users',
        ));
    }

    public function backend_Users_Pending()
    {
        $users = User::where('status', 2)->latest('id')->paginate(20);
        $user = User::count();
        $active_users = User::where('status', 1)->count();
        $pending_users = User::where('status', 2)->count();
        $draft_users = User::where('status', 0)->count();

        return view('backend.users.index', compact(
            'users',
            'user',
            'active_users',
            'pending_users',
            'draft_users',
        ));
    }

    public function backend_Users_Drafts()
    {
        $users = User::where('status', 0)->latest('id')->paginate(20);
        $user = User::count();
        $active_users = User::where('status', 1)->count();
        $pending_users = User::where('status', 2)->count();
        $draft_users = User::where('status', 0)->count();

        return view('backend.users.index', compact(
            'users',
            'user',
            'active_users',
            'pending_users',
            'draft_users',
        ));
    }

    public function backend_Users_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $users = User::where('id', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->orWhere('account_name', 'like', '%' . $query . '%')
            ->orWhere('account_number', 'like', '%' . $query . '%')
            ->paginate(20);

        $user = User::count();
        $active_users = User::where('status', 1)->count();
        $pending_users = User::where('status', 2)->count();
        $draft_users = User::where('status', 0)->count();

        return view('backend.users.index', compact(
            'users',
            'user',
            'active_users',
            'pending_users',
            'draft_users',
        ));
    }

    public function backend_Users_Create()
    {
        $countries = Country::where('status', 1)->get();
        return view('backend.users.create', compact(
            'countries',
        ));
    }

    public function backend_Users_Store(Request $request)
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
            $imgPath = 'media/profile/';
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

        $user = User::create([
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

        return redirect()->back()->with('success', 'User Adding Has Been Successfully');
    }

    public function backend_Users_Edit($id)
    {
        $user = User::find($id);
        $countries = Country::where('status', 1)->get();

        return view('backend.users.edit', compact(
            'user',
            'countries',
        ));
    }

    public function backend_Users_Update(Request $request, User $user)
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
                $deleteOldImage = 'media/profile/' . $user->avatar;
                # Check if the file exists and its name is not "avatar.png"
                if (File::exists($deleteOldImage) && $user->avatar != 'avatar.png') {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/profile/';
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
                $existingImage = 'media/profile/' . $user->avatar;
                $newImageName = Str::slug($request->name) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($user->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/profile/' . $newImageName);
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

            $value = $user->update($dataToUpdate);
            return redirect()->back()->with('success', 'User Adding Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Users_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        User::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'User Status Change Successfully');
    }

    public function backend_Users_Destory($id)
    {
        $user = User::find($id);
        $deleteOldImage = 'media/profile/' . $user->avatar;
        # Check if the file exists and its name is not "avatar.png"
        if (File::exists($deleteOldImage) && $user->avatar != 'avatar.png') {
            File::delete($deleteOldImage);
        }
        $user->delete();
        return redirect()->back()->with('success', 'User Permanently Delete Successfully');
    }
}

