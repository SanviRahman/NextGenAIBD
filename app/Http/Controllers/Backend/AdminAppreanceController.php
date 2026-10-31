<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminAppreanceController extends Controller
{
    public function backend_Settings_Logo()
    {
        $logo = Logo::first();
        // dd($logo);
        return view('backend.theme-option.logo', compact(
            'logo',
        ));
    }

    public function backend_Settings_Logo_Update(Request $request)
    {
        try {
            $logo = Logo::first();
            $input = $request->all();

            if ($image = $request->file('favicon')) {
                if ($logo->favicon != "favicone.png") {
                    $deleteOldImage = 'media/logo/' . $logo->favicon;
                    if (File::exists($deleteOldImage)) {
                        File::delete($deleteOldImage);
                    }
                }
                $imgPath = 'media/logo/';
                $proImage = date('YmdHis') . "-favicon-appupnext." . $image->getClientOriginalExtension();
                $image->move($imgPath, $proImage);
                $input['favicon'] = "$proImage";
            } else {
                unset($input['favicon']);
            }

            if ($image = $request->file('frontlogo')) {
                if ($logo->frontlogo != "frontlogo.png") {
                    $deleteOldImage = 'media/logo/' . $logo->frontlogo;
                    if (File::exists($deleteOldImage)) {
                        File::delete($deleteOldImage);
                    }
                }
                $imgPath = 'media/logo/';
                $proImage = date('YmdHis') . "-frontlogo-appupnext." . $image->getClientOriginalExtension();
                $image->move($imgPath, $proImage);
                $input['frontlogo'] = "$proImage";
            } else {
                unset($input['frontlogo']);
            }

            if ($image = $request->file('backlogo')) {
                if ($logo->backlogo != "backlogo.png") {
                    $deleteOldImage = 'media/logo/' . $logo->backlogo;
                    if (File::exists($deleteOldImage)) {
                        File::delete($deleteOldImage);
                    }
                }
                $imgPath = 'media/logo/';
                $proImage = date('YmdHis') . "-backlogo-appupnext." . $image->getClientOriginalExtension();
                $image->move($imgPath, $proImage);
                $input['backlogo'] = "$proImage";
            } else {
                unset($input['backlogo']);
            }

            $logo->update($input);
            return redirect()->back()->with('success', 'logo Update Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
