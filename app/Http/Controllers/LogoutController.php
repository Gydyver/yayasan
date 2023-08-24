<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function __construct()
    {
        session_start();
        \SessionCheckingHelper::instance()->checkAuthenticated();
    }

    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function perform()
    {
        unset($_SESSION['data']);
        session_destroy();
        // Session::flush();

        // Auth::logout();

        return redirect('login');
    }
}
