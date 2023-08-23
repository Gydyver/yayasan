<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\Menu;
use App\Models\UserAccess;
use DataTables;

class UserAccessController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::orderBy('id', 'asc')->get();
        $usergroups = UserGroup::latest()->get();

        $menusWithChildren = Menu::with('children')->whereNull('menuparent_id')->orderBy('name', 'asc')->get();
        // dd($menusWithChildren);
        return view('master.user_access.index', compact('menus', 'usergroups', 'menusWithChildren'));
    }

    // function settingMenuandChild($usergroup_id == null){
    //     $menus = Menu::orderBy('id', 'asc')->get();

    //     $menus = Menu:whereNull('menuparent_id');
    // }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = UserGroup::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd(json_encode($row->name));
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='" . json_encode($row) . "'>Update</button>
                    <button class='btn btn-sm btn-icon btn-danger'  onclick='confirmData(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Delete</button>
                    ";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // dd($id);
        $access_existing = UserAccess::where('usergroup_id', $id)->get();
        // dd($access_existing);
        return  json_encode($access_existing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd('masuk yupdate');
        $menu_set = [];

        $delete = UserAccess::where('usergroup_id',  $request->usergroup_id)->Delete();
        // $data = UserAccess::where()
        // dd($delete);
        if (count($request->access_menu_id_) > 0) {

            foreach ($request->access_menu_id_ as $key => $value) {
                $data = [
                    'usergroup_id' => $request->usergroup_id,
                    'menu_id' => $key,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                array_push($menu_set, $data);
            }
        }

        // Bulking data
        // $data = [
        //     ['name'=>'Coder 1', 'rep'=>'4096'],
        //     ['name'=>'Coder 2', 'rep'=>'2048'],
        //     //...
        // ];


        $save = UserAccess::insert($menu_set);
        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Update Data"]);
        } else {

            return redirect()->back()->with(["error" => " Update Data Failed"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idEncrypted)
    {
        // dd('masuk delete',$id);
        // dd($id);
        $id = \EncryptionHelper::instance()->decrypt($idEncrypted);
        $delete = UserAccess::where('id', $id)->Delete();
        // redirect
        if ($delete) {
            return redirect()->back()->with(["success" => "Delete Data"]);
        } else {
            return redirect()->back()->with(["error" => " Delete Data"]);
        }
    }
}
