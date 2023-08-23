<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classes;
use App\Models\Session;
use App\Models\Chapter_Point_Aspect;
use App\Models\Point_History;
use App\Models\Session_Generated;
use App\Models\Grade;
use App\Models\Point_Aspect;

use Illuminate\Support\Facades\Auth;

use DataTables;

class ClassController extends Controller
{
    public function __construct()
    {
        session_start();
    }

    public function index()
    {
        //Changed into Login Auth
        if (\SessionCheckingHelper::instance()->checkSuperadmin($_SESSION["data"]->usergroup_id)) {
            //Superadmin
            $permission = true;
        } else if (\SessionCheckingHelper::instance()->checkTeacher($_SESSION["data"]->usergroup_id)) {
            //Teacher
            $permission = true;
        } else if (\SessionCheckingHelper::instance()->checkStudent($_SESSION["data"]->usergroup_id)) {
            //Student
            $permission = false;
        }

        if ($permission) {
            return view('teacher.class.index');
        } else {
            return redirect()->route('notAllowed');
        }
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            // $data = Classes::with('teachers')->with('chapters')->with('classTypes')->where('teacher_id',2)->latest()->get();
            $data = Classes::where('teacher_id', $_SESSION["data"]->id)->latest()->get();
            // dd(Classes::latest()->get());
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";
                    $actionBtn = "
                    <a href='/teacher/class/session/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
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
    }


    public function updateStatusClass($idEncrypted)
    {
        //
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);


        $data = [
            'closed' => true,
            'class_end' => date("Y-m-d")
        ];
        Classes::where('id', $decrypted)->update($data);

        return redirect()->route('class.index')->with('success', 'Class has been deleted successfully');
    }

    public function showSession($idEncrypted)
    {
        //User Teacher
        //Menu Kelas Detail(isinya list session dari sebuah kelas)
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);

        $session = Session::where('class_id', $decrypted)->get();
        $classes = Classes::where('id', $decrypted)->get();
        //Pengecekan Session Data
        if (\SessionCheckingHelper::instance()->checkSuperadmin($_SESSION["data"]->usergroup_id)) {
            //Superadmin
            $permission = true;
        } else if (\SessionCheckingHelper::instance()->checkTeacher($_SESSION["data"]->usergroup_id)) {
            //Teacher
            $permission = \SessionCheckingHelper::instance()->checkSession($_SESSION["data"]->usergroup_id, $_SESSION["data"]->id, $classes[0]->teacher_id);
        } else if (\SessionCheckingHelper::instance()->checkStudent($_SESSION["data"]->usergroup_id)) {
            //Student
            $permission = false;
        } else {
            $permission = false;
        }

        if ($permission) {
            return view('teacher.class.classSession');
        } else {
            return redirect()->route('notAllowed');
        }
    }

    public function getDatatableSession($id, Request $request)
    {
        // dd('ini?');
        if ($request->ajax()) {
            //Changed into Login Auth
            $decrypted = \EncryptionHelper::instance()->decrypt($id);
            $data = Session::with('sessionGenerated')->where('class_id', $decrypted)->latest()->get();
            // dd($data[0]->sessionGenerated);
            // dd($decrypted);
            return Datatables::of($data[0]->sessionGenerated)
                ->addIndexColumn()
                ->addColumn('day', function ($row) use ($data) {
                    // if ($data[0]->day == 0) {
                    return $data[0]->day;
                    // } else {
                    //     return '';
                    // }
                })
                ->addColumn('status', function ($row) use ($data) {
                    if ($row->status == 0) {
                        $status = 'Terbuka';
                    } else if ($row->status == 1) {
                        $status = 'Selesai';
                    } else {
                        $status = 'Dibatalkan';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) use ($data) {
                    $actionBtn = "
                    <a href='/teacher/class/" . \EncryptionHelper::instance()->encrypt($data[0]->class_id) . "/session/point/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
                    ";
                    return $actionBtn;
                })

                // ->addColumn('action', function($user)use($pes) {
                //     return ' link with 2 paramter';
                //     })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function updateStatusSession()
    { }

    public function showSessionStudent($idEncryptedClass, $idEncrypted)
    {
        $decryptedClass = \EncryptionHelper::instance()->decrypt($idEncryptedClass);
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);


        $students = User::where('usergroup_id', 3)->where('class_id', $decryptedClass)->latest()->get();
        $classes = Classes::where('id', $decryptedClass)->get();
        $class_info = $classes[0];
        $student_info = $students[0];

        //Pengecekan Session Data
        if (\SessionCheckingHelper::instance()->checkSuperadmin($_SESSION["data"]->usergroup_id)) {
            //Superadmin
            $permission = true;
        } else if (\SessionCheckingHelper::instance()->checkTeacher($_SESSION["data"]->usergroup_id)) {
            //Teacher
            $permission = \SessionCheckingHelper::instance()->checkSession($_SESSION["data"]->usergroup_id, $_SESSION["data"]->id, $classes[0]->teacher_id);
        } else if (\SessionCheckingHelper::instance()->checkStudent($_SESSION["data"]->usergroup_id)) {
            //Student
            $permission = false;
        } else {
            $permission = false;
        }

        if ($permission) {
            if ($student_info != null) {
                // $point_aspects = Chapter_Point_Aspect::with('pointAspects')->where('chapter_id', $student_info->chapter_id)->latest()->get();
                return view('teacher.class.classSessionStudent', compact('students', 'class_info', 'decrypted'));
            } else {
                return view('teacher.class.classSessionStudent', ['errorMessage' => 'Failed showing data because user chapter is not set yet']);
            }
        } else {
            return redirect()->route('notAllowed');
        }
    }



    public function getDatatableSessionPointHistory($idSession, Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            $decrypted = \EncryptionHelper::instance()->decrypt($idSession);
            // $data = User::with('studentPointHistory')->where('.session_id',$decrypted)->latest()->get();
            $data = User::with(['studentPointHistory' => function ($query) use ($decrypted) {
                $query->where('session_generated_id', $decrypted);
            }])
                // with('studentPointHistory')
                ->whereHas('studentPointHistory', function ($query) use ($decrypted) {
                    $query->where('session_generated_id', '=', $decrypted);
                })
                ->latest()
                ->get();
            // ->toSql();


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('point_history', function ($data) {
                    // dd($data);

                    $point_history = '<ul>';
                    foreach ($data->studentPointHistory as $dt) {
                        $point_aspect = Point_Aspect::where('id', $dt->chapter_point_aspect_id)->get();
                        $point_history .= '<li>' . $point_aspect[0]->name . ' : ' . $dt->point . ' (' . $dt->teacher_notes . ')' . '</li>';
                    }
                    $point_history .= '</ul>';
                    return  $point_history;
                })
                // ->addColumn('action', function($row){
                //     $actionBtn = "
                //     <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                //     id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                //     ";
                //     // <a href='/teacher/class/session/student/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Edit</a>
                //     return $actionBtn;
                // })
                ->rawColumns(['point_history'])
                ->make(true);
        }
    }

    public function createHistoryPoint(Request $request)
    {
        // dd($request);
        $points = [];

        $updateUser = User::where('id', $request->student_id)->update(['latest_hapalan' => $request->latest_hapalan, 'latest_halaman' => $request->latest_halaman]);

        foreach ($request->point_aspect_id as $key => $point_aspect) {
            // dd($request->point[$key]);
            // $request->point[$key] = 90;
            $grade = Grade::where('highest_poin', ">=", $request->point[$key])->where('lowest_poin', "<=", $request->point[$key])->get();
            // ->toSql();
            // dd($grade);
            $point = [
                'student_id' => $request->student_id,
                'session_generated_id' => $request->session_generated_id,
                'chapter_point_aspect_id' => $point_aspect,
                'grade_id' => $grade[0]->id,
                'point' => $request->point[$key],
                'teacher_notes' => $request->notes[$key],
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d'),
            ];

            array_push($points, $point);
        }


        // dd($points);

        $save = Point_History::insert($points);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Tambah Data"]);
        } else {

            return redirect()->back()->with(["error" => " Tambah Data Failed"]);
        }
    }

    public function getPointAspectStudent($student_id)
    {
        $user = User::where('id', $student_id)->get();

        $chapter_id = $user[0]->chapter_id;
        $chapter_point_aspect = Chapter_Point_Aspect::where('chapter_id', $chapter_id)->with('pointAspects')->get();
        $point_aspects = [];

        foreach ($chapter_point_aspect as $cpa) {
            array_push($point_aspects, ['id' => $cpa->pointAspects->id, 'name' => $cpa->pointAspects->name]);
        }

        return $point_aspects;
    }
}
