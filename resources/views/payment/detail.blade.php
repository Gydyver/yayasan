@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Detail</h3>
        <!-- <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Add</button>
        </div> -->
        <div class="pull-right mb-2">

        </div>
    </div>
    <!-- /.card-header -->

    <div class="card-body">
        @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            <strong>Success</strong> {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-success" role="alert">
            <strong>Error</strong> {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <strong>
                    <div class="row">
                        <div class="col-md-3">Total Payment : </div>
                        <div class="col-md-9">Rp. {{$payment[0]->payment_billing}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">Tanggal Transfer : </div>
                        <div class="col-md-9">{{$payment[0]->transfer_time}}</div>
                    </div>
                </strong>
                <br>
                <h5><strong>Billing List</strong></h5>
                <input type="hidden" id="idEncrypted" value="{{$idEncrypted}}" />
                <table id="billing" class="table table-bordered yajra-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan Tagihan</th>
                            <th>Billing</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <br>
                <h5><strong>Sedekah</strong></h5>
                @if(count($sedekah) != 0)
                <div class="row">
                    <div class="col-md-2">
                        <p>Total Sedekah : </p>
                    </div>
                    <div class="col-md-3">
                        <input type="text" disabled value="Rp. {{$sedekah[0]->nominal}}" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <p>Notes :</p>
                    </div>
                    <div class="col-md-10">
                        @if($sedekah[0]->notes != null)
                        <textarea class="form-control" readonly name="notes_sedekah" rows="3" class="">{{$sedekah[0]->notes}}</textarea>
                        @else
                        <textarea class="form-control" readonly name="notes_sedekah" rows="3" class="">-</textarea>
                        @endif
                    </div>
                </div>
                @else
                <textarea class="form-control" readonly> Tidak ada sedekah pada transaksi ini</textarea>
                @endif
                <br>
            </div>
            <div class="col-md-4">
                <p><strong> Bukti Transfer : </strong> </p>
                <div class="prove_container">
                    <img class="img_prove" src="{{ URL::to('/uploads') }}/{{$payment[0]->link_prove}}" />
                </div>
            </div>
        </div>
        <div class="float-right">
            @if(!$payment[0]->verified && $_SESSION["data"]->usergroup_id != 3)
            <button type="button" class="btn btn-secondary" onclick='window.location=`{{ url("/payment") }}`'>Close</button>
            <button type="button" class="btn btn-primary" onClick="verify()">Verifikasi</button>
            @endif
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Billing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p>Student Name : </p>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p id="name"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p>Billing : </p>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p id="billing_total"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p>Month Year : </p>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p id="month_year"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script>
    $(document).ready(function() {
        var payment_rest = 0

        var id = "{{request('idEncrypted')}}"

        $('#transfer_time_create').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('payment/detail/list') }}" + '/' + id,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'month_tagihan',
                    name: 'Bulan Tagihan'
                },
                {
                    data: 'bilings',
                    name: 'Billing'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });


    function detailBilling(button) {
        var item = ($(button).data('item'));


        $('#name').html(item.billings.studentDetail.name);
        $('#billing_total').html(item.billings.billing);
        $('#month_year').html(item.billings.monthName + ' ' + item.billings.year);
    }

    function verify() {
        var id = $('#idEncrypted').val()
        swal({
            title: 'Verified!',
            text: 'Bukti transfer telah di verifikasi!',
            icon: 'success'
        }).then(function() {
            window.location.href = "{{ url('payment/status_update') }}/" + id;
        });
    }

    // function reject() {
    //     var id = $('#idEncrypted').val()
    //     swal({
    //         title: 'Verified!',
    //         text: 'Bukti transfer telah di tolak, silahkan infokan kepada peserta didik untuk melakukan update atas bukti transfer yang ada',
    //         icon: 'failed'
    //     }).then(function() {
    //         window.location.href = "{{ url('payment/status_update/rejected/') }}/" + id;
    //     });
    //     alert('Bukti Transfer ditolak')
    // }
</script>
<style>
    .form-sedekah {
        display: none;
    }

    .btn-confirm {
        display: none;
    }

    .notif-lessThanBilling {
        display: none;
    }

    .notif-actionNotChoosen {
        display: none;
    }

    /* .prove_container {
        overflow-x: hidden;

    border: solid 2px grey;
    border-radius: 5px;
    height: 300px;
    width: 300px;
    }

    img {
        height: 500px;
    }

    */
</style>
@endsection