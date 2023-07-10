<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Payment_Detail;
use App\Models\Payment_Other;
use App\Models\Point_Aspect;
use App\Models\User;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment.index');
    }

    public function getDatatable(Request $request)
    {
        // dd('getDatatable');
        if ($request->ajax()) {
            // with('billings')->
            // dd(Auth::user()->id);
            if (Auth::user()->usergroup_id == 3) {
                $data = Payment::whereHas('payment_detail', function ($query) {
                    $query->where('student_id', Auth::user()->id);
                })->latest()->get();
                // dd($data);

                // ->where("payment_detail->student_id",  Auth::user()->id)->latest()->get();
                // with(['payment_detail' => function ($query) {
                //     $query->where('student_id', Auth::user()->id);
                // }])
                // with('payment_detail')

            } else {
                $data = Payment::with('payment_detail')->latest()->get();
            }
            // dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->verified) {
                        return 'Pembayaran di verifikasi';
                    } else {
                        return 'Menunggu Verifikasi';
                    }
                })
                ->addColumn('tanggal_transfer', function ($row) {
                    // if (count($row->billings) > 0) {
                    return $row->transfer_time;
                    // } else {
                    //     return '-';
                    // }
                })
                ->addColumn('student_name', function ($row) use ($data) {
                    // if (count($row->billings) > 0) {
                    // dd($data);
                    // dd($row);
                    $student_name = User::where('id', $row->payment_detail[0]->student_id)->get();
                    return $student_name[0]->name;
                    // } else {
                    //     return '-';
                    // }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = "
                    <a href='payment/show/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail</a>
                    ";
                    // <a href='payment/edit/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Update</a>

                    if ($row->verified != true) {
                        $actionBtn .= "<a href='payment/edit/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Update</a>";
                    }

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDatatablePaymentDetail($id, Request $request)
    {

        if ($request->ajax()) {
            $decrypted = \EncryptionHelper::instance()->decrypt($id);
            // $data = Payment::with(['payment_detail' => function ($query) use ($decrypted) {
            //     $query->where('payment_id', $decrypted);
            // }])->whereNotNull('student_id')->latest()->get();
            $data = Payment_Detail::with('billings')->where('payment_id', $decrypted)->whereNotNull('billing_id')->latest()->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('bilings', function ($row) {
                    // if ($row->billings) {
                    $studentDetail = User::where('id', $row->billings->student_id)->get();

                    $row->billings->monthName = getMonthName($row->billings->month);
                    $row->billings->studentDetail = $studentDetail[0];

                    return getMonthName($row->billings->month) . ' ' .  $row->billings->year;
                    // }
                    // return 'Billing Not Found';
                })
                // ->addColumn('bilings', function ($row) {
                //     // if ($row->billings) {
                //     return getMonthName($row->billings->month) . ' ' .  $row->billings->year;
                //     // }
                //     // return 'Billing Not Found';
                // })
                ->addColumn('tanggal_transfer', function ($row) {
                    return $row->transfert;
                })
                ->addColumn('action', function ($row) {
                    // $actionBtn = "
                    // <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='detailBilling(this);'
                    // id='btnDetail' data-target='#ModalDetail' data-item='" . json_encode($row) . "'>Detail Billing</button>
                    // ";
                    $actionBtn = "
                    <button type='button' class='btn btn-sm btn-icon btn-primary' data-toggle='modal' onclick='detailBilling(this);'
                    id='btnDetail' data-target='#ModalDetail' data-item='" . json_encode($row) . "'>Detail Billing</button>
                    ";
                    // <a target='_blank' href='billing/show/" . \EncryptionHelper::instance()->encrypt($row->id) . "' class='btn btn-sm btn-primary'>Detail Billing</a>
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
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
        $id = \EncryptionHelper::instance()->decrypt($idEncrypted);

        $payment = Payment::where('id', $id)->get();
        $paymentDetail = Payment_Detail::where('payment_id', $id)->get();
        $sedekah = Payment_Detail::where('payment_id', $id)->whereNull('billing_id')->get();
        // dd($paymentDetail);

        return view('payment.detail', compact('payment', 'paymentDetail', 'idEncrypted', 'sedekah'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($idEncrypted)
    {
        $id =  \EncryptionHelper::instance()->decrypt($idEncrypted);
        $payments = Payment::where('id', $id)->latest()->get();
        $payment_details = Payment_Detail::with('billings')->where('payment_id', $id)->whereNotNull('billing_id')->latest()->get();
        // dd($payment_details);
        // dd($payments[0]);
        // dd($payments[0]->nominal);
        $existing_billing_id = [];
        foreach ($payment_details as $key => $value) {
            array_push($existing_billing_id, $value->billing_id);
        }

        $payment_sedekah = Payment_Detail::where('payment_id', $id)->whereNull('billing_id')->latest()->get();

        $billings = Billing::where(function ($query) {
            $query->whereIn('status', [0, 2]);
            $query->where('student_id', Auth::user()->id);
        })
            ->orWhereIn('id', $existing_billing_id)
            ->latest()->get();
        // dd($existing_billing_id);
        $billings = Billing::whereIn('status', [0, 2])->where('student_id', Auth::user()->id)->orWhereIn('id', $existing_billing_id)->latest()->get();
        // dd($payment_details);
        return view('upload_payreceipt.formUpdate', compact('billings', 'payments', 'payment_details', 'payment_sedekah', 'idEncrypted'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($idEncrypted, Request $request)
    {
        try {

            // dd($request);

            $id =  \EncryptionHelper::instance()->decrypt($idEncrypted);
            $previous_data = Payment::where('id', $id)->get();

            File::delete($previous_data[0]->link_prove);

            $previous_billing_datas = Payment_Detail::where('payment_id', $id)->get();

            foreach ($previous_billing_datas as $key => $value) {
                // dd($value);
                //Update Status to unpaid
                Billing::where('id', $value->billing_id)
                    ->update(['status' => 0]);
            }


            $file_name = '';


            //Insert Payment
            $dataPayment = [
                'transfer_time' => $request->transfer_time,
                'payment_billing' => $request->nominal,
                'notes' => $request->notes,
                'updated_at' => date('Y-m-d')
            ];
            if ($request->hasFile('file')) {
                $file_name = $request->file('file')->getClientOriginalName();
                $formating_datetime1 = strtotime($request->transfer_time);
                $formating_datetime2 = date("Y_m_d_H:i:s", $formating_datetime1);
                //Upload File
                $request->file->move(public_path('uploads'), 'Bulking_Payment_' . $formating_datetime2 . '_' . $request->student_id . '_' . $file_name);
                $dataPayment['link_prove'] = 'Bulking_Payment_' . $formating_datetime2 . '_' . $request->student_id . '_' . $file_name;
            } else {
                $file_name = 'no file!';
            }

            Payment::where('id', $id)->update($dataPayment);

            if ($request->nominal_sedekah != 0) {
                $nominal = $request->nominal - $request->nominal_sedekah;
            } else {
                $nominal = $request->nominal;
            }

            Payment_Detail::where('payment_id', $id)->Delete();

            $new_payment_detail = [];
            foreach ($request->billing_ids as $key => $value) {
                //Update Status to Waiting for verification
                Billing::where('id', $value)
                    ->update(['status' => 1]);
                $dataPaymentDet = [
                    'payment_id' => $id,
                    'student_id' => $request->student_id,
                    'billing_id' => $value,
                    'nominal' => $request->billing_biayas[$key],
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ];
                array_push($new_payment_detail, $dataPaymentDet);
            }

            //Insert Payment Detail
            $save2 = Payment_Detail::insert($new_payment_detail);

            if ($request->nominal_sedekah != 0) {
                //Insert Payment Other
                $dataPaymentOther = [
                    'payment_id' => $id,
                    'student_id' => $request->student_id,
                    // 'billing_id' => null,
                    'notes' => $request->notes_sedekah,
                    'nominal' => $request->nominal_sedekah,
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d')
                ];
                // $save = Payment_Other::insert($dataPaymentOther);
                $save3 = Payment_Detail::insert($dataPaymentOther);
            }

            // redirect
            if ($id && $save2) {
                return redirect()->back()->with(["success" => "Update Data"]);
            } else {

                return redirect()->back()->with(["error" => " Update Data Failed"]);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        // // redirect
        // if ($save) {
        //     return redirect()->back()->with(["success" => "Update Data"]);
        // } else {

        //     return redirect()->back()->with(["error" => " Update Data Failed"]);
        // }
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

    public function statusUpdate($idEncrypted)
    {
        $id = \EncryptionHelper::instance()->decrypt($idEncrypted);

        $update = Payment::where('id', $id)->update(['verified' => true]);

        if ($update) {
            return redirect()->route('payment.index')->with(["success" => "Status Updated Successfully"]);
        } else {
            return redirect()->route('payment.index')->with(["success" => "Status Update Failed"]);
        }
    }
}
