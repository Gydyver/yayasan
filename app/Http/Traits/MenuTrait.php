<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAccess;
use App\Models\Menu;

use Illuminate\Http\Request;

trait MenuTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function getMenus() {
        $auth = Auth::user()->usergroup_id;
        $permissions = UserAccess::where('usergroup_id', $auth)->latest()->get();
        $menus = array();
        $array_test = array();
        if ($permissions) {
            // dd($permissions);
            foreach ($permissions as $permission) {
                // dd($permission->menu_id);
                $menu_id = $permission->menu_id;
                $menu_parent = Menu::where('id', $menu_id)->whereNull('menuparent_id')->latest()->get();
                // dd(count($menu_parent));
                array_push($array_test, count($menu_parent));
                
                // dd($menu_parent[0]->id);
                if(count($menu_parent) > 0){
                    $menu = [
                        'id' =>$menu_parent[0]->id,
                        'name' =>$menu_parent[0]->name,
                        'icon' =>$menu_parent[0]->icon,
                        'url' =>$menu_parent[0]->url,
                    ];
                    $childs = array();
                    // where('menu_id', $menu_id)->
                    $menu_childs = Menu::where('menuparent_id', $menu_id)->latest()->get();
                    if($menu_childs){
                        foreach ($menu_childs as $menu_child) {
                            $child = 
                            [
                                'id' =>$menu_child->id,
                                'name' =>$menu_child->name,
                                'icon' =>$menu_child->icon,
                                'url' =>$menu_child->url,
                            ];
                            array_push($childs, $child);
                        }
                        $menu['child'] = $childs;
                    }

                    array_push($menus,$menu);
                }
                
                // if($menu_get->menuparent_id != null){
                //     $menu_get = Menu::where('menu_id', $menu_id)->latest()->get();
                // }

                // $menus.
              }

              
            // foreach($permissions as $permission){
            //     $permission
            //     $menu_list = Menu::where('usergroup_id', $auth)->latest()->get();
            // }
            // $menus[] = [
            //     'id'       => 'menu-system',
            //     'icon'     => 'fas fa-tag',
            //     'name'     => 'System',
            //     'href'     => '',
            //     'children' => $system
            // ];
        }
        // dd($array_test);
        return $menus;

    }

}