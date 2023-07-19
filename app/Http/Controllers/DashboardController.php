<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\MenuTrait;
use App\Models\Session_Generated;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserAccess;
use App\Models\Menu;
use App\Models\Classes;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    use MenuTrait;
    public function index()
    {
        if (Auth::user() ==  null) {
            return redirect()->route('login.view');
        }
        $data = [];
        $totalStudent = [];

        //bisa untuk data div card
        $user_info = User::where('id', Auth::User()->id)->get();

        $idEncrypted = \EncryptionHelper::instance()->encrypt(Auth::User()->id);
        // dd($idEncrypted);
        if ($user_info[0]->usergroup_id == 3) {
            $next_class = DB::select("select sg.*, c.name as class_name, u.name as user_name
            from session_generated sg 
            join session s on s.id = sg.session_id 
            join  class c on c.id = s.class_id 
            join users u on u.class_id = c.id 
            where sg.status = 0 and u.id = " . Auth::User()->id . " and sg.session_start > NOW()::timestamp limit 1");
            // dd($next_class);

            $currMonthBill = DB::select("
            select b.month, b.year, b.billing, p.nominal from billing b LEFT JOIN payment_detail p ON p.billing_id = b.id  where b.student_id = " . Auth::User()->id . "  and b.month =  date_part('month', (SELECT current_timestamp))
            ");

            $studentLatestData = DB::select("select * from users where id = " . Auth::User()->id . "");
            // dd($studentLatestData);
        } else {
            $next_class = [];
            $currMonthBill = [];
            $studentLatestData = [];
            if ($user_info[0]->usergroup_id == 2) {
                $teacher_id = $user_info[0]->id;
                $totalStudent = User::whereHas('studentClasses', function ($query) use ($teacher_id) {
                    $query->where('teacher_id', '=', $teacher_id);
                })->where('usergroup_id', 3)->get();
                $totalClass = Classes::where('closed', false)->where('teacher_id', $teacher_id)->get();
                // dd($totalStudent);
            } else {
                $totalStudent = User::where('usergroup_id', 3)->get();
                $totalClass = Classes::where('closed', false)->get();
            }
        }
        // dd($totalStudent);
        // dd($totalStudent);


        // $student = DB::select("select * from users where id = 10");

        //getting week of date range
        // $from = Carbon::parse("2023-07-01");
        // $to = Carbon::parse("2023-12-31");

        // $weeks = $from->diffInWeeks($to);

        // foreach ($weeks as $key => $value) {


        //     # code...
        // }

        // dd("Number of weeks: " . $weeks);
        // dd('masuk getMenus');
        $auth = Auth::user()->usergroup_id;
        // dd($auth);

        // $data['menus'] = $this->getMenus();
        // compact('data')
        $menus = $this->getMenus();
        return view('dashboard', compact('menus', 'user_info', 'next_class', 'currMonthBill', 'studentLatestData', 'idEncrypted', 'totalStudent', 'totalClass'));
    }

    function getDataFromVar($var, $key)
    {
        $array = [];
        foreach ($var as $key => $value) {
            dd($value->$key);
            $array[] = $value[$key];
        }
        return $array;
    }

    function getDatasetStudGen()
    {
        //Teacher
        //Pie Chart
        $usergroup_id = Auth::user()->usergroup_id;
        // dd($usergroup_id);
        if ($usergroup_id == 2) {
            $teacher_id = Auth::User()->id;
            // dd($teacher_id);
            $pieGenderStudData = User::whereHas('studentClasses', function ($query) use ($teacher_id) {
                $query->where('teacher_id', $teacher_id);
            })->selectRaw('gender,COUNT(*) as total ')->where('usergroup_id', 3)->groupBy('gender')->get();
            // dd($pieGenderStudData);
        } else {
            $pieGenderStudData = User::selectRaw('gender,COUNT(*) as total ')->where('usergroup_id', 3)->groupBy('gender')->get();
        }
        // dd($pieGenderStudData);
        $label = [];
        $data = [];

        foreach ($pieGenderStudData as $key => $value) {
            $label[] = $value['gender'];
            $data[] = $value['total'];
        }
        // $pieGenderStud = json_encode(['gender' => $label, 'total' => $data]);

        return json_encode(['label' => $label, 'total' => $data]);
    }

    function getDataSetSesGenCanceled()
    {
        //Superadmin
        //Bar Chart
        $CancelledSesGen = Session_Generated::selectRaw('teacher_id,COUNT(*) as total ')->where('status', 2)->groupBy('teacher_id')->get();
        $label = [];
        $data = [];

        foreach ($CancelledSesGen as $key => $value) {
            $teacher = User::where('id', $value->teacher_id)->get();
            $label[] = $teacher[0]->name;
            $data[] = $value['total'];
        }
        return json_encode(['label' => $label, 'total' => $data]);
    }


    function getStudHighestAbsent()
    {
        //Teacher
        $teacher_id = Auth::User()->id;
        $from = strtotime('2021-01-01');
        $to = strtotime('2023-07-31');
        // (select count(sg.*) as total
        // from session_generated sg 
        // join session s on s.id = sg.session_id join  class c on c.id = s.class_id 
        // join users u on u.class_id = c.id 
        // where sg.status != 2 and u.id =  ua.id and sg.session_start < NOW()) as total_session,
        // (SELECT COUNT(ph) AS total_present
        // FROM users u
        // LEFT JOIN point_history ph ON ph.student_id  = u.id 
        // LEFT JOIN session_generated sg ON ph.session_generated_id  = sg.id 
        // where u.usergroup_id = 3
        // and u.id = ua.id and sg.session_start < NOW()) as total_present,

        //Total Session Generated
        $highestAbsent = DB::select("select 
        ua.name,
        uc.name as class_name,
        (select count(sg.*) as total
        from session_generated sg 
        join session s on s.id = sg.session_id join  class c on c.id = s.class_id 
        join users u on u.class_id = c.id 
        where sg.status != 2 and u.id =  ua.id and sg.session_start < NOW()) - (SELECT COUNT(ph) AS total_present
        FROM users u
        LEFT JOIN point_history ph ON ph.student_id  = u.id 
        LEFT JOIN session_generated sg ON ph.session_generated_id  = sg.id 
        where u.usergroup_id = 3
        and u.id = ua.id and sg.session_start < NOW()) as total
        from users ua JOIN class uc on uc.id = ua.class_id where ua.usergroup_id = 3 and uc.teacher_id = " . $teacher_id . " and uc.class_start >= " . "'2021-01-01'" . " and (uc.class_end >= " . "'2023-07-31'" . " OR uc.class_end IS NULL ) order by total desc limit 5");
        // dd($highestAbsent);
        // date('Y-m-d', $to)
        // uc.name as class_name,
        // dd($highestAbsent);
        $label = [];
        $data = [];

        foreach ($highestAbsent as $key => $value) {
            // dd($value->name);
            $label[] = $value->name . " ( " . $value->class_name . " )";
            $data[] = $value->total;
        }
        // dd(json_encode(['label' => $label, 'total' => $data]));
        return json_encode(['label' => $label, 'total' => $data]);
    }



    // function getStudLatestData()
    // {
    //     $student = DB::select("select * from users where id = 10");
    // }
}
