<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DataTables;
class StudentController extends Controller
{
    public function index()
    {
        //Changed into Login Auth
        return view('teacher.student.index');
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            $data = User::with('studentClasses')
            ->whereHas('studentClasses', function ($query) {
                return $query->where('teacher_id', '=',Auth::user()->id );
            })
            ->where('usergroup_id',3)
            ->latest()
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('class_label', function($row){
                    return  $row->studentClasses->name;
                })
                ->addColumn('age', function($row){
                    $dateOfBirth = $row->birth_date;
                    $age = Carbon::parse($dateOfBirth)->age;
                    return  $age;
                })
                ->addColumn('action', function($row){
                    $actionBtn = "
                    <a href='/teacher/class/session/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
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
        Session::where('class_id', $decrypted)->get();
        dd($decrypted);
    }

    public function showSession($idEncrypted)
    {
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        Session::where('class_id', $decrypted)->get();

        return view('teacher.class.classSession');
    }

    public function getDatatableSession(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            $data = Session::with('classes')->where('classes.teacher_id',5)->latest()->get();
            dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = "
                    <a href='/teacher/class/session/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    ";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

}
