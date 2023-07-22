<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login-new');
    }

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();
        // dd($credentials);
        if (!Auth::validate($credentials)) :
            // dd(trans('auth.failed'));
            return redirect()->to('login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user);

        return $this->authenticated($request, $user);
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        $user_authenticated = [
            'name' => $user->name,
            'usergroup_id' => $user->usergroup_id,
            'class_id' => $user->class_id,
            'phone' => $user->phone,
            'username' => $user->username,
            'password' => $user->password,
            'monthly_fee' => $user->monthly_fee,
            'gender' => $user->gender,
            'birth_date' => $user->birth_date,
            'join_date' => $user->join_date,
        ];
        session($user_authenticated);
        return redirect("/");
    }
}
