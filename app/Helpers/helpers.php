<?php
// namespace App\Repositories;
use App\Http\Traits\MenuTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAccess;
use App\Models\Menu;

// class RepositoriesClassForBlade
// {
// use MenuTrait;

//     // dd(MenuTrait->getMenus());
// }

// if(!function_exists('getMenuData')){
//     function getMenuData(){
//         // use MenuTrait;
//         $menus = $this->getMenus();
//         return $menus;
//     }

// }


if (!function_exists('getMenus')) {
    function getMenus()
    {
        // dd('masuk getMenus');
        $auth = Auth::user()->usergroup_id;
        // dd($auth);
        $permissions = UserAccess::where('usergroup_id', $auth)->latest()->get();
        $permission_ids = $permissions->map(function ($item) {
            return $item->menu_id;
        })->toArray();

        // dd($permission_ids);
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
                if (count($menu_parent) > 0) {
                    $menu = [
                        'id' => $menu_parent[0]->id,
                        'name' => $menu_parent[0]->name,
                        'icon' => $menu_parent[0]->icon,
                        'url' => $menu_parent[0]->url,
                    ];
                    $childs = array();
                    // where('menu_id', $menu_id)->
                    $menu_childs = Menu::where('menuparent_id', $menu_id)->latest()->get();
                    if ($menu_childs) {
                        foreach ($menu_childs as $menu_child) {
                            // dd($permission_ids);
                            if (in_array($menu_child->id, $permission_ids)) {

                                $child =
                                    [
                                        'id' => $menu_child->id,
                                        'name' => $menu_child->name,
                                        'icon' => $menu_child->icon,
                                        'url' => $menu_child->url,
                                    ];
                                array_push($childs, $child);
                            }
                        }
                        $menu['child'] = $childs;
                    }

                    array_push($menus, $menu);
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
        return ($menus);
    }
}

if (!function_exists('getMonthName')) {
    function getMonthName($month)
    {
        switch ($month) {
            case 1:
                return "Januari";
                break;

            case 2:
                return "Februari";
                break;

            case 3:
                return "Maret";
                break;

            case 4:
                return "April";
                break;

            case 5:
                return "Mei";
                break;

            case 6:
                return "Juni";
                break;

            case 7:
                return "Juli";
                break;

            case 8:
                return "Agustus";
                break;

            case 9:
                return "September";
                break;

            case 10:
                return "Oktober";
                break;

            case 11:
                return "November";
                break;

            case 12:
                return "Desember";
                break;

            default:
                # code...
                break;
        }
        return 'Bulan Label';
    }
}

if (!function_exists('getDayName')) {
    function getDayName($day)
    {
        switch ($day) {
            case 1:
                return "Monday";
                break;

            case 2:
                return "Tuesday";
                break;

            case 3:
                return "Wednesday";
                break;

            case 4:
                return "Thrusday";
                break;

            case 5:
                return "Friday";
                break;

            case 6:
                return "Saturday";
                break;

            case 7:
                return "Sunday";
                break;

            default:
                # code...
                break;
        }
        return 'Hari Label';
    }
}
