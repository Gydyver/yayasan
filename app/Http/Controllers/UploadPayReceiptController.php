<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Payment_Detail;
use App\Models\Payment_Other;
use App\Models\Point_Aspect;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use DataTables;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class UploadPayReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('upload_payreceipt.index');
    }

    public function getDatatable(Request $request)
    {
        // dd(Auth::user()->id);
        if ($request->ajax()) {
            // with('billings')->
            // dd(Auth::user()->id);
            $data = Billing::with('billings')->where('student_id', Auth::user()->id)->latest()->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('month_year', function ($row) {
                    return $row->month . ' - ' . $row->year;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return 'Waiting admin confirmation';
                    } else if ($row->status == 2) {
                        return 'Paid and Confirmed';
                    } else if ($row->status == 3) {
                        return 'Need to be in ';
                    } else {
                        return 'Unpaid';
                    }
                })
                ->addColumn('tanggal_transfer', function ($row) {
                    // if($row->studentBilling != null){
                    //     return $row->studentBilling->created_at;
                    // }else{
                    //     return '-';
                    // }
                    // dd(count($row->billings));
                    if (count($row->billings) > 0) {
                        return $row->billings[0]->created_at;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('total_transfer', function ($row) {
                    // if($row->studentBilling != null){
                    //     return $row->studentBilling->created_at;
                    // }else{
                    //     return '-';
                    // }
                    if (count($row->billings) > 0) {
                        return $row->billings[0]->payment_billing;
                        // $payment_detail = Payment_Detail::where('payment_id',$row->billings->id)->latest()->get();

                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='uploadData(this);'
                    id='btnUpload' data-target='#ModalUploadFile' data-item='" . json_encode($row) . "'>Upload File</button>
                    ";
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // public function getDatatablePaid(Request $request)
    // {
    //     dd($request);
    //     if ($request->ajax()) {
    //         $data = Billing::with('Payment')::latest()->get();
    //         dd($data);
    //         return Datatables::of($data)
    //         ->addIndexColumn()
    //             ->addColumn('action', function($row){
    //                 // dd(json_encode($row->name));
    //                 $actionBtn = "
    //                 <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
    //                 id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
    //                 <button class='btn btn-sm btn-icon btn-danger'  onclick='confirmData(\"".\EncryptionHelper::instance()->encrypt($row->id) ."\")'>Delete</button>
    //                 ";
    //                 return $actionBtn;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    // }

    // public function getDatatableUnpaid(Request $request)
    // {
    //     dd($request);
    //     if ($request->ajax()) {
    //         $data = Billing::with('Payment')::latest()->get();
    //         dd($data);
    //         return Datatables::of($data)
    //         ->addIndexColumn()
    //             ->addColumn('action', function($row){
    //                 // dd(json_encode($row->name));
    //                 $actionBtn = "
    //                 <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
    //                 id='btnEdit' data-target='#ModalUpdate' data-item='".json_encode($row)."'>Update</button>
    //                 <button class='btn btn-sm btn-icon btn-danger'  onclick='confirmData(\"".\EncryptionHelper::instance()->encrypt($row->id) ."\")'>Delete</button>
    //                 ";
    //                 return $actionBtn;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    // }

    public function getDatatableHistory(Request $request)
    {
        if ($request->ajax()) {
            $data = Payment::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // dd(json_encode($row->name));
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='updateData(this);'
                    id='btnEdit' data-target='#ModalUpdate' data-item='" . json_encode($row) . "'>Update</button>
                    <button class='btn btn-sm btn-icon btn-danger'  onclick='confirmData(\"" . \EncryptionHelper::instance()->encrypt($row->id) . "\")'>Delete</button>
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadFile(Request $request)
    {
        try {
            $file_name = '';

            if ($request->hasFile('file')) {
                $file_name = $request->file('file')->getClientOriginalName();
            } else {
                $file_name = 'no file!';
            }

            $billingStatus = [
                'status' => 1
            ];

            //Update Status
            Billing::where('id', $request->billing_id)
                ->update(['status' => 1]);


            $billing = Billing::where('id', $request->billing_id)->get();

            //Upload File
            $request->file->move(public_path('uploads'), $billing[0]->month . ' / ' . $billing[0]->year . ' / ' . $request->student_id . ' / ' . $file_name);

            //Insert Payment
            $dataPayment = [
                'billing_id' => $request->billing_id,
                'transfer_time' => $request->transfer_time,
                'link_prove' =>  $billing[0]->month . ' / ' . $billing[0]->year . ' / ' . $request->student_id . ' / ' . $file_name,
                'payment_billing' => $request->nominal,
                'notes' => $request->notes,
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ];
            $idPayment = Payment::insertGetId($dataPayment);

            //Insert Payment Detail
            $dataPaymentDet = [
                'payment_id' => $idPayment,
                'student_id' => $request->student_id,
                'nominal' => $request->nominal,
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ];
            $save = Payment_Detail::insert($dataPaymentDet);

            // redirect
            if ($save) {
                return redirect()->back()->with(["success" => "Tambah Data"]);
            } else {

                return redirect()->back()->with(["error" => " Tambah Data Failed"]);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function formUploadBulking()
    {
        $billings = Billing::whereIn('status', [0, 2])->where('student_id', Auth::user()->id)->latest()->get();
        // dd($billings);
        return view('upload_payreceipt.form', compact('billings'));
    }

    public function uploadFileBulking(Request $request)
    {
        //Masih belum karna dari inputnya perlu di update
        try {
            $file_name = '';

            if ($request->hasFile('file')) {
                $file_name = $request->file('file')->getClientOriginalName();
            } else {
                $file_name = 'no file!';
            }

            $billingStatus = [
                'status' => 1
            ];

            //Update Status
            Billing::where('id', $request->billing_id)
                ->update(['status' => 1]);


            $billing = Billing::where('id', $request->billing_id)->get();

            //Upload File
            $request->file->move(public_path('uploads'), $billing[0]->month . ' / ' . $billing[0]->year . ' / ' . $request->student_id . ' / ' . $file_name);

            //Insert Payment
            $dataPayment = [
                'billing_id' => $request->billing_id,
                'transfer_time' => $request->transfer_time,
                'link_prove' =>  $billing[0]->month . ' / ' . $billing[0]->year . ' / ' . $request->student_id . ' / ' . $file_name,
                'payment_billing' => $request->nominal,
                'notes' => $request->notes,
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ];
            $idPayment = Payment::insertGetId($dataPayment);

            foreach ($student_ids as $student_id) {
                //Insert Payment Detail
                $dataPaymentDet = [
                    'payment_id' => $idPayment,
                    'student_id' => $request->student_id,
                    'nominal' => $request->nominal,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ];
                $save = Payment_Detail::insert($dataPaymentDet);
            }


            // redirect
            if ($save) {
                return redirect()->back()->with(["success" => "Tambah Data"]);
            } else {

                return redirect()->back()->with(["error" => " Tambah Data Failed"]);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        // dd('masuk yupdate');
        // dd($request->all());
        $data = [
            'name' => $request->name
        ];

        $save = Point_Aspect::where('id', $request->id)->update($data);

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
        // dd('masuk delete',$id);
        // dd($id);
        $id = \EncryptionHelper::instance()->decrypt($idEncrypted);
        $delete = Point_Aspect::where('id', $id)->Delete();
        // redirect
        if ($delete) {
            return redirect()->back()->with(["success" => "Delete Data"]);
        } else {
            return redirect()->back()->with(["error" => " Delete Data"]);
        }
    }
}
