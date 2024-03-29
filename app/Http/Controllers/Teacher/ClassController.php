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
    public function index()
    {
        //Changed into Login Auth
        return view('teacher.class.index');
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            // $data = Classes::with('teachers')->with('chapters')->with('classTypes')->where('teacher_id',2)->latest()->get();
          
            $data = Classes::where('teacher_id',Auth::user()->id)->latest()->get();
            // dd(Classes::latest()->get());
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";
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
        //
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        Session::where('class_id', $decrypted)->get();
        dd($decrypted);
    }

    public function showSession($idEncrypted)
    {
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
     
        return view('teacher.class.classSession');
    }

    public function getDatatableSession($id,Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            $decrypted = \EncryptionHelper::instance()->decrypt($id);
            $data = Session::with('sessionGenerated')->where('class_id',$decrypted)->latest()->get();
            return Datatables::of($data[0]->sessionGenerated)
                ->addIndexColumn()
                ->addColumn('action', function($row)use($data){
                    $actionBtn = "
                    <a href='/teacher/class/".\EncryptionHelper::instance()->encrypt($data[0]->class_id)."/session/point/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
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

    public function showSessionStudent($idEncryptedClass, $idEncrypted)
    {
        $decryptedClass = \EncryptionHelper::instance()->decrypt($idEncryptedClass);
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
    
        $students = User::where('usergroup_id',3)->where('class_id', $decryptedClass)->latest()->get();
        $class = Classes::where('id', $decryptedClass)->get();
        $class_info = $class[0];
        $point_aspects = Chapter_Point_Aspect::with('pointAspects')->where('chapter_id', $class_info->chapter_id )->latest()->get();
       
        return view('teacher.class.classSessionStudent',compact('students','point_aspects','class_info', 'decrypted'));
    }

    public function getDatatableSessionPointHistory($idSession,Request $request)
    {
        // dd($idSession);
        // dd('kok ga masuk ya');
        //lagi bingung disini kenapa ga bisa ya?
        // dd('masuk ga sih?');
        if ($request->ajax()) {
            //Changed into Login Auth
            $decrypted = \EncryptionHelper::instance()->decrypt($idSession);
            // dd($decrypted);
            // $data = User::with('studentPointHistory')->where('.session_id',$decrypted)->latest()->get();
            $data = User::with('studentPointHistory')
                    ->whereHas('studentPointHistory', function ($query) use ($decrypted) {
                        $query->where('session_generated_id','=',$decrypted);
                    })
                    ->latest()
                    ->get();
   
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('point_history', function ($data) {
                    // dd($data);
                    
                    $point_history = '<ul>';
                    foreach($data->studentPointHistory as $dt){
                        $point_aspect = Point_Aspect::where('id', $dt->chapter_point_aspect_id)->get();
                        $point_history .= '<li>' . $point_aspect[0]->name .' : '. $dt->point .' ('.$dt->teacher_notes.')' . '</li>';
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

    public function createHistoryPoint(Request $request){
        // dd($request);
        $points = [];

        foreach($request->point_aspect_id as $key => $point_aspect){
            // dd($request->point[$key]);
            // $request->point[$key] = 90;
            $grade = Grade::where( 'highest_poin',">=", $request->point[$key] )->where('lowest_poin',"<=", $request->point[$key] )->get();
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
                'updated_at'=> date('Y-m-d'),
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
}
