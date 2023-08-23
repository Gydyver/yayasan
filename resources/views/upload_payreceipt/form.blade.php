@extends('app/layout')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Receipt</h3>
        <!-- <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Add</button>
        </div> -->
    </div>
    <!-- /.card-header -->

    <div class="card-body">
        Upload Payment


        <form action="{{ route('upload_payreceipt.uploadFileBulking') }}" id="formUploadFile" method="POST" enctype="multipart/form-data">
            @csrf
            <input name="student_id" id="student_id" type="hidden" value="{{$_SESSION["data"]->id}}" />
            <input name="nominal_sedekah" id="nominal_sedekah_hidden" type="hidden" value="0" />
            <textarea name="notes_sedekah" id="notes_sedekah_hidden" style="display: none;"></textarea>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Transfer Time:</strong>
                        <!-- <div class="input-group date" id="transfer_time_create" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#transfer_time_create" name="transfer_time">
                            <div class="input-group-append" data-target="#transfer_time_create" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div> -->
                        <div class="input-group date" id="transfer_time_create" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" name="transfer_time" data-target="#transfer_time_create" />
                            <div class="input-group-append" data-target="#transfer_time_create" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Nominal:</strong>
                        <input type="number" name="nominal" id="nominal" class="form-control" placeholder="Nominal Transfer">
                    </div>
                </div>
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
                        <textarea class="form-control" name="notes" rows="3" class=""></textarea>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Bulan Tagihan:</strong>
                        <!-- onChange="getBiaya()"  -->
                        <select name="billing_id" id="billing_id" style="width:100%" class="form-control" placeholder="Tagihan">
                            <option value="">--</option>
                            @foreach ($billings as $billing)
                            <option value="{{$billing->id}}|{{getMonthName($billing->month)}} {{$billing->year}}|{{$billing->billing}}" data-Biaya="{{$billing->billing}}">{{$billing->month}} {{$billing->year}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Biaya:</strong>
                        <input type="text" class="form-control" id="billing_tagihan" readonly />
                    </div>
                </div>
                <!-- <div class="col-xs-6 col-sm-6 col-md-6">
                    <button type="button" id="btn-add" style="width:100%">Tambah Tagihan</button>
                </div> -->
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <button type="button" class="btn-primary" id="btn-add" style="width:100%">Tambah Tagihan</button>
                </div>
            </div>
            <hr>
            <div class="row">
                <!-- <div class="col-xs-9 col-sm-9 col-md-9">
                    <div class="form-group">
                        <strong>Tagihan:</strong>
                        <select name="billing_id[]" id="billing_id" style="width:100%" class="form-control" placeholder="Tagihan">
                            @foreach ($billings as $billing)
                            <option value="{{$billing->id}}">{{$billing->month}} {{$billing->year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <button type="button" id="btn-remove" style="width:100%">Hapus</button>
                </div> -->
                <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                    <select name="billing_id[]" id="billing_id" style="width:100%" class="form-control" placeholder="Tagihan"  multiple="multiple">
                        @foreach ($billings as $billing)
                        <option value="{{$billing->id}}">{{$billing->month}} {{$billing->year}}</option>
                        @endforeach
                    </select>
                </div> -->
                <table class="table" id="tagihan_table">
                    <thead>
                        <tr>
                            <td>Bulan Tagihan</td>
                            <td>Biaya</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>


            <div class="float-right">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onClick="savePayment()">Save changes</button>
            </div>
        </form>


    </div>
</div>

<div class="modal fade" id="modalConfirmSedekah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apakah kelebihan dari transfer akan disimpan sebagai sedekah?</h5>
                <br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <button type="button" class="btn btn-primary btn-confirm" onClick="showSedekahForm()">Sedekahkan kelebihan</button>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <button type="button" class="btn btn-primary btn-confirm" onClick="setSedekahNull()" data-dismiss="modal">Kembali ke form sebelumnya</button>
                        <!-- <button type="button" class="btn btn-primary btn-confirm" data-dismiss="modal">Kembali ke form sebelumnya</button> -->


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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onClick="savePayment()">Save changes</button>
            </div>
        </div>



    </div>
</div>


@endsection

@section('script')
<script>
    $(document).ready(function() {

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
                {
                    data: 'total_transfer',
                    name: 'Total Transfer'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $("#btn-add").on('click', add);
        // $('.btn-remove').on('click', remove);
        $("#tagihan_table").on('click', '.remCF', function() {
            $(this).parent().parent().remove();
        });

        $('#nominal').on('keydown', function() {
            $("#nominal").css('border-color', '#ced4da')
        }).keydown();

        $(".btn-confirm").click(function() {
            // alert("btn confirm clicked");
            $('.notif-actionNotChoosen').css("display", "none");
        });


        $('#transfer_time_create').datetimepicker({
            icons: {
                time: 'far fa-clock'
            },
            format: 'YYYY-MM-DD HH:mm:ss'
        });

    });

    // $('input#nominal').on('input', function(e) {
    //     var billing_total = $('#billing_total').val();
    //     var nominal = $(this).val()
    //     payment_rest = billing_total - nominal
    //     // return payment_rest;
    //     if (payment_rest != 0 && payment_rest < 0 && nominal != 0) {
    //         $(".btn-confirm").css("display", "block");
    //         $(".notif-lessThanBilling").css("display", "none");
    //     } else if (payment_rest != 0 && payment_rest > 0 && nominal != 0) {
    //         $(".btn-confirm").css("display", "none");
    //         $(".notif-lessThanBilling").css("display", "block");
    //     } else {
    //         $(".btn-confirm").css("display", "none");
    //         $(".notif-lessThanBilling").css("display", "none");
    //     }
    // });

    function setSedekahNull() {
        $('#nominal_sedekah_hidden').val(null);
        // $('#notes_sedekah_hidden').val(null);
        $('#nominal_sedekah').val(null);
        // $('#notes_sedekah').val(null);
        $('#notes_sedekah').val('');
        $('#notes_sedekah_hidden').val('');
        $(".form-sedekah").css("display", "none");
        $("#modalConfirmSedekah").toggle()
    }

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

    function updateData(button) {
        // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
        var item = ($(button).data('item'));
        // item = JSON.parse(item)
        // $('#formEdit').attr('action', '{{ route('usergroup.edit') }}');
        // $('#formEdit').attr('action', '{{ route('usergroup.edit') }}');
        $('#formUploadFile #billing_id').val(item.id);
        $('#formUploadFile #student_id').val(item.id);
    }

    function add() {
        var nominal = $('#nominal').val();
        if (nominal == "") {
            $("#nominal").css('border-color', '#f80c01')
            alert('Isi nominal terlebih dahulu')
            return;
        }
        var array = $('#billing_id').val().split("|");

        var id = array[0]
        var bulan = array[1]
        var biaya = parseInt(array[2])

        var existingData = $("input[name='billing_ids[]']")
            .map(function() {
                // existingData.push($(this).val());
                return $(this).val()
            }).get();

        //Untuk perhitungan Billing dan nominal
        var billing_biaya = $("input[name='billing_biayas[]']")
            .map(function() {
                // existingData.push($(this).val());
                return parseInt($(this).val())
            }).get();
        const sum = billing_biaya.reduce((partialSum, a) => parseInt(partialSum) + parseInt(a), 0);
        var total_biaya = sum + biaya

        if (existingData.includes(id)) {
            alert('Tagihan ini sudah dimasukan')
        } else if (nominal < total_biaya) {
            $("#nominal").css('border-color', '#f80c01')
            alert('Nominal kurang dari total biaya')
        } else {

            var html = ` <tr> 
                        <td> ` + bulan +
                `<input type="hidden" value="` + id + `" name="billing_ids[]"/></td>
                        <td> ` + biaya +
                ` <input type="hidden" value="` + biaya + `" name="billing_biayas[]"/></td> 
                        <td> <button class="btn-danger remCF" type="button"> Remove</button> </td> 
                        </tr>`;
            $("#tagihan_table tbody").append(html);
        }
    }

    function confirmModal() {
        if ($(".btn-confirm").is(":visible")) {
            $('#error-message').text("Silahkan pilih aksi atas kelebihan dana transfer");
            $('.notif-actionNotChoosen').css("display", "block");
            return false
        }
    }

    function savePayment() {
        var nominal = $('#nominal').val();

        var sedekah = $('#nominal_sedekah').val();
        var notes = $('#notes_sedekah').val();

        $('#nominal_sedekah_hidden').val(sedekah);
        $('#notes_sedekah_hidden').val(notes);

        var billing_biaya = $("input[name='billing_biayas[]']")
            .map(function() {
                // existingData.push($(this).val());
                return parseInt($(this).val())
            }).get();
        var sum = billing_biaya.reduce((partialSum, a) => parseInt(partialSum) + parseInt(a), 0);

        if ($('#nominal_sedekah_hidden').val() != 0) {
            sum = parseInt(sum) + parseInt(sedekah)
        }

        if (sum != nominal) {
            if (sum > nominal) {
                //Nominal dibawah total semua
                alert('Nominal kurang dari total tagihan yang dipilih')
            } else {
                if ($('#modalConfirmSedekah').hasClass('show')) {
                    $('#error-message').text("Silahkan pilih aksi atas kelebihan dana transfer");
                    $('.notif-actionNotChoosen').css("display", "block");
                } else {
                    $("#modalConfirmSedekah").modal()
                }
            }

            return;
        }


        // var sum = 0

        // var billing_biaya = $("input[name='billing_biayas[]']").map(e => sum += e)

        // var totalData = $("input[name='billing_biayas[]']")
        //     .map(function() {
        //         return $(this).val()
        //     }).get();

        $('.notif-actionNotChoosen').css("display", "none");
        $("#modalConfirmSedekah").toggle()
        $("#formUploadFile").submit();

        //LEMPAR KE LIST PAYMENT
    }

    function calculateSedekah() {
        var billing_biaya = $("input[name='billing_biayas[]']")
            .map(function() {
                // existingData.push($(this).val());
                return parseInt($(this).val())
            }).get();
        const billing_total = billing_biaya.reduce((partialSum, a) => parseInt(partialSum) + parseInt(a), 0);
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


    $('#billing_id').change(function() {
        var Biaya = $(this).find(':selected').attr('data-Biaya')

        $('#billing_tagihan').val(Biaya)
    });

    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#myInput').trigger('focus')
    // })
</script>

<style>
    .form-sedekah {
        display: none;
    }

    .btn-confirm {
        display: block;
    }

    .notif-lessThanBilling {
        display: none;
    }

    .notif-actionNotChoosen {
        display: none;
    }
</style>
@endsection