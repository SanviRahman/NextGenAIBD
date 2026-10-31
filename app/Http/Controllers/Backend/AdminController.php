<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function admin_Login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validatedData = $request->validate([
                'login' => ['required'], // Assuming you have an input named 'login' for email/username
                'password' => ['required'],
            ]);

            $loginField = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $credentials = [
                $loginField => $data['login'],
                'password' => $data['password'],
                'status' => 1,
            ];

            if (Auth::guard('admin')->attempt($credentials)) {
                return redirect()->route('backend_Home_Index');
            } else {
                return redirect()->back()->with('error_message', 'Invalid Email/Username or Password');
            }
        }

        // if ($request->isMethod('post')) {
        //     $data = $request->all();

        //     $validatedData = $request->validate([
        //         'email' => ['required'],
        //         'password' => ['required'],
        //     ]);

        //     if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])) {
        //         return redirect()->route('backend_Home_Index');
        //     } else {
        //         return redirect()->back()->with('error_message', 'Invalid Email or Password');
        //     }
        // }
        return view('backend.auth.login');
    }

    public function admin_Register()
    {
        $countries = Country::where('status', 1)->get();
        return view('backend.auth.register', compact(
            'countries'
        ));
    }

    public function admin_Register_DB(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'country' => $request->country,
            'phonecode' => $request->phonecode,
            'mobile' => $request->mobile,
            'email' => $request['email'],
            'type' => $request->type ?? "superadmin",
            'status' => $request->status ?? 0,
            'password_confirmation' => $request->password_confirmation ?? null,
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->back()->with('success', 'Registerd Successfully');
    }

    public function admin_Logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_Login');
    }
}
