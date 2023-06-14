<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\MenuTrait;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use MenuTrait;
    public function index() 
    {
        if(Auth::user()==  null){
            return redirect()->route('login.view');
        }
        $data = [];
        // $data['menus'] = $this->getMenus();
        // compact('data')
        $menus = $this->getMenus();
        return view('dashboard',compact('menus')); 
    }

}
