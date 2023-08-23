<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Point_Aspect;
use App\Models\Chapter_Point_Aspect;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use DataTables;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        session_start();
    }

    public function index()
    {
        $chapters = Chapter::orderBy('id', 'desc')->paginate(10);
        $point_aspects = Point_Aspect::get();
        // dd($point_aspects);
        return view('master.chapter.index', compact('chapters', 'point_aspects'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Chapter::with('chapterPointAspects')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('point_aspects', function ($data) {
                    $point_aspects = '<ul>';
                    foreach ($data->chapterPointAspects as $dt) {
                        $point_aspects .= '<li>' . $dt->name . '</li>';
                    }
                    $point_aspects .= '</ul>';
                    return  $point_aspects;
                })
                ->addColumn('action', function ($row) {
                    // dd(json_encode($row->name));
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='" . json_encode($row) . "'>Update</button>
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='addPointAspect(this)'
                    id='btnEdit' data-target='#ModalAddPointAspect' data-item='" . json_encode($row) . "'>Add Point Aspect</button>
                    <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Delete</button>
                    ";
                    return $actionBtn;
                })
                ->rawColumns(['action', 'point_aspects'])
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
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d')
        ];

        $save = Chapter::insert($data);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Tambah Data"]);
        } else {

            return redirect()->back()->with(["error" => " Tambah Data Failed"]);
        }
    }

    public function addPointAspect(Request $request)
    {
        $data = [
            'chapter_id' => $request->id,
            'point_aspect_id' => $request->point_aspect_id,
        ];
        // dd($data);

        $save = Chapter_Point_Aspect::insert($data);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Tambah Data Point Aspect ke chapter"]);
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
            'name' => $request->name,
            'updated_at' => date('Y-m-d')
        ];

        $save = Chapter::where('id', $request->id)->update($data);

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
        $delete = Chapter::where('id', $id)->Delete();
        // redirect
        if ($delete) {
            return redirect()->back()->with(["success" => "Delete Data"]);
        } else {
            return redirect()->back()->with(["error" => " Delete Data"]);
        }
    }
}
