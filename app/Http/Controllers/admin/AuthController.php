<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login View 
     */
    public function index()
    {
        return view('admin.login');
    }

    /**
     * 
     */
    public function login(Request $request)
    {
        $userdata = [];
        $userdata['email'] = $request->post('email');
        $userdata['password'] = $request->post('password');
        $response = [];
        $response['status'] = 0;

        if (Auth::attempt($userdata)) {
            $response['status'] = 1;
        }
        return response()->json($response);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    public function register()
    {
        return view('admin.register');
    }

    public function registeruser(Request $request)
    {
        $response = [];
        $response['status'] = 0;
        $response['is_exist'] = 'no';
        $email = $request->post('email');
        $password = $request->post('password');
        $cpassword = $request->post('confirm-password');

        // $validate = $request->validate([
        //     'email' => 'required|email|email:rfc,filter',
        //     'password' => 'required|min:8|max:30',
        //     'confirm-password' => 'required|min:8|max:30',
        // ]);

        // if (!$validate) {
        //     return redirect()->back()->with('error', 'Enter valid data');
        // }

        if ($password != $cpassword) {
            return redirect()->back()->with('error', 'Passwords do not match');
        }

        $userobj = new User();

        $is_exist = $userobj->is_email_exists($email);

        if ($is_exist) {
            $response['status'] = 0;
            $response['is_exist'] = 'yes';
        } else {
            $userobj->email = $email;
            $userobj->password = Hash::make($password);
            $userobj->save();

            $response['status'] = 1;
        }
        return response()->json($response);
    }
}
