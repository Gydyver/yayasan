<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Session;

use DataTables;
class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Classes::orderBy('id','desc')->get();
        $days = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thrusday",
            "Friday",
            "Saturday",
            "Sunday"
        ];
        return view('master.session.index', compact('classes','days'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Session::with('classes')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('class_label', function ($data) {
                    return  $data->classes->name;
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
                    <a href='session/show/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
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
        // return view('class.list');
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
            'class_id' => $request->class_id,
            'day' => $request->day,
            'time' => $request->time,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d')
        ];
        // dd($data);

        $save = Session::insert($data);

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
        //
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
        $data = [
            'name' => $request->name,
            'class_id' => $request->class_id,
            'day' => $request->day,
            'time' => $request->time,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d')
        ];

        $save = Session::where('id', $request->id)->update($data);

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
         // delete
        $id = \EncryptionHelper::instance()->decrypt($idEncrypted);
         $class = Session::find($id);
         $class->delete();
         return redirect()->route('session.index')->with('success','Class has been deleted successfully');
 
         // redirect
        //  Session::flash('message', 'Successfully deleted the shark!');
        //  return Redirect::to('sharks');
    }
}