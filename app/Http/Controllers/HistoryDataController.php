<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point_History;
use App\Models\Session_Generated;
use App\Models\Session as Session_Data;
use App\Models\Classes;
use App\Models\User;
use App\Models\Chapter_Point_Aspect;


use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use DB;

use DataTables;

class HistoryDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $classes = Classes::orderBy('id', 'desc')->get();

        return view('history.index');
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            // ->where('status', "!=", 2)
            $user = User::where('id', Auth::User()->id)->get();
            $data = Session_Generated::with('teacherData', 'sessionGenerated', 'pointHistory')
                ->whereHas('sessionGenerated', function ($query) use ($user) {
                    $query->where('class_id', '=', $user[0]->class_id);
                })
                // ->where('')
                ->latest()->get();
            // dd($data);
            // dd($data[4]->sessionGenerated);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status_label', function ($row) {
                    // dd($row);
                    if ($row->status == 0) {
                        return  'Aktif';
                    } else  if ($row->status == 1) {
                        return  'Selesai';
                    } else {
                        return  'Di Batalkan';
                    }
                })
                ->addColumn('teacher_label', function ($row) {
                    // dd($row->teacherData->name);

                    return  $row->teacherData->name;
                })
                ->addColumn('class_label', function ($row) {
                    // dd($row->sessionGenerated->class_id);
                    if ($row->sessionGenerated->class_id) {
                        $class = Classes::where('id', $row->sessionGenerated->class_id)->get();
                        return  $class[0]->name;
                    }
                    return "";
                })
                ->addColumn('action', function ($row) {
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";

                    // if ($row->classes->status != 2) {

                    $actionBtn = "
                    <a href='history/show/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
                    ";
                    // } else {
                    //     $actionBtn = "<a href='session/showSession/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>";
                    // }
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
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d')
        ];
        // dd($data);

        $save = SessionData::insert($data);

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
        $session_generated_id = \EncryptionHelper::instance()->decrypt($idEncrypted);

        $session_generated = Session_Generated::where('id', $session_generated_id)->get();
        $session = Session_Data::where('id', $session_generated[0]->session_id)->get();
        $class = Classes::where('id', $session[0]->class_id)->get();
        $pointHistory = Point_History::with('chapterPointAspectHistory', 'studentPointHistory', 'pointHistory')->where('session_generated_id', $session_generated_id)->get();

        return view('history.detail', compact('class', 'session', 'session_generated', 'pointHistory', 'idEncrypted'));
    }

    public function getDatatableHistory($idEncrypted, Request $request)
    {
        // dd($idEncrypted);
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        // dd($decrypted);
        if ($request->ajax()) {
            $data = Session_Generated::with('sessionGenerated')->where('session_id', $decrypted)->latest()->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('day_label', function ($row) {
                    // dd($row);
                    if ($row->session_start) {
                        return date('l', strtotime($row->session_start));
                    }
                    // return  $row->start_time;
                })
                ->addColumn('status_label', function ($row) {
                    // dd($row);
                    if ($row->status == 0) {
                        return 'Aktif';
                    } else  if ($row->status == 1) {
                        return 'Selesai';
                    } else  if ($row->status == 2) {
                        return 'Di Batalkan';
                    }
                    // return  $row->start_time;
                })
                ->addColumn('action', function ($row) use ($data) {

                    $class_id = $data[0]->sessionGenerated->class_id;
                    $class_info = Classes::where('id', $class_id)->get();
                    // dd($class_info);
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";

                    if ($class_info[0]->closed) {
                        $actionBtn = "<div class='alert alert-primary'><center>Kelas Sudah ditutup</center></div>";
                    } else {
                        $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='" . json_encode($row) . "'>Update</button>
                    <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Delete</button>
                    ";
                    }
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getDatasetHistory($idEncStud, Request $request)
    {
        // dd($idEncrypted);
        $decryptedStudent = \EncryptionHelper::instance()->decrypt($idEncStud);
        // $decryptedSesGen = \EncryptionHelper::instance()->decrypt($idEncSesGen);
        // dd($decrypted);

        $from = "2021-01-01";
        $to = "2021-06-01";
        if ($request->ajax()) {
            $datas = Point_History::with('studentPointHistory', 'chapterPointAspectHistory', 'pointHistory')
                // with(['chapterPointAspectHistory.pointAspects' => function ($query) {
                //         $query->select('name');
                // }, 'pointHistory'])
                // ->selectRaw('point', )
                // ->select(DB::raw('point','chapterPointAspectHistory','chapterPointAspectHistory.pointAspects.name as label'))
                ->whereHas('pointHistory', function ($query) use ($from, $to) {
                    return $query->whereBetween('session_start', [$from, $to])->whereBetween('session_end', [$from, $to]);
                })
                ->where('student_id', $decryptedStudent)->latest()->get();
            // dd($datas);

            $points = [];
            $labels = [];


            // foreach ($datas as $key => $value) {
            //     array_push($points,(int)$value->point);
            //     array_push($labels,$value->chapterPointAspectHistory->pointAspects->name);
            // }

            $student = User::with('studentChapters')->where('id', $decryptedStudent)->get();
            $point_aspects = Chapter_Point_Aspect::with('pointAspects')->where('chapter_id', $student[0]->chapter_id)->get();
            $point_history = Point_History::with('pointAspects')->where('chapter_id', $student[0]->chapter_id)->get();

            $datanya = [];
            $arraytest = [];
            foreach ($point_aspects as $key => $value) {
                array_push($labels, $value->pointAspects->name);

                // $dataset_ .  = [];
                // ${'dataset_' . $key} = [];
                // dd($value->pointAspects->id);

            }

            foreach ($datas as $key2 => $value2) {
                // dd($value->pointAspects->id);
                // dd($value2->chapter_point_aspect_id);
                if ($value2->chapter_point_aspect_id == $value->pointAspects->id) {
                    // dd('sempet masuk if');
                    dd($value);
                    array_push($datanya[$key], $value->point);
                    dd($datanya);
                }
            }

            array_push($arraytest, $datanya);
            dd($arraytest);
            // dd($arraytes

            // $datas =  DB::select('select * from point_history ph JOIN with user u on ph.student_id = u.id JOIN chapter_point_history cph on cph.session_generated_id =   where id = ?', [1

            //    $data = 
            //    $data['labels'] = $label
            //    $data['data'] = $poin

            // dd($label

            // return json_encode($dat


            //        return $da
            //    } else{
            //        return nu
            //    }

        }
    }
}
