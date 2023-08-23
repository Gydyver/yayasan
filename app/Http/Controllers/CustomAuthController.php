<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class CustomAuthController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    // https://www.positronx.io/laravel-custom-authentication-login-and-registration-tutorial/
    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request)
    {
        // $request->validate([
        //     'username' => 'required',
        //     'password' => 'required',
        // ]);

        $credentials = $request->only('username', 'password');
        // dd($request->username, $request->password);
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            // return redirect()->intended('dashboard')
            //             ->withSuccess('Signed in');
            // dd('masuk if');
            return view('dashboard');
            // return redirect("dashboard")->withSuccess('You have signed-in');
        } else {
            dd('masuk else');
        }

        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function customRegistration(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     // 'usergroup_id' => 'required',
        //     'username' => 'required|unique:users',
        //     'password' => 'required|min:6',
        //     'monthly_fee' => 'required',
        //     'gender' => 'required',
        //     'birth_date' => 'required'
        // ]);

        $data = $request->all();
        // dd($data);
        $check = $this->create($data);

        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'usergroup_id' => 3,
            'monthly_fee' => $data['monthly_fee'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function dashboard()
    {
        // dd(Auth::check());
        // if(Auth::check()){
        return view('dashboard');
        // }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut()
    {
        dd(Session::all());
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
