<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Class_Type;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use DataTables;

class ClassTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $class_types = Class_Type::orderBy('id', 'desc')->paginate(10);
        // dd($class_types);
        return view('class_type.index', compact('class_types'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Class_Type::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // dd($row);
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
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
            'name' => $request->name
        ];

        $save = Class_Type::insert($data);

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
            'name' => $request->name
        ];

        $save = Class_Type::where('id', $request->id)->update($data);

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
        $delete = Class_Type::where('id', $id)->Delete(); 
        // redirect
        if ($delete) {
            return redirect()->back()->with(["success" => "Delete Data"]);
        } else {
            return redirect()->back()->with(["error" => " Delete Data"]);
        }
    }
}
