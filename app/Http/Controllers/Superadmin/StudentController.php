<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Session;
use App\Models\Chapter;
use App\Models\Chapter_History;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DataTables;

class StudentController extends Controller
{
    public function index()
    {
        //Changed into Login Auth

        $chapters = Chapter::latest()->get();
        $classes = Classes::where('closed', false)->latest()->get();
        return view('superadmin.student.index', compact('chapters', 'classes'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            //Changed into Login Auth
            //Changed into Login Auth
            $data = User::with('studentClasses', 'studentChapters')
                // ->whereHas('studentClasses', function ($query) {
                //     return $query->where('student_id', '=', Auth::user()->id);
                // })
                ->where('usergroup_id', 3)
                ->latest()
                ->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('class_label', function ($row) {
                    if ($row->studentClasses != null) {
                        return  $row->studentClasses->name;
                    } else {
                        return  '<span style="color:red;">Set class first<span>';
                    }
                })
                ->addColumn('chapter_label', function ($row) {
                    if ($row->studentChapters != null) {
                        return  $row->studentChapters->name;
                    } else {
                        return  '<span style="color:red;">Set chapter first<span>';
                    }
                })
                ->addColumn('age', function ($row) {

                    $dateOfBirth = $row->birth_date;
                    $age = Carbon::parse($dateOfBirth)->age;
                    return  $age;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = "<a href='/superadmin/student/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>";
                    if ($row->studentClasses != null) {
                        $actionBtn .= "<button type='button' class='btn btn-sm btn-icon btn-success' data-toggle='modal' onclick='changeClass(this);'
                        id='btnChangeClass' data-target='#ModalUpdateClass' data-target='#ModalUpdate'  data-item='" . json_encode($row) . "'>Change Class</button> ";
                    } else {
                        $actionBtn .= "
                        <button type='button' class='btn btn-sm btn-icon btn-success' data-toggle='modal' onclick='setClass(this);'
                        id='btnSetClass' data-target='#ModalSetClass' data-item='" . json_encode($row) . "'>Set Class</button>
                        ";
                    }

                    if ($row->studentChapters != null) {
                        $actionBtn .= " <button type='button' class='btn btn-sm btn-icon btn-warning' data-toggle='modal' onclick='changeChapter(this);'
                        id='btnChangeChapter' data-target='#ModalUpdateChapter' data-item='" . json_encode($row) . "'>Change Chapter</button> ";
                    } else {
                        $actionBtn .= "
                        <button type='button' class='btn btn-sm btn-icon btn-warning' data-toggle='modal' onclick='setChapter(this);'
                        id='btnSetChapter' data-target='#ModalSetChapter' data-item='" . json_encode($row) . "'>Set Chapter</button>
                        ";
                    }

                    return $actionBtn;
                })
                ->rawColumns(['action', 'class_label', 'chapter_label'])
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
        try {
            $data = [
                'student_id' => $request->student_id,
                'chapter_id' => $request->chapter_id,
                'start_date' => $request->start_date
            ];
            $save = Chapter_History::create($data);
            $end_date_prev_chap = Carbon::parse($request->start_date)->subDay()->format('m/d/Y');


            $update_prev_chapter = Chapter_History::where('student_id', $request->student_id)
                ->orderBy('id', 'desc')
                ->take(1)->update(['end_date' => $end_date_prev_chap]);

            $update_user = User::where('id', $request->student_id)
                ->update(['chapter_id' => $request->chapter_id]);

            if ($save && $update_prev_chapter && $update_user) {
                return redirect()->back()->with(["success" => "Data berhasil tersimpan"]);
            } else {

                return redirect()->back()->with(["error" => " Gagal menyimpan data"]);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function setChapter(Request $request)
    {
        try {
            $data = [
                'student_id' => $request->student_id,
                'chapter_id' => $request->chapter_id,
                'start_date' => $request->start_date
            ];
            $save = Chapter_History::create($data);

            $update_user = User::where('id', $request->student_id)
                ->update(['chapter_id' => $request->chapter_id]);

            if ($save && $update_user) {
                return redirect()->back()->with(["success" => "Data berhasil tersimpan"]);
            } else {

                return redirect()->back()->with(["error" => " Gagal menyimpan data"]);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function changeClass(Request $request)
    {
        try {
            // $data = [
            //     'student_id' => $request->student_id,
            //     'class_id' => $request->chapter_id,
            //     'start_date' => $request->start_date
            // ];
            // $save = Chapter_History::create($data);
            // $end_date_prev_chap = Carbon::parse($request->start_date)->subDay()->format('m/d/Y');


            // $update_prev_chapter = Chapter_History::where('student_id', $request->student_id)
            //     ->orderBy('id', 'desc')
            //     ->take(1)->update(['end_date' => $end_date_prev_chap]);

            $update_user = User::where('id', $request->student_id)
                ->update(['class_id' => $request->class_id]);

            if ($update_user) {
                return redirect()->back()->with(["success" => "Data berhasil tersimpan"]);
            } else {

                return redirect()->back()->with(["error" => " Gagal menyimpan data"]);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function setClass(Request $request)
    {
        try {
            // $data = [
            //     'student_id' => $request->student_id,
            //     'class_id' => $request->class_id,
            //     'start_date' => $request->start_date
            // ];
            // $save = Chapter_History::create($data);

            $update_user = User::where('id', $request->student_id)
                ->update(['class_id' => $request->class_id]);

            if ($update_user) {
                return redirect()->back()->with(["success" => "Data berhasil tersimpan"]);
            } else {

                return redirect()->back()->with(["error" => " Gagal menyimpan data"]);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}
