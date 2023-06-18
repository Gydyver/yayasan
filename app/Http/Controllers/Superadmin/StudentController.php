<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Session;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DataTables;

class StudentController extends Controller
{
    public function index()
    {
        //Changed into Login Auth

        $chapters = Chapter::latest()->get();
        return view('superadmin.student.index', compact('chapters'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            //Changed into Login Auth
            $data = User::with('studentClasses')
                // ->whereHas('studentClasses', function ($query) {
                //     return $query->where('teacher_id', '=',Auth::user()->id );
                // })
                ->where('usergroup_id', 3)
                ->latest()
                ->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('class_label', function ($row) {
                    return  $row->studentClasses->name;
                })
                ->addColumn('age', function ($row) {

                    $dateOfBirth = $row->birth_date;
                    $age = Carbon::parse($dateOfBirth)->age;
                    return  $age;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-success' data-toggle='modal' onclick='changeClass(this);'
                    id='btnChangeClass' data-target='#changeClass' data-item='" . json_encode($row) . "'>Change Class</button>
                    <a href='/superadmin/student/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
                    ";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function show($idEncrypted)
    {
        //
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        Session::where('class_id', $decrypted)->get();
        dd($decrypted);
    }

    public function changeChapter(Request $request)
    {
        try { } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}
