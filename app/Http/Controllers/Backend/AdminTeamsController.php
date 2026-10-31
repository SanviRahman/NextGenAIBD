<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminTeamsController extends Controller
{
    public function backend_Teams_Index()
    {
        $teams = Team::latest('id')->paginate(10);
        $team = Team::count();
        $active_teams = Team::where('status', 1)->count();
        $pending_teams = Team::where('status', 2)->count();
        $draft_teams = Team::where('status', 3)->count();

        return view('backend.teams.index', compact(
            'teams',
            'team',
            'active_teams',
            'pending_teams',
            'draft_teams',
        ));
    }

    public function backend_Teams_Active()
    {
        $teams = Team::where('status', 1)->latest('id')->paginate(10);
        $team = Team::count();
        $active_teams = Team::where('status', 1)->count();
        $pending_teams = Team::where('status', 2)->count();
        $draft_teams = Team::where('status', 3)->count();

        return view('backend.teams.index', compact(
            'teams',
            'team',
            'active_teams',
            'pending_teams',
            'draft_teams',
        ));
    }

    public function backend_Teams_Pending()
    {
        $teams = Team::where('status', 2)->latest('id')->paginate(10);
        $team = Team::count();
        $active_teams = Team::where('status', 1)->count();
        $pending_teams = Team::where('status', 2)->count();
        $draft_teams = Team::where('status', 3)->count();

        return view('backend.teams.index', compact(
            'teams',
            'team',
            'active_teams',
            'pending_teams',
            'draft_teams',
        ));
    }

    public function backend_Teams_Drafts()
    {
        $teams = Team::where('status', 3)->latest('id')->paginate(10);
        $team = Team::count();
        $active_teams = Team::where('status', 1)->count();
        $pending_teams = Team::where('status', 2)->count();
        $draft_teams = Team::where('status', 3)->count();

        return view('backend.teams.index', compact(
            'teams',
            'team',
            'active_teams',
            'pending_teams',
            'draft_teams',
        ));
    }

    public function backend_Teams_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $teams = Team::where('id', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->paginate(10);

        $team = Team::count();
        $active_teams = Team::where('status', 1)->count();
        $pending_teams = Team::where('status', 2)->count();
        $draft_teams = Team::where('status', 3)->count();

        return view('backend.teams.index', compact(
            'teams',
            'team',
            'active_teams',
            'pending_teams',
            'draft_teams',
        ));
    }

    public function backend_Teams_Create()
    {
        return view('backend.teams.create');
    }

    public function backend_Teams_Store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'name' => ['required', 'min:1', 'max:255'],
            'description' => ['required', 'min:1', 'max:20000'],
        ]);

        if ($image = $request->file('image')) {
            $imgPath = 'media/team/';
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

        $team = Team::create([
            'image' => $databadgeImagePath ?? null,
            'name' => $request->name ?? null,
            'slug' => Str::slug($request->name) ?? null,
            'description' => $request->description ?? null,
            'sort_description' => $request->sort_description ?? null,
            'video_url' => $request->video_url ?? null,
            'status' => $request->status ?? 1,

            'meta_title' => $request->title ?? null,
            'meta_keywords' => $request->description ?? null,
            'meta_description' => $request->description ?? null,
        ]);

        return redirect()->back()->with('success', 'Team Adding Has Been Successfully');
    }

    public function backend_Teams_Edit($id)
    {
        $team = Team::find($id);
        if (!$team) {
            abort(404);
        }
        return view('backend.teams.edit', compact(
            'team',
        ));
    }

    public function backend_Teams_Update(Request $request, Team $team)
    {
        try {
            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'name' => ['required', 'min:1', 'max:255'],
                'description' => ['required', 'min:1', 'max:20000'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/team/' . $team->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/team/';
                $proImage = Str::slug($request->name) . '-appupnext' . '.jpg';
                $image->move($imgPath, $proImage);
                # Compress the newly uploaded image
                $img = Image::make($imgPath . $proImage);
                $maxFileSize = 100 * 1024;
                $quality = 90;
                while (strlen($img->encode('jpg', $quality)) > $maxFileSize && $quality >= 5) {
                    $quality -= 5;
                }
                $compressedImagePath = $imgPath . Str::slug($request->name) . '-appupnext' . '.jpg';
                $img->encode('jpg', $quality)->save($compressedImagePath);
                $input['image'] = $compressedImagePath; # Store the original image name
            } else {
                $existingImage = 'media/team/' . $team->image;
                $newImageName = Str::slug($request->name) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($team->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/team/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName,
                'name' => $request->name ?? null,
                'slug' => Str::slug($request->name) ?? null,
                'description' => $request->description ?? null,
                'sort_description' => $request->sort_description ?? null,
                'video_url' => $request->video_url ?? null,
                'status' => $request->status ?? 0,

                'meta_title' => $request->title ?? null,
                'meta_keywords' => $request->description ?? null,
                'meta_description' => $request->description ?? null,
            ];

            $value = $team->update($dataToUpdate);
            if (!$team) {
                abort(404);
            }
            return redirect()->back()->with('success', 'Team Adding Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Teams_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Team::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Team Status Change Successfully');
    }

    public function backend_Teams_Destory($id)
    {
        $team = Team::find($id);
        $excludedBanners = [
            "banner.png",
        ];
        if (!in_array($team->image, $excludedBanners)) {
            $deleteOldImage = 'media/team/' . $team->image;
            if (File::exists($deleteOldImage)) {
                File::delete($deleteOldImage);
            }
        }
        $team->delete();
        return redirect()->back()->with('success', 'Team Permanently Delete Successfully');
    }
}
