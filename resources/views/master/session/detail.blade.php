@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Session Generated</h3>
    </div>
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        <div class="row">
            <div class="col-md-1">
                <p>Class : </p>
            </div>
            <div class="col-md-11">
                <p>{{$session[0]->classes->name}}</p>

            </div>

        </div>

        <div class="row">
            <div class="col-md-1">
                <p>Session : </p>
            </div>
            <div class="col-md-11">
                <p>{{$session[0]->name}}</p>
            </div>

        </div>
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Time Start</th>
                    <th>Time End</th>
                    <!-- <th width="280px">Action</th> -->
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    @endsection

    @section('script')
    <script>
        $(document).ready(function() {
            // $('.select2').select2({
            //     placeholder: "Please select data"
            // })

            // $('#timepicker_start_create').datetimepicker({
            //     format: 'HH:mm'
            // });

            // $('#timepicker_end_create').datetimepicker({
            //     format: 'HH:mm'
            // });

            // $('#timepicker_start_div').datetimepicker({
            //     format: 'HH:mm'
            // });

            // $('#timepicker_end_div').datetimepicker({
            //     format: 'HH:mm'
            // });


            // $('#start_date_in').datetimepicker({
            //     format: 'YYYY-MM-DD'
            // });


            // $('#end_date_in').datetimepicker({
            //     format: 'YYYY-MM-DD'
            // });


            var idEncrypted = "{{request('idEncrypted')}}"
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/session/getDatatableSesGenerated/list') }}" + '/' + idEncrypted,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'session_start',
                        name: 'Time Start'
                    },
                    {
                        data: 'session_end',
                        name: 'Time End'
                    },
                ]
            });
        });
        // $(document).ready(function(){
        //     $('#tabel_data').DataTable({
        //         ajax: '../ajax/data/arrays.txt'
        //     });
        // });

        function myFunction(button) {
            var item = $(button).data('item');
            $('#formGenerate #class_id_gen').val(item.name);
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
                        text: 'Class Deleted successfully!',
                        icon: 'success'
                    }).then(function() {
                        window.location.href = "{{ url('session/destroy/') }}/" + id;
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            })

        }

        function updateData(button) {
            var item = $(button).data('item');
            $('#formEdit #session_id').val(item.id);
            $('#formEdit #name').val(item.name);
            $('#class_id').val(item.class_id).select2();
            $('#formEdit #time_start').val(item.time_start);
            $('#formEdit #time_end').val(item.time_end);
        }


        // $('#myModal').on('shown.bs.modal', function () {
        //     $('#myInput').trigger('focus')
        // })
    </script>
    @endsection