<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBlogsController extends Controller
{
    public function backend_Blogs_Index()
    {
        $blogs = Blog::latest('id')->paginate(10);
        $blog = Blog::count();
        $active_blogs = Blog::where('status', 1)->count();
        $pending_blogs = Blog::where('status', 2)->count();
        $draft_blogs = Blog::where('status', 3)->count();

        return view('backend.blogs.index', compact(
            'blogs',
            'blog',
            'active_blogs',
            'pending_blogs',
            'draft_blogs',
        ));
    }

    public function backend_Blogs_Active()
    {
        $blogs = Blog::where('status', 1)->latest('id')->paginate(10);
        $blog = Blog::count();
        $active_blogs = Blog::where('status', 1)->count();
        $pending_blogs = Blog::where('status', 2)->count();
        $draft_blogs = Blog::where('status', 3)->count();

        return view('backend.blogs.index', compact(
            'blogs',
            'blog',
            'active_blogs',
            'pending_blogs',
            'draft_blogs',
        ));
    }

    public function backend_Blogs_Pending()
    {
        $blogs = Blog::where('status', 2)->latest('id')->paginate(10);
        $blog = Blog::count();
        $active_blogs = Blog::where('status', 1)->count();
        $pending_blogs = Blog::where('status', 2)->count();
        $draft_blogs = Blog::where('status', 3)->count();

        return view('backend.blogs.index', compact(
            'blogs',
            'blog',
            'active_blogs',
            'pending_blogs',
            'draft_blogs',
        ));
    }

    public function backend_Blogs_Drafts()
    {
        $blogs = Blog::where('status', 3)->latest('id')->paginate(10);
        $blog = Blog::count();
        $active_blogs = Blog::where('status', 1)->count();
        $pending_blogs = Blog::where('status', 2)->count();
        $draft_blogs = Blog::where('status', 3)->count();

        return view('backend.blogs.index', compact(
            'blogs',
            'blog',
            'active_blogs',
            'pending_blogs',
            'draft_blogs',
        ));
    }

    public function backend_Blogs_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $query = $request->input('search');
        $blogs = Blog::where('id', 'like', '%' . $query . '%')
            ->orWhere('title', 'like', '%' . $query . '%')
            ->paginate(10);

        $blog = Blog::count();
        $active_blogs = Blog::where('status', 1)->count();
        $pending_blogs = Blog::where('status', 2)->count();
        $draft_blogs = Blog::where('status', 3)->count();

        return view('backend.blogs.index', compact(
            'blogs',
            'blog',
            'active_blogs',
            'pending_blogs',
            'draft_blogs'
        ));
    }

    public function backend_Blogs_Create()
    {
        return view('backend.blogs.create');
    }

    public function backend_Blogs_Store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'title' => ['required', 'min:1', 'max:255'],
            'sort_description' => ['min:1', 'max:200'],
            'description' => ['required', 'min:1', 'max:20000'],
            'categories' => ['required', 'min:1', 'max:255'],

        ]);

        if ($image = $request->file('image')) {
            $imgPath = 'media/blog/';
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

        $blog = Blog::create([
            'image' => $databadgeImagePath ?? null,
            'title' => $request->title ?? null,
            'slug' => Str::slug($request->title) ?? null,
            'description' => $request->description ?? null,
            'sort_description' => $request->sort_description ?? null,
            'categories' => $request->categories ?? null,
            'status' => $request->status ?? 1,

            'meta_title' => $request->title ?? null,
            'meta_keywords' => $request->description ?? null,
            'meta_description' => $request->description ?? null,
        ]);

        return redirect()->back()->with('success', 'blog Adding Has Been Successfully');
    }

    public function backend_Blogs_Edit($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            abort(404);
        }
        return view('backend.blogs.edit', compact(
            'blog',
        ));
    }

    public function backend_Blogs_Update(Request $request, Blog $blog)
    {
        try {
            $request->validate([
                // 'image' => ['required', 'min:1', 'max:255'],
                'title' => ['required', 'min:1', 'max:255'],
                'description' => ['required', 'min:1', 'max:20000'],
                'sort_description' => ['required', 'min:1', 'max:200'],
                'categories' => ['required', 'min:1', 'max:255'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);

            $input = $request->all();
            if ($image = $request->file('image')) {
                $deleteOldImage = 'media/blog/' . $blog->image;
                if (File::exists($deleteOldImage)) {
                    File::delete($deleteOldImage);
                }
                $imgPath = 'media/blog/';
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
                $existingImage = 'media/blog/' . $blog->image;
                $newImageName = Str::slug($request->title) . '-appupnext' . '.' . pathinfo($existingImage, PATHINFO_EXTENSION);
                if ($blog->image !== $newImageName && File::exists($existingImage)) {
                    File::move($existingImage, 'media/blog/' . $newImageName);
                }
                $input['image'] = $newImageName;
            }

            $dataToUpdate = [
                'image' => $proImage ?? $newImageName,
                'title' => $request->title ?? null,
                'slug' => Str::slug($request->title) ?? null,
                'description' => $request->description ?? null,
                'sort_description' => $request->sort_description ?? null,
                'categories' => $request->categories ?? null,
                'status' => $request->status ?? 0,

                'meta_title' => $request->title ?? null,
                'meta_keywords' => $request->description ?? null,
                'meta_description' => $request->description ?? null,
            ];

            $value = $blog->update($dataToUpdate);
            if (!$blog) {
                abort(404);
            }
            return redirect()->back()->with('success', 'Blog Adding Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Blogs_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Blog::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Blog Status Change Successfully');
    }

    public function backend_Blogs_Destory($id)
    {
        $blog = Blog::find($id);
        $excludedBanners = [
            "banner.png",
        ];
        if (!in_array($blog->image, $excludedBanners)) {
            $deleteOldImage = 'media/blog/' . $blog->image;
            if (File::exists($deleteOldImage)) {
                File::delete($deleteOldImage);
            }
        }
        $blog->delete();
        return redirect()->back()->with('success', 'Blog Permanently Delete Successfully');
    }
}
