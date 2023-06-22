@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Session</h3>
        <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Create</button>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#ModalGen">Generate</button>
        </div>
    </div>
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Day</th>
                    <th>Time Start</th>
                    <th>Time End</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Modal Generate -->
    <div class="modal fade" id="ModalGen" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('session.generate') }}" id="formGenerate" method="POST">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generate Session Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Class:</strong>
                                    <select class="form-control select2" style="width: 100%;" name="class_id_gen">
                                        <option></option>
                                        @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Date:</label>
                                    <div class="input-group date" id="start_date_in" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#start_date_in" name="start_date">
                                        <div class="input-group-append" data-target="#start_date_in" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Date:</label>
                                    <div class="input-group date" id="end_date_in" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#end_date_in" name="end_date">
                                        <div class="input-group-append" data-target="#end_date_in" data-toggle="datetimepicker">
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


    <!-- Modal Add -->
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('session.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Session</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" class="form-control" placeholder="Session Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Class:</strong>
                                    <select class="form-control select2" style="width: 100%;" name="class_id">
                                        <option></option>
                                        @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Day:</strong>
                                    <select class="form-control select2" style="width: 100%;" name="day">
                                        <option></option>
                                        @foreach ($days as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Time Start:</label>
                                    <div class="input-group date" id="timepicker_start_create" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="time_start" data-target="#timepicker_start_create">
                                        <div class="input-group-append" data-target="#timepicker_start_create" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Time End:</label>
                                    <div class="input-group date" id="timepicker_end_create" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="time_end" data-target="#timepicker_end_create">
                                        <div class="input-group-append" data-target="#timepicker_end_create" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
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

    <!-- Modal Update -->
    <div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('session.update') }}" id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Session</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="session_id" name="id">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Session Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Class:</strong>
                                    <select class="form-control select2edit" style="width: 100%;" name="class_id" id="class_id">
                                        <option></option>
                                        @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Day:</strong>
                                    <select class="form-control select2edit" style="width: 100%;" name="day" id="day">
                                        <option></option>
                                        @foreach ($days as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Time:</label>
                                    <div class="input-group date" id="timepicker_start_div" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="time_start" id="time_start" data-target="#timepicker_start_div">
                                        <div class="input-group-append" data-target="#timepicker_start_div" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Time:</label>
                                    <div class="input-group date" id="timepicker_end_div" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="time_end" id="time_end" data-target="#timepicker_end_div">
                                        <div class="input-group-append" data-target="#timepicker_end_div" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
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
            $('.select2').select2({
                placeholder: "Please select data"
            })

            $('#timepicker_start_create').datetimepicker({
                format: 'HH:mm'
            });

            $('#timepicker_end_create').datetimepicker({
                format: 'HH:mm'
            });

            $('#timepicker_start_div').datetimepicker({
                format: 'HH:mm'
            });

            $('#timepicker_end_div').datetimepicker({
                format: 'HH:mm'
            });


            $('#start_date_in').datetimepicker({
                format: 'YYYY-MM-DD'
            });


            $('#end_date_in').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('session.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'Name'
                    },
                    {
                        data: 'class_label',
                        name: 'Class'
                    },
                    {
                        data: 'day',
                        name: 'Day'
                    },
                    {
                        data: 'time_start',
                        name: 'Time Start'
                    },
                    {
                        data: 'time_end',
                        name: 'Time End'
                    },
                    {
                        data: 'action',
                        name: 'Action',
                        orderable: false,
                        searchable: false
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