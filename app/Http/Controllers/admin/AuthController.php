<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Socialite;

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
     * Google Login View
     */
    public function google_login()
    {
        // redirect user to "login with Google account" page
        return Socialite::driver('google')->redirect();
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

    /**
     * Google Auth Callback
     */
    public function googleAuthCallback()
    {
        try {
            // get user data from Google
            $user = Socialite::driver('google')->user();

            // find user in the database where the social id is the same with the id provided by Google
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser)  // if user found then do this
            {
                // Log the user in
                Auth::login($finduser);

                // redirect user to dashboard page
                return redirect()->route('dashboard');
            }
            else
            {
                // if user not found then this is the first time he/she try to login with Google account
                // create user data with their Google account data
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => Hash::make($user->email .'.'. $user->id),  // fill password by whatever pattern you choose
                ]);

                Auth::login($newUser);

                return redirect()->route('dashboard');
            }

        }
        catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
