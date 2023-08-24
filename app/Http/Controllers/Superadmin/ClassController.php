<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DataTables;

class ClassController extends Controller
{
    public function __construct()
    {
        session_start();
        \SessionCheckingHelper::instance()->checkAuthenticated();
    }

    public function index()
    {
        //Changed into Login Auth
        return view('superadmin.class.index');
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            //Changed into Login Auth
            $data = Classes::with('studentClasses')
                // ->whereHas('studentClasses', function ($query) {
                //     return $query->where('teacher_id', '=',$_SESSION["data"]->id );
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
}
