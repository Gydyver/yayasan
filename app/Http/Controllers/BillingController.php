<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Session;

use DataTables;
class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $years = [2019,2020,2021,2022,2023,2024,2025,2026,2027];
        return view('master.billing.index', compact('months', 'years'));
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Billing::with('students')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('student_label', function ($data) {
                    return  $data->students->name;
                })
                ->addColumn('action', function($row){
                    // actionBtn standard, billing cuma show aja dulu untuk saat ini
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    // id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
                    // <a href='billing/show/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
                    // <button class='btn btn-sm btn-icon btn-danger' onclick='confirmData(\"".\EncryptionHelper::instance()->encrypt($row->id) ."\")'>Delete</button>
                    // ";
                    $actionBtn = "
                    <a href='billing/show/".\EncryptionHelper::instance()->encrypt($row->id)."' class='btn btn-sm btn-primary'>Detail</a>
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

    }

    public function generateMonthlyBilling(Request $request)
    {
        // $dateMin = strtotime('10/{$request-month}/{$request-year}');
        $dateMin = '01-'.$request->month.'-'.$request->year;
        $dateMinfilter = date($dateMin);



        $students = User::where('usergroup_id',3)->where('join_date' , '<=', $dateMinfilter)->get();
        // dd($students);
        // 
        $studentNotFound = [];
        foreach($students as $student){
            $billing = Billing::where('month', $request->month)->where('year', $request->year)->where('student_id', $student->id)->get();
            if(count($billing) ==0){
                $billingRow = [
                    'student_id'  =>  $student->id,
                    'billing'  =>  $student->monthly_fee,
                    'month'  =>  $request->month,
                    'year'  =>  $request->year,
                    'status'  =>  0,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ];
                array_push($studentNotFound, $billingRow);
            }
        }
        // dd($studentNotFound);

        if(count($studentNotFound) > 0){
            Billing::insert($studentNotFound);
            return redirect()->back()->with(["success" => "Semua billing berhasil dibuat"]);
        }else{
            return redirect()->back()->with(["error" => "Semua billing sudah terbuat"]);
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
        //
        $decrypted = \EncryptionHelper::instance()->decrypt($idEncrypted);
        dd($decrypted);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idEncrypted)
    {

    }
}
