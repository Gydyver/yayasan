@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment</h3>
        <!-- <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Add</button>
        </div> -->
        <div class="pull-right mb-2">
            <a href='/upload_payreceipt/formUploadBulking' class='btn btn-sm btn-primary'>Upload File Bulking</a>
        </div>
    </div>
    <!-- /.card-header -->

    <div class="card-body">
        Payment
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
        <table id="billing" class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Payment</th>
                    <th>Tanggal Transfer</th>
                    <th>Status</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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