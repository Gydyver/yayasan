<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\UserAccess;
use DataTables;

class UserAccessController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = UserAccess::orderBy('id', 'asc')->paginate(10);

        
        return view('master.user_access.index', compact('menus'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = UserGroup::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // dd(json_encode($row->name));
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    <button class='btn btn-sm btn-icon btn-danger'  onclick='confirmData(\"".\EncryptionHelper::instance()->encrypt($row->id) ."\")'>Delete</button>
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'usergroup_id' => $request->usergroup_id,
            'menu_id' => $request->menu_id,
        ];

        // Bulking data
        // $data = [
        //     ['name'=>'Coder 1', 'rep'=>'4096'],
        //     ['name'=>'Coder 2', 'rep'=>'2048'],
        //     //...
        // ];


        $save = UserAccess::insert($data);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Tambah Data"]);
        } else {

            return redirect()->back()->with(["error" => " Tambah Data Failed"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        // dd($request->all());
        $data = [
            'usergroup_id' => $request->name,
            'menu_id' => $request->menuparent_id,
            'url' => $request->url,
            'icon' => $request->icon
        ];

        $save = UserAccess::where('id', $request->id)->update($data);

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
