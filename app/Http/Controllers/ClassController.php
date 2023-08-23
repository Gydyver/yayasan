<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\class_type;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Session_Generated;
use App\Models\Session as SessionData;

use Illuminate\Support\Facades\Auth;
use DataTables;

class ClassController extends Controller
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
        //Changed into Login Auth
        $classes = Classes::orderBy('id', 'desc')->paginate(10);
        $teachers = User::orderBy('name', 'asc')->where('usergroup_id', 2)->get();
        $chapters = Chapter::orderBy('name', 'asc')->get();
        $class_types = class_type::orderBy('name', 'asc')->get();

        //Pengecekan Session Data
        if (\SessionCheckingHelper::instance()->checkSuperadmin($_SESSION["data"]->usergroup_id)) {
            //Superadmin
            $permission = true;
        } else if (\SessionCheckingHelper::instance()->checkTeacher($_SESSION["data"]->usergroup_id)) {
            //Teacher
            $permission = true;
        } else if (\SessionCheckingHelper::instance()->checkStudent($_SESSION["data"]->usergroup_id)) {
            //Student
            $permission = false;
        } else {
            $permission = false;
        }

        if ($permission) {
            return view('master.class.index', compact('classes', 'teachers', 'chapters', 'class_types'));
        } else {
            return redirect()->route('notAllowed');
        }
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            if ($_SESSION["data"]->usergroup_id == 2) {
                $data = Classes::with('teachers')->with('classTypes')->where('teacher_id', $_SESSION["data"]->id)->latest()->get();
            } else {
                $data = Classes::with('teachers')->with('classTypes')->latest()->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status_label', function ($row) {
                    if ($row->closed) {
                        return  'Selesai';
                    } else {
                        return 'Aktif';
                    }
                })
                ->addColumn('teacher_label', function ($data) {
                    return  $data->teachers->name;
                })
                ->addColumn('class_type_label', function ($data) {
                    return  $data->classTypes->name;
                })

                ->addColumn('action', function ($row) {
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";
                    // dd($row);
                    if ($row->closed) {
                        $actionBtn = " <a href='/class/show/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>";
                    } else {
                        $actionBtn = "
                        <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                        id='btnEdit' data-target='#ModalUpdate' data-item='" . json_encode($row) . "'>Update</button>
                        <a href='/class/show/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
                        <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Delete</button>
                        <button type='button' class='btn btn-sm btn-icon btn-danger' data-toggle='modal' onclick='closeClass(this);'
                        id='btnClose' data-target='#ModalClose' data-item='" . json_encode($row) . "'>Closed Class</button>
                        ";
                    }

                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmDataClosed(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Closed</button>
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
            'teacher_id' => $request->teacher_id,
            // 'chapter_id' => $request->chapter_id,
            // 'chapter_id' => $request->chapter_id,
            'class_type_id' => $request->class_type_id,
            'class_start' => $request->class_start,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d')
        ];
        // dd($data);

        $save = Classes::insert($data);

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
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        $classes = Classes::where('id', $decrypted)->paginate(10);

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
            $days = [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thrusday",
                "Friday",
                "Saturday",
                "Sunday"
            ];

            return view('master.class.detail', compact('classes', 'idEncrypted', 'days'));
        } else {
            return redirect()->route('notAllowed');
        }
    }

    public function getDatatableSession($id, Request $request)
    {
        $decrypted = \EncryptionHelper::instance()->decrypt($id);
        if ($request->ajax()) {
            $data = SessionData::with('classes')->where('class_id', $decrypted)->latest()->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('teacher_label', function ($data) {
                //     return  $data->teachers->name;
                // })
                // ->addColumn('class_type_label', function ($data) {
                //     return  $data->classTypes->name;
                // })

                ->addColumn('action', function ($row) use ($data) {
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";
                    // dd($data);
                    if ($data[0]->classes->closed) {
                        $actionBtn = " <a href='/class/showSessionGenerated/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>";
                    } else {
                        $actionBtn = "
                        <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                        id='btnEdit' data-target='#ModalUpdate' data-item='" . json_encode($row) . "'>Update</button>
                        <a href='/class/showSessionGenerated/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
                        <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Delete</button>
                        ";
                    }

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function showSessionGenerated($idEncrypted)
    {
        // dd('showSessionGenerated');
        $session_id = \EncryptionHelper::instance()->decrypt($idEncrypted);

        $session = SessionData::with('classes')->where('id', $session_id)->get();

        $classes = Classes::where('id', $session[0]->class_id)->get();
        // dd($classes);
        $teachers = User::where('usergroup_id', 2)->get();

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
            return view('master.class.detailSesGen', compact('idEncrypted', 'session', 'teachers'));
        } else {
            return redirect()->route('notAllowed');
        }
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

    public function updateSessionGenerated(Request $request)
    {
        $data = [
            'teacher_id' => $request->teacher_id,
            'status' => $request->status,
            'session_start' => $request->session_start,
            'session_end' => $request->session_end,
            'updated_at' => date('Y-m-d')
        ];

        $save = Session_Generated::where('id', $request->id)->update($data);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Update Data"]);
        } else {

            return redirect()->back()->with(["error" => " Update Data Failed"]);
        }
    }

    public function updateSessionGeneratedStat(Request $request)
    {
        $data = [
            'status' => $request->status
        ];

        $update = Session_Generated::where('id', $request->id)->update($data);

        // redirect
        if ($update) {
            return redirect()->back()->with(["success" => "Update Status Generated Status"]);
        } else {

            return redirect()->back()->with(["error" => " Update Status Generated Status Failed"]);
        }
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
        // try {
        //     //code...
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
        $teacher = Classes::where('id', $request->id)->pluck('teacher_id');
        $data = [
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            // 'chapter_id' => $request->chapter_id,
            // 'chapter_id' => $request->chapter_id,
            'class_type_id' => $request->class_type_id,
            'class_start' => $request->class_start,
            'updated_at' => date('Y-m-d')
        ];

        // dd($teacher[0]);
        if ($teacher[0] != $request->teacher_id) {
            // dd($request->teacher_id);
            $this->updateTeacherInSesGen($request->teacher_id, $request->id);
        }

        $save = Classes::where('id', $request->id)->update($data);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Update Data"]);
        } else {

            return redirect()->back()->with(["error" => " Update Data Failed"]);
        }
    }

    function updateTeacherInSesGen($teacher_id, $class_id)
    {
        // dd('masuk updateTeacherInSesGen');
        // dd($teacher_id);
        try {
            //Disini proses update teacher
            $session = SessionData::where('class_id', $class_id)->pluck('id')->toArray();

            $session_generated = Session_Generated::whereIn('session_id', $session)->update(['teacher_id' => $teacher_id]);
            // dd($session_generated);
            // if ($session_generated) {
            return true;
            // }
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    public function close(Request $request)
    {
        $data = [
            'class_end' => $request->class_end,
            'closed' => true
        ];

        // dd($data);

        $save = Classes::where('id', $request->id)->update($data);

        $this->updateStatusInSesGen($request->class_end, $request->id);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Close Class Success"]);
        } else {

            return redirect()->back()->with(["error" => " Close Data Failed"]);
        }
    }

    function updateStatusInSesGen($class_end, $class_id)
    {
        try {

            // $today = date("Y-m-d");
            $today = date($class_end);


            //Disini proses update teacher
            $session = SessionData::where('class_id', $class_id)->pluck('id')->toArray();

            $session_generated_close = Session_Generated::whereIn('session_id', $session)->where('session_end', '<', $today)->update(['status' => 1]);
            $session_generate_cancel = Session_Generated::whereIn('session_id', $session)->where('session_end', '>', $today)->update(['status' => 2]);
            // dd($session_generated);
            // if ($session_generated) {
            return true;
            // }
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
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
        $class = Classes::find($id);
        $session_ids = SessionData::where('class_id', $id)->pluck('id')->toArray();
        $session_generated = Session_Generated::whereIn('session_id', $session_ids)->delete();
        $session = SessionData::where('class_id', $id)->delete();
        $class->delete();
        return redirect()->route('class.index')->with('success', 'Class has been deleted successfully');
    }

    public function destroySessionGenerated($idEncrypted)
    {
        // delete
        $id = \EncryptionHelper::instance()->decrypt($idEncrypted);
        $sesGen = Session_Generated::find($id);
        $idSes = $sesGen->session_id;
        // dd($idSes);
        $idSesEncrypted = \EncryptionHelper::instance()->encrypt($idSes);
        // dd($idSesEncrypted);
        $sesGen->delete();
        // \Redirect::route('regions', $id)->with('message', 'State saved correctly!!!');
        return redirect()->route('class.showSessionGenerated', $idSesEncrypted)->with('success', 'Session Generated has been deleted successfully');
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

        return redirect()->route('class.index')->with('success', 'Class has been closed successfully');
    }
}
