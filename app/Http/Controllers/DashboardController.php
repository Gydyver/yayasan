<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\MenuTrait;

class DashboardController extends Controller
{
    use MenuTrait;
    public function index() 
    {
        $data = [];
        // $data['menus'] = $this->getMenus();
        // compact('data')
        $menus = $this->getMenus();
        return view('dashboard',compact('menus')); 
    }

}
