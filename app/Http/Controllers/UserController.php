<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Classes;

use DataTables;

class UserController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_groups = UserGroup::orderBy('id', 'asc')->paginate(10);
        $classes = Classes::orderBy('id', 'asc')->paginate(10);

        
        return view('master.user.index', compact('user_groups','classes'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('usergroups')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('usergroup_label', function ($data) {
                    return  $data->usergroups->name;
                }) 
                ->addColumn('action', function($row){
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    <a href='user/show/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"".\EncryptionHelper::instance()->encrypt($row->id) ."\")'>Delete</button>
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
            'name' => $request->name,
            'usergroup_id' => $request->usergroup_id,
            'phone' => $request->phone,
            'username' => $request->username,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'join_date'=> $request->join_date,
            'password' => $request->password,
            'monthly_fee' => $request->monthly_fee
        ];
        // dd($data);

        $save = User::create($data);

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
    public function show($idEncrypted)
    {
        // $decrypted = Crypt::decryptString($idEncrypted);
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        
        dd($decrypted);
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
        if($request->password == ""){
            $data = [
                'name' => $request->name,
                'usergroup_id' => $request->usergroup_id,
                'class_id' => $request->class_id,
                'phone' => $request->phone,
                'username' => $request->username,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'join_date'=> $request->join_date,
            ];
        }else {
            $data = [
                'name' => $request->name,
                'usergroup_id' => $request->usergroup_id,
                'class_id' => $request->class_id,
                'phone' => $request->phone,
                'username' => $request->username,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'join_date'=> $request->join_date,
                'password' =>  bcrypt($request->password)
            ];
        }

        $save = User::where('id', $request->id)->update($data);
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
        $delete = User::where('id', $id)->Delete(); 
        // redirect
        if ($delete) {
            return redirect()->back()->with(["success" => "Delete Data"]);
        } else {
            return redirect()->back()->with(["error" => " Delete Data"]);
        }
    }
}
