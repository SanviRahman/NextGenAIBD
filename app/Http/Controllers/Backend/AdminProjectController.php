<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProjectController extends Controller
{
    public function backend_Projects_Index()
    {
        $projects = Project::latest('id')->paginate(10);
        $project = Project::count();
        $active_projects = Project::where('status', 1)->count();
        $pending_projects = Project::where('status', 2)->count();
        $draft_projects = Project::where('status', 3)->count();

        return view('backend.projects.index', compact(
            'projects',
            'project',
            'active_projects',
            'pending_projects',
            'draft_projects',
        ));
    }

    public function backend_Projects_Active()
    {
        $projects = Project::where('status', 1)->latest('id')->paginate(10);
        $project = Project::count();
        $active_projects = Project::where('status', 1)->count();
        $pending_projects = Project::where('status', 2)->count();
        $draft_projects = Project::where('status', 3)->count();

        return view('backend.projects.index', compact(
            'projects',
            'project',
            'active_projects',
            'pending_projects',
            'draft_projects',
        ));
    }

    public function backend_Projects_Pending()
    {
        $projects = Project::where('status', 2)->latest('id')->paginate(10);
        $project = Project::count();
        $active_projects = Project::where('status', 1)->count();
        $pending_projects = Project::where('status', 2)->count();
        $draft_projects = Project::where('status', 3)->count();

        return view('backend.projects.index', compact(
            'projects',
            'project',
            'active_projects',
            'pending_projects',
            'draft_projects',
        ));
    }

    public function backend_Projects_Drafts()
    {
        $projects = Project::where('status', 3)->latest('id')->paginate(10);
        $project = Project::count();
        $active_projects = Project::where('status', 1)->count();
        $pending_projects = Project::where('status', 2)->count();
        $draft_projects = Project::where('status', 3)->count();

        return view('backend.projects.index', compact(
            'projects',
            'project',
            'active_projects',
            'pending_projects',
            'draft_projects',
        ));
    }

    public function backend_Projects_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $projects = Project::where('id', 'like', '%' . $query . '%')
            ->orWhere('title', 'like', '%' . $query . '%')
            ->paginate(10);

        $project = Project::count();
        $active_projects = Project::where('status', 1)->count();
        $pending_projects = Project::where('status', 2)->count();
        $draft_projects = Project::where('status', 3)->count();

        return view('backend.projects.index', compact(
            'projects',
            'project',
            'active_projects',
            'pending_projects',
            'draft_projects',
        ));
    }

    public function backend_Projects_Create()
    {
        return view('backend.projects.create');
    }

    public function backend_Projects_Store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'title' => ['required', 'min:1', 'max:255'],
            'sort_description' => ['required', 'min:1', 'max:2000'],
            'description' => ['required', 'min:1', 'max:20000'],
            // 'categories' => ['required', 'min:1', 'max:255'],
            // 'video_url' => ['required', 'min:1', 'max:355'],
            // 'views' => ['required', 'min:1', 'max:255'],
        ]);

        if ($image = $request->file('image')) {
            $imgPath = 'media/project/';
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

        $project = Project::create([
            'image' => $databadgeImagePath ?? null,
            'title' => $request->title ?? null,
            'slug' => Str::slug($request->title) ?? null,
            'sort_description' => $request->sort_description ?? null,
            'description' => $request->description ?? null,
            'categories' => $request->categories ?? null,
            'video_url' => $request->video_url ?? null,
            'status' => $request->status ?? 1,

            'meta_title' => $request->title ?? null,
            'meta_keywords' => $request->description ?? null,
            'meta_description' => $request->description ?? null,
        ]);

        return redirect()->back()->with('success', 'Project Adding Has Been Successfully');
    }

    public function backend_Projects_Edit($id)
    {
        $project = Project::find($id);
        if (!$project) {
            abort(404);
        }
        return view('backend.projects.edit', compact(
            'project',
        ));
    }

    public function backend_Projects_Update(Request $request, Project $project)
    {
        try {
            $request->validate([
                'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
                'title' => ['required', 'min:1', 'max:255'],
                'sort_description' => ['required', 'min:1', 'max:2000'],
                'description' => ['required', 'min:1', 'max:20000'],
                // 'categories' => ['required', 'min:1', 'max:255'],
                // 'video_url' => ['required', 'min:1', 'max:255'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/project/' . $project->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/project/';
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
                $existingImage = 'media/project/' . $project->image;
                $newImageName = Str::slug($request->title) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($project->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/project/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName,
                'title' => $request->title ?? null,
                'slug' => Str::slug($request->title) ?? null,
                'sort_description' => $request->sort_description ?? null,
                'description' => $request->description ?? null,
                'categories' => $request->categories ?? null,
                'video_url' => $request->video_url ?? null,
                'status' => $request->status ?? 0,

                'meta_title' => $request->title ?? null,
                'meta_keywords' => $request->description ?? null,
                'meta_description' => $request->description ?? null,
            ];

            $value = $project->update($dataToUpdate);
            if (!$project) {
                abort(404);
            }

            return redirect()->back()->with('success', 'Project Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Projects_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Project::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Project Status Change Successfully');
    }

    public function backend_Projects_Destory($id)
    {

        $project = Project::find($id);
        $excludedBanners = [
            "banner.png",
        ];
        if (!in_array($project->image, $excludedBanners)) {
            $deleteOldImage = 'media/project/' . $project->image;
            if (File::exists($deleteOldImage)) {
                File::delete($deleteOldImage);
            }
        }
        $project->delete();
        return redirect()->back()->with('success', 'Project Permanently Delete Successfully');
    }
}
