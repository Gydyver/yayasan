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
                    <th>Day</th>
                    <th>Status</th>
                    <th>Time Start</th>
                    <th>Time End</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal Update -->
    <div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('class.sessionGenerated.update') }}" id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Session Generated</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" />
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Teacher:</strong>
                                    <select type="number" name="teacher_id" id="teacher_id" class="form-control" placeholder="Teacher">
                                        <option value="">--</option>
                                        @foreach ($teachers as $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Status:</strong>
                                    <select type="number" name="status" id="status" class="form-control" placeholder="Status">
                                        <!-- <option value="">--</option> -->
                                        <option value="0">Aktif</option>
                                        <option value="1">Selesai</option>
                                        <option value="2">Batalkan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Start Time:</strong>
                                    <div class="input-group date" id="session_start_update" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="session_start" id="session_start" data-target="#session_start_update" />
                                        <div class="input-group-append" data-target="#session_start_update" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>End Time:</strong>
                                    <div class="input-group date" id="session_end_update" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="session_end" id="session_end" data-target="#session_end_update" />
                                        <div class="input-group-append" data-target="#session_end_update" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
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
            console.log('idEncrypted');
            console.log(idEncrypted);
            console.log("{{ url('/session/getDatatableSesGenerated/list') }}" + '/' + idEncrypted);
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/session/getDatatableSesGenerated/list') }}" + '/' + idEncrypted,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'day_label',
                        name: 'Day'
                    },
                    {
                        data: 'status_label',
                        name: 'Status'
                    },
                    {
                        data: 'session_start',
                        name: 'Time Start'
                    },
                    {
                        data: 'session_end',
                        name: 'Time End'
                    },
                    {
                        data: 'action',
                        name: 'Action'
                    },
                ]
            });


            $('#session_start_update').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                },
                format: 'YYYY-MM-DD HH:mm:ss'
            });


            $('#session_end_update').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                },
                format: 'YYYY-MM-DD HH:mm:ss'
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
                        window.location.href = "{{ url('/class/sessionGenerated/destroy/') }}/" + id;
                    });
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            })

        }

        function updateData(button) {
            var item = $(button).data('item');
            console.log(item);
            $('#formEdit #id').val(item.id);
            $('#formEdit #teacher_id').val(item.teacher_id);
            $('#formEdit #status').val(item.status);
            $('#session_start_update').val(item.session_start)
            $('#session_end_update').val(item.session_end)
            $('#formEdit #session_start').val(item.session_start);
            $('#formEdit #session_end').val(item.session_end);
        }


        // $('#myModal').on('shown.bs.modal', function () {
        //     $('#myInput').trigger('focus')
        // })
    </script>
    @endsection