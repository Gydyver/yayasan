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
                        <input type="file" name="file" id="file" class="form-control" placeholder="File"  accept="application/pdf,image/png, image/gif, image/jpeg">
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
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="row">
                        <div class="col-xs-9 col-sm-9 col-md-9">
                            <div class="form-group">
                                <strong>Tagihan:</strong>
                                <select name="billing_id[]" id="billing_id" style="width:100%" class="form-control" placeholder="Tagihan">
                                    @foreach ($billings as $billing)
                                        <option value="{{$billing->id}}">{{$billing->month}} {{$billing->year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <buttton id="btn-remove" style="width:100%">Hapus</button>
                        </div>
                    </div> 
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                        <buttton id="btn-add" style="width:100%">Tambah Tagihan</button>
                </div>
            <div>
            <hr>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
     
     
    </div>
</div>


@endsection

@section('script')
    <script>
        $(document).ready(function(){
         
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('upload_payreceipt.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'month_year', name: 'Bulan Tahun'},
                    {data: 'billing', name: 'Total Billing'},
                    {data: 'status', name: 'Status'},
                    {data: 'tanggal_transfer', name: 'Tanggal Transfer'},
                    {data: 'total_transfer', name: 'Total Transfer'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false
                    },
                ]
            });

            $("#btn-add").on('click', add);
            $('.btn-remove').on('click', remove);
        

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

        function updateData(button) {
            // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
            var item = ($(button).data('item'));
            console.log(item);

            // item = JSON.parse(item)
            // $('#formEdit').attr('action', '{{ route('usergroup.edit') }}');
            // $('#formEdit').attr('action', '{{ route('usergroup.edit') }}');
            $('#formUploadFile #billing_id').val(item.id);
            $('#formUploadFile #student_id').val(item.id);
        }


        // $('#myModal').on('shown.bs.modal', function () {
        //     $('#myInput').trigger('focus')
        // })
    </script>
@endsection