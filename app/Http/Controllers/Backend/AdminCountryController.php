<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCountryController extends Controller
{
    public function backend_Country_Index()
    {
        $countries = Country::paginate(20);
        $country = Country::count();
        $active_countries = Country::where('status', 1)->count();
        $pending_countries = Country::where('status', 2)->count();
        $draft_countries = Country::where('status', 0)->count();

        return view('backend.country.index', compact(
            'countries',
            'country',
            'active_countries',
            'pending_countries',
            'draft_countries',
        ));
    }

    public function backend_Country_Active()
    {
        $countries = Country::where('status', 1)->paginate(20);
        $country = Country::count();
        $active_countries = Country::where('status', 1)->count();
        $pending_countries = Country::where('status', 2)->count();
        $draft_countries = Country::where('status', 0)->count();

        return view('backend.country.index', compact(
            'countries',
            'country',
            'active_countries',
            'pending_countries',
            'draft_countries',
        ));
    }

    public function backend_Country_Pending()
    {
        $countries = Country::where('status', 2)->paginate(20);
        $country = Country::count();
        $active_countries = Country::where('status', 1)->count();
        $pending_countries = Country::where('status', 2)->count();
        $draft_countries = Country::where('status', 0)->count();

        return view('backend.country.index', compact(
            'countries',
            'country',
            'active_countries',
            'pending_countries',
            'draft_countries',
        ));
    }

    public function backend_Country_Drafts()
    {
        $countries = Country::where('status', 0)->paginate(20);
        $country = Country::count();
        $active_countries = Country::where('status', 1)->count();
        $pending_countries = Country::where('status', 2)->count();
        $draft_countries = Country::where('status', 0)->count();

        return view('backend.country.index', compact(
            'countries',
            'country',
            'active_countries',
            'pending_countries',
            'draft_countries',
        ));
    }

    public function backend_Country_Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);
        $query = $request->input('search');
        $countries = Country::where('id', 'like', '%' . $query . '%')
            ->orWhere('short_name', 'like', '%' . $query . '%')
            ->orWhere('country_name', 'like', '%' . $query . '%')
            ->orWhere('phonecode', 'like', '%' . $query . '%')
            ->paginate(20);

        $country = Country::count();
        $active_countries = Country::where('status', 1)->count();
        $pending_countries = Country::where('status', 2)->count();
        $draft_countries = Country::where('status', 0)->count();

        return view('backend.country.index', compact(
            'countries',
            'country',
            'active_countries',
            'pending_countries',
            'draft_countries',
        ));
    }

    public function backend_Country_Create()
    {
        return view('backend.country.create');
    }

    public function backend_Country_Store(Request $request)
    {
        $request->validate([
            'short_name' => ['required', 'min:1', 'max:255'],
            'country_name' => ['required', 'min:1', 'max:255'],
            // 'flag' => ['required', 'min:1', 'max:255'],
            'phonecode' => ['required', 'min:1', 'max:255'],
            // 'continent' => ['required', 'min:1', 'max:255'],
            'status' => ['required', 'min:1', 'max:255'],
        ]);

        $country = Country::create([
            'country_name' => $request->country_name ?? null,
            'slug' => Str::slug($request->country_name) ?? null,
            'short_name' => $request->short_name ?? null,
            'flag' => $request->flag ?? null,
            'phonecode' => $request->phonecode ?? null,
            'continent' => $request->continent ?? null,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->back()->with('success', 'Country Adding Has Been Successfully');
    }

    public function backend_Country_Edit($id)
    {
        $country = Country::find($id);
        return view('backend.country.edit', compact(
            'country',
        ));
    }

    public function backend_Country_Update(Request $request, Country $country)
    {
        try {
            $request->validate([
                'short_name' => ['required', 'min:1', 'max:255'],
                'country_name' => ['required', 'min:1', 'max:255'],
                // 'flag' => ['required', 'min:1', 'max:255'],
                'phonecode' => ['required', 'min:1', 'max:255'],
                // 'continent' => ['required', 'min:1', 'max:255'],
                'status' => ['required', 'min:1', 'max:255'],
            ]);

            $dataToUpdate = [
                'country_name' => $request->country_name ?? null,
                'slug' => Str::slug($request->country_name) ?? null,
                'short_name' => $request->short_name ?? null,
                'flag' => $request->flag ?? null,
                'phonecode' => $request->phonecode ?? null,
                'continent' => $request->continent ?? null,
                'status' => $request->status ?? 1,
            ];

            $value = $country->update($dataToUpdate);
            return redirect()->back()->with('success', 'Country Adding Has Been Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function backend_Country_Status(Request $request, $id)
    {
        $status = $request->input('status');
        # Perform validation to ensure $status is a valid value (e.g., 1, 2, or 3)
        Country::where('id', $id)->update([
            'status' => $status,
        ]);
        return redirect()->back()->with('success', 'Country Status Change Successfully');
    }

    public function backend_Country_Destory($id)
    {
        $country = Country::find($id);
        $country->delete();
        return redirect()->back()->with('success', 'Country Permanently Delete Successfully');
    }
}
