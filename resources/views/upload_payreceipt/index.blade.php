@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Receipt</h3>
        <!-- <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Add</button>
        </div> -->
        <div class="pull-right mb-2">
            <a href='/upload_payreceipt/formUploadBulking' class='btn btn-sm btn-primary'>Upload File Bulking</a>
        </div>
    </div>
    <!-- /.card-header -->

    <div class="card-body">
        <!-- <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="home" aria-selected="true">Tagihan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="profile" aria-selected="false">Histori Pembayaran</button>
            </li>
        </ul> -->


        <!-- <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="billing" role="tabpanel" aria-labelledby="billing-tab"> -->
        Billing
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
        <!-- For Billing that still unpaid -->
        <table id="billing" class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bulan Tahun</th>
                    <th>Total Billing</th>
                    <th>Status</th>
                    <th>Tanggal Transfer</th>
                    <!-- <th>Total Transfer</th> -->
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!-- </div> -->
        <!-- For Billing that has been paid -->
        <!-- <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                History
                <table id="paid_billing" class="table table-bordered yajra-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bulan Tahun</th>
                            <th>Total Billing</th>
                            <th>Tanggal Tagihan</th>
                            <th>Tanggal Transfer</th>
                            <th>Total Transfer</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div> -->
    </div>


</div>



<!-- Modal Upload File -->
<div class="modal fade" id="ModalUploadFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('upload_payreceipt.uploadFile') }}" id="formUploadFile" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload File</h5>
                    <br>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="billing_id" name="billing_id">
                    <input type="hidden" id="billing_total" name="billing_total">
                    <input type="hidden" id="student_id" name="student_id">
                    <div class="row">
                        <p>Kelebihan Transfer secara otomatis akan dianggap sedekah. Jika terjadi kesalahan pengiriman data, silahkan hubungi admin yayasan.</p>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Transfer Time:</strong>
                                <div class="input-group date" id="transfer_time_create" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#transfer_time_create" name="transfer_time">
                                    <div class="input-group-append" data-target="#transfer_time_create" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <!-- <input type="date" name="transfer_time" id="transfer_time" class="form-control" placeholder="User Group"> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nominal Transfer:</strong>
                                <input type="number" name="nominal" id="nominal" class="form-control" placeholder="Nominal Transfer">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger notif-lessThanBilling" role="alert">
                        Total Nominal masih kurang dari tagihan
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>File:</strong>
                                <input type="file" name="file" id="file" class="form-control" placeholder="File" accept="application/pdf,image/png, image/gif, image/jpeg">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Notes:</strong>
                                <textarea class="form-control" name="notes" id="notes" rows="3" class=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <button type="button" class="btn btn-primary btn-confirm" onClick="showSedekahForm()">Sedekahkan kelebihan</button>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <button type="button" class="btn btn-primary btn-confirm" onClick="setValuetoBilling()">Kelebihan Direturn</button>

                        </div>
                    </div>
                    <div class="alert alert-danger notif-actionNotChoosen" role="alert">
                        <span id="error-message"></span>
                    </div>
                    <hr class="form-sedekah">
                    <div class="row form-sedekah">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Total Sedekah:</strong>
                                <input type="number" name="nominal_sedekah" id="nominal_sedekah" class="form-control" placeholder="Nominal Sedekah" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row form-sedekah">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Notes Sedekah:</strong>
                                <textarea class="form-control" name="notes_sedekah" rows="3" class=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onClick="resetForm()" data-dismiss="modal">Close</button>
                    <button type="button" onClick="checkForm()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var payment_rest = 0

        $('#transfer_time_create').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('upload_payreceipt.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'month_year',
                    name: 'Bulan Tahun'
                },
                {
                    data: 'billing',
                    name: 'Total Billing'
                },
                {
                    data: 'status',
                    name: 'Status'
                },
                {
                    data: 'tanggal_transfer',
                    name: 'Tanggal Transfer'
                },
                // {
                //     data: 'total_transfer',
                //     name: 'Total Transfer'
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });


    });

    $('input#nominal').on('input', function(e) {
        var billing_total = $('#billing_total').val();
        var nominal = $(this).val()
        payment_rest = billing_total - nominal
        // return payment_rest;
        if (payment_rest != 0 && payment_rest < 0 && nominal != 0) {
            $(".btn-confirm").css("display", "block");
            $(".notif-lessThanBilling").css("display", "none");
        } else if (payment_rest != 0 && payment_rest > 0 && nominal != 0) {
            $(".btn-confirm").css("display", "none");
            $(".notif-lessThanBilling").css("display", "block");
        } else {
            $(".btn-confirm").css("display", "none");
            $(".notif-lessThanBilling").css("display", "none");
        }
        console.log('payment_rest');
        console.log(payment_rest);
    });


    function confirmData(id) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            icon: "warning",
            buttons: [
                'No, cancel it!',
                'Yes, I am sure!'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {

                swal({
                    title: 'Deleted!',
                    text: 'User Group Deleted successfully!',
                    icon: 'success'
                }).then(function() {
                    window.location.href = "{{ url('user_group/destroy/') }}/" + id;
                });
            } else {
                swal("Cancelled", "Your data is safe :)", "error");
            }
        })
    }

    function uploadData(button) {
        // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
        var item = ($(button).data('item'));

        // item = JSON.parse(item)
        $('#formUploadFile #billing_id').val(item.id);
        $('#formUploadFile #student_id').val(item.student_id);
        $('#formUploadFile #billing_total').val(item.billing);

    }

    function calculateSedekah() {
        var billing_total = $('#billing_total').val();
        var nominal = $('#nominal').val()
        payment_rest = nominal - billing_total
        return payment_rest;
    }

    function showSedekahForm() {
        // $('.form-sedekah').display('block')
        var sedekah = this.calculateSedekah();
        $(".form-sedekah").css("display", "block");
        $('#nominal_sedekah').val(sedekah)
    }

    function setValuetoBilling() {
        // $('.form-sedekah').display('none')
        $(".form-sedekah").css("display", "none");
        $('#nominal_sedekah').val(null);
        $('textarea#notes_sedekah').val(null);
        var total_billing = $('#billing_total').val();
        $('#nominal').val(total_billing);
    }

    function checkForm() {
        if ($(".btn-confirm").is(":visible")) {
            $('#error-message').text("Silahkan pilih aksi atas kelebihan dana transfer");
            $('.notif-actionNotChoosen').css("display", "block");
            return false
        }

        $('.notif-actionNotChoosen').css("display", "none");
        $("#formUploadFile").submit();
    }

    function resetForm() {
        $('#transfer_time_create').val(null);
        $('#billing_total').val(null);
        $('#student_id').val(null);
        $('#student_id').val(null);
        $('#nominal').val(null);
        $('#file').val(null);
        $('#nominal_sedekah').val(null);
        $('textarea#notes').val(null);
        $('textarea#notes_sedekah').val(null);

        $('.btn-confirm').css("display", "none");
        $('.form-sedekah').css("display", "none");
    }


    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#myInput').trigger('focus')
    // })
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
</style>
@endsection