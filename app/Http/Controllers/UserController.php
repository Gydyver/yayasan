<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Classes;

use DataTables;

class UserController extends Controller
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
        $user_groups = UserGroup::orderBy('id', 'asc')->paginate(10);
        $classes = Classes::orderBy('id', 'asc')->paginate(10);


        return view('master.user.index', compact('user_groups', 'classes'));
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
                ->addColumn('username_freetext', function ($data) {
                    $username = \VCHelper::instance()->vigenereDecrypt($data->username);
                    return $username;
                })
                ->addColumn('action', function ($row) {
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='" . json_encode($row) . "'>Update</button>
                    <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Delete</button>
                    ";
                    // <a href='user/show/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
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
    // public function store(Request $request)
    // {
    //     // $options = [
    //     //     'cost' => 11
    //     // ];
    //     // $password = password_hash($request->password, PASSWORD_BCRYPT, $options) . "\n";

    //     $salt = getRandomString();
    //     // $username = hash('sha256', $request->username . $salt);
    //     $password = hash('sha256', $request->password . $salt);
    //     // dd($salt);

    //     // $username = \ShaHelper::instance()->sha256WithSalt($request->username, bin2hex('test'));
    //     // $password = \ShaHelper::instance()->sha256WithSalt($request->password, bin2hex('test'));
    //     $data = [
    //         'name' => $request->name,
    //         'usergroup_id' => $request->usergroup_id,
    //         'phone' => $request->phone,
    //         'username' => $request->username,
    //         'gender' => $request->gender,
    //         'birth_date' => $request->birth_date,
    //         'join_date' => $request->join_date,
    //         'password' => $password,
    //         'monthly_fee' => $request->monthly_fee,
    //         'salt' => $salt
    //     ];
    //     // dd($data);

    //     $save = User::create($data);

    //     // redirect
    //     if ($save) {
    //         return redirect()->back()->with(["success" => "Tambah Data"]);
    //     } else {

    //         return redirect()->back()->with(["error" => " Tambah Data Failed"]);
    //     }
    // }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'usergroup_id' => $request->usergroup_id,
            'phone' => $request->phone,
            'username' => \VCHelper::instance()->VigenereEncrypt($request->username),
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'join_date' => $request->join_date,
            'password' => \VCHelper::instance()->VigenereEncrypt($request->password),
            'monthly_fee' => $request->monthly_fee,
        ];

        $save = User::create($data);

        // redirect
        if ($save) {
            // return redirect()->back()->with(["success" => "Tambah Data"]);
            return true;
        } else {

            return false;
            // return redirect()->back()->with(["error" => " Tambah Data Failed"]);
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
        // dd($request->id);
        $previous_data =  User::where('id', $request->id)->get();
        // dd($previous_data);
        if ($request->password == "") {
            $data = [
                'name' => $request->name,
                'usergroup_id' => $request->usergroup_id,
                'class_id' => $request->class_id,
                'phone' => $request->phone,
                'username' => $request->username,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'monthly_fee' => $request->monthly_fee,
                'join_date' => $request->join_date,
            ];
        } else {
            $password = hash('sha256', $request->password . $previous_data[0]->salt);
            $data = [
                'name' => $request->name,
                'usergroup_id' => $request->usergroup_id,
                'class_id' => $request->class_id,
                'phone' => $request->phone,
                'username' => \VCHelper::instance()->VigenereEncrypt($request->username),
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'join_date' => $request->join_date,
                'monthly_fee' => $request->monthly_fee,
                'password' =>  \VCHelper::instance()->VigenereEncrypt($password)
            ];
        }
        $save = User::where('id', $request->id)->update($data);
        // redirect
        if ($save) {
            return true;
        } else {
            return false;
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
