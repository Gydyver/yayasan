<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\MenuTrait;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class DashboardController extends Controller
{
    use MenuTrait;
    public function index()
    {
        if (Auth::user() ==  null) {
            return redirect()->route('login.view');
        }
        $data = [];


        $user_info = User::where('id', Auth::User()->id)->get();

        // $data['menus'] = $this->getMenus();
        // compact('data')
        $menus = $this->getMenus();
        return view('dashboard', compact('menus'));
    }
}
