@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Class</h3>
        <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Create</button>
        </div>
    </div>
    <div class="card-body">
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Teacher</th>
                <th>Chapter</th>
                <th>Class Type</th>
                <th>Status</th>
                <th>Class Start</th>
                <th>Class End</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- @foreach ($classes as $class)
                <tr>
                    <td>{{ $class->id }}</td>
                    <td>{{ $class->name }}</td>
                    <td>{{ $class->email }}</td>
                    <td>{{ $class->address }}</td>
                    <td>
                        <form action="{{ route('class.destroy',$class->id) }}" method="Post">
                            <a class="btn btn-primary" href="{{ route('class.edit',$class->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach -->
        </tbody>
    </table>
</div>

<!-- Modal Add -->
<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('class.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" class="form-control" placeholder="Class Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Teacher:</strong>
                                <select class="form-control select2" style="width: 100%;" name="teacher_id">
                                    @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Chapter:</strong>
                                <select class="form-control select2" style="width: 100%;" name="chapter_id">
                                    @foreach ($chapters as $chapter)
                                    <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Class_type:</strong>
                                <select class="form-control select2" style="width: 100%;" name="class_type_id">
                                    @foreach ($class_types as $class_type)
                                    <option value="{{ $class_type->id }}">{{ $class_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Date:</label>
                                <div class="input-group date" id="class_start_create" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#class_start_create">
                                    <div class="input-group-append" data-target="#class_start_create" data-toggle="datetimepicker">
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

<!-- Modal Update -->
<div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('class.update') }}" id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="class_id" name="id"> 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Class Type">
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
    $(document).ready(function(){    
        $('.select2').select2()

        $('#class_start_create').datetimepicker({
            format: 'L'
        });
    
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('class.list') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'address', name: 'address'},
                {
                    data: 'action', 
                    name: 'action', 
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
                    window.location.href = "{{ url('class/destroy/') }}/" + id;
                });
            } else {
                swal("Cancelled", "Your data is safe :)", "error");
            }
        })

    }

    function updateData(button) {
        // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
        var item = $(button).data('item');
        $('#formEdit #class_id').val(item.id);
        $('#formEdit #name').val(item.name);
    }


    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#myInput').trigger('focus')
    // })
</script>
@endsection