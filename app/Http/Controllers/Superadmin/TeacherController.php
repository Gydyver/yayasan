<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DataTables;

class TeacherController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    public function index()
    {
        //Changed into Login Auth
        return view('superadmin.teacher.index');
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            $data = User::where('usergroup_id', 2)
                ->latest()
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = "
                    <a href='/superadmin/teacher/show/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
                    ";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function show($idEncrypted)
    {
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        // Session::where('class_id', $decrypted)->get();
        // dd($decrypted);
        $class = Classes::with('sessions')->where('teacher_id', $decrypted)->latest()->get();
        // dd($class);
        return view('superadmin.teacher.session', compact('class'));
    }

    // public function getDatatableSession($id,Request $request)
    public function getDatatableSession($id, Request $request)
    {
        $decrypted = \EncryptionHelper::instance()->decrypt($id);
        // dd($request->ajax());
        if ($request->ajax()) {
            // dd('masuk if');
            //Changed into Login Auth
            // $data = User::where('teacher_id',$decrypted)
            // ->latest()
            // ->get();
            $data = Classes::with('sessions')->where('teacher_id', $decrypted)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('session_list', function ($row) {
                    if ($row->sessions) {
                        $sessions = '<ul>';
                        foreach ($row->sessions as $dt) {
                            $sessions .= '<li>' . $dt->name . '</li>';
                        }
                        $sessions .= '</ul>';
                        return $sessions;
                    } else {
                        return "-";
                    }
                })
                // ->addColumn('point_aspects', function ($data) {
                //     $point_aspects = '<ul>';
                //     foreach($data->chapterPointAspects as $dt){
                //         $point_aspects .= '<li>' . $dt->name . '</li>';
                //     }
                //     $point_aspects .= '</ul>';
                //     return  $point_aspects;
                // }) 
                // ->addColumn('action', function($row){
                //     $actionBtn = "
                //     <a href='/superadmin/teacher/show/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                //     ";
                //     return $actionBtn;
                // })
                ->rawColumns(['session_list'])
                ->make(true);
        }
    }
}
