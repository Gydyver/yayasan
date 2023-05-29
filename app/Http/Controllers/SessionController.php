<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Session as SessionData;
use App\Models\Session_Generated;


use Carbon\Carbon;

use DataTables;
class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Classes::orderBy('id','desc')->get();
        $days = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thrusday",
            "Friday",
            "Saturday",
            "Sunday"
        ];
        return view('master.session.index', compact('classes','days'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = SessionData::with('classes')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('class_label', function ($data) {
                    return  $data->classes->name;
                }) 
                ->addColumn('action', function($row){
                    // dd(json_encode($row->name));
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='user/show/".Crypt::encryptString($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(".$row->id .")'>Delete</button>
                    // ";
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    <a href='session/show/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"".\EncryptionHelper::instance()->encrypt($row->id) ."\")'>Delete</button>
                    ";
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
    public function showSession($idEncrypted)
    {
        //
        $class_id = \EncryptionHelper::instance()->decrypt($idEncrypted);
        
        $session = Classess::where('id',$class_id )->with('sessions')->get();
        dd($session);
        // dd($decrypted);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
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

        $save = SessionData::where('id', $request->id)->update($data);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Update Data"]);
        } else {

            return redirect()->back()->with(["error" => " Update Data Failed"]);
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
         $class = SessionData::find($id);
         $class->delete();
         return redirect()->route('session.index')->with('success','Class has been deleted successfully');
 
         // redirect
        //  SessionData::flash('message', 'Successfully deleted the shark!');
        //  return Redirect::to('sharks');
    }


    public function generate(Request $request)
    {
        $session = SessionData::where('class_id', $request->class_id_gen)->latest()->get();
        $class = Classes::where('id', $request->class_id_gen)->latest()->get();
        // dd($session);
        $dates = [];
        $day = "MONDAY";
        foreach($session as $s){
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
            $toDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
            
            if($s->day == 'Sunday'){
                // Get the first Sunday in the date range
                $date = $fromDate->dayOfWeek == Carbon::SUNDAY
                ? $fromDate
                : $fromDate->copy()->modify('next Sunday');
            } else if($s->day == 'Monday'){
                // Get the first Monday in the date range
                $date = $fromDate->dayOfWeek == Carbon::MONDAY
                ? $fromDate
                : $fromDate->copy()->modify('next Monday');
            }else if($s->day == 'Tuesday'){
                // Get the first Tuesday in the date range
                $date = $fromDate->dayOfWeek == Carbon::TUESDAY
                ? $fromDate
                : $fromDate->copy()->modify('next Tuesday');
            }else if($s->day == 'Wednesday'){
                // Get the first Wednesday in the date range
                $date = $fromDate->dayOfWeek == Carbon::WEDNESDAY
                ? $fromDate
                : $fromDate->copy()->modify('next Wednesday');

            }else if($s->day == 'Thursday'){
                // Get the first Thursday in the date range
                $date = $fromDate->dayOfWeek == Carbon::THURSDAY
                ? $fromDate
                : $fromDate->copy()->modify('next Thursday');

            }else if($s->day == 'Friday'){
                // Get the first Friday in the date range
                $date = $fromDate->dayOfWeek == Carbon::MONDAY
                ? $fromDate
                : $fromDate->copy()->modify('next Friday');

            }else if($s->day == 'Saturday'){
                // Get the first Saturday in the date range
                $date = $fromDate->dayOfWeek == Carbon::SATURDAY
                ? $fromDate
                : $fromDate->copy()->modify('next Saturday');
            }
            
            // Iterate until you have reached the end date adding a week each time
            while ($date->lt($toDate)) {
                // dd($date->toDateString());
                // $date = \Carbon\Carbon::parse('2016-11-01 15:04:19');
                $startDatetime = Carbon::parse($date->toDateString() . $s->time_start);
                $endDatetime = Carbon::parse($date->toDateString() . $s->time_end);

                // $dates[] = $date->toDateString(); //nanti ditaro disini nih harusnya yang $data.
                $data = [
                    'teacher_id' => $class[0]->teacher_id,
                    'session_id' => $s->id,
                    'session_start' => $startDatetime->format("Y-m-d H:i:s"),
                    'session_end' => $endDatetime->format("Y-m-d H:i:s"),
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ];
                $dates[] = $data;
                $date->addWeek();
            }
        }
        
        //DAPET NIH DATA Datesnya di array dates
        // dd($dates);

        // $data = [
        //     'teacher_id' => $class->teacher_id,
        //     'session_id' => $session->id,
        //     'session_start' => $request->chapter_id,
        //     'session_end' => $request->class_type_id,
        //     'created_at' => date('Y-m-d'),
        //     'updated_at' => date('Y-m-d')
        // ];
        // dd($data);

        $save = Session_Generated::insert($dates);

        // redirect
        if ($save) {
            return redirect()->back()->with(["success" => "Generate Class"]);
        } else {
            return redirect()->back()->with(["error" => "Generate Class Failed"]);
        }
    }
}
