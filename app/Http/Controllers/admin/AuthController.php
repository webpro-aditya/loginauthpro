<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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
        $email = $request->post('email');
        $password = $request->post('password');
    }

    public function register()
    {
        return view('admin.register');
    }

    public function registeruser(Request $request)
    {
            $response = [];
            $response['status'] = 0;
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

            $userobj->email = $email;
            $userobj->password = Hash::make($password);
            $data = $userobj->save();

            $response['status'] = 1;
            return response()->json($response);
    }
}
