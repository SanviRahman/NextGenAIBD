<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSEOController extends Controller
{
    public function backend_Seo_Index()
    {
        $seo = Seo::first();
        return view('backend.seo.index', compact(
            'seo',
        ));
    }

    public function backend_Seo_Edit()
    {
        $seo = Seo::first();
        if (!$seo) {
            abort(404);
        }
        return view('backend.seo.edit', compact(
            'seo',
        ));
    }

    public function backend_Seo_Update(Request $request)
    {
        try {
            $request->validate([
                'title' => ['required', 'min:1', 'max:20000'],
                // 'slug' => ['required', 'min:1', 'max:20000'],
                'description' => ['required', 'min:1', 'max:20000'],
                'keywords' => ['required', 'min:1', 'max:20000'],
                // 'status' => ['required', 'min:1', 'max:255'],
            ]);
            $seo = Seo::first();

            $seo->update([
                'title' => $request->title ?? null,
                'slug' => Str::slug($request->title) ?? null,
                'description' => $request->description ?? null,
                'keywords' => $request->keywords ?? null,
                'status' => $request->status ?? 1,
            ]);

            return redirect()->back()->with('success', 'SEO Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Seo_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Seo::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Service Status Change Successfully');
    }

    public function backend_Seo_Destory($id)
    {

        $service = Seo::find($id);
        $service->delete();
        return redirect()->back()->with('success', 'Service Permanently Delete Successfully');
    }
}
