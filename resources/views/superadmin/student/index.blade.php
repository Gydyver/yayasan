@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Student</h3>
        <!-- <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Create</button>
        </div> -->
    </div>
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        {{request()->route('id')}}
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Chapter</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <!-- Modal Update Class-->
    <div class="modal fade" id="ModalUpdateClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('superadmin.student.changeClass') }}" id="formChangeClass" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Student Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="student_id" name="student_id">
                        <input type="hidden" id="prev_class_id" name="prev_class_id">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="User" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Previous Class:</strong>
                                    <input type="text" name="Previous Class" id="previous_class" class="form-control" placeholder="Current Class" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>New Class:</strong>
                                    <select class="form-control select2edit" style="width: 100%;" name="class_id" id="class_id">
                                        <option> --</option>
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
                                    <strong>Start Date:</strong>
                                    <div class="input-group date" class="start_date_div" id="start_date_div_1" data-target-input="nearest">
                                        <input name="start_date" type="text" class="form-control datetimepicker-input" data-target="#start_date_div_1">
                                        <div class="input-group-append" data-target="#start_date_div_1" data-toggle="datetimepicker">
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


    <!-- Modal Set Class-->
    <div class="modal fade" id="ModalSetClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('superadmin.student.setClass') }}" id="formSetClass" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Student Class</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="student_id" name="student_id">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="User">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>New Class:</strong>
                                    <select class="form-control select2edit" style="width: 100%;" name="class_id" id="class_id">
                                        <option> --</option>
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
                                    <strong>Start Date:</strong>
                                    <div class="input-group date" class="start_date_div" id="start_date_div_2" data-target-input="nearest">
                                        <input name="start_date" type="text" class="form-control datetimepicker-input" data-target="#start_date_div_2">
                                        <div class="input-group-append" data-target="#start_date_div_2" data-toggle="datetimepicker">
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


    <!-- Modal Update Chapter-->
    <div class="modal fade" id="ModalUpdateChapter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('superadmin.student.changeChapter') }}" id="formChangeChapter" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Student Chapter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="student_id" name="student_id">
                        <input type="hidden" id="prev_chapter_id" name="prev_chapter_id">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="User" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Current Chapter:</strong>
                                    <input type="text" name="previous_chapter" id="previous_chapter" class="form-control" placeholder="Current Chapter" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>New Chapter:</strong>
                                    <select class="form-control select2edit" style="width: 100%;" name="chapter_id" id="chapter_id">
                                        <option> --</option>
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
                                    <strong>Start Date:</strong>
                                    <div class="input-group date" class="start_date_div" id="start_date_div_3" data-target-input="nearest">
                                        <input name="start_date" type="text" class="form-control datetimepicker-input" data-target="#start_date_div_3">
                                        <div class="input-group-append" data-target="#start_date_div_3" data-toggle="datetimepicker">
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


    <!-- Modal Set Chapter-->
    <div class="modal fade" id="ModalSetChapter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('superadmin.student.setChapter') }}" id="formSetChapter" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Student Chapter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="student_id" name="student_id">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="User">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>New Chapter:</strong>
                                    <select class="form-control select2edit" style="width: 100%;" name="chapter_id" id="chapter_id">
                                        <option> --</option>
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
                                    <strong>Start Date:</strong>
                                    <div class="input-group date" class="start_date_div" id="start_date_div_4" data-target-input="nearest">
                                        <input name="start_date" type="text" class="form-control datetimepicker-input" data-target="#start_date_div_4">
                                        <div class="input-group-append" data-target="#start_date_div_4" data-toggle="datetimepicker">
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

            // $('#birth_date_create').datetimepicker({
            //     format: 'YYYY-MM-DD'
            // });

            // $('#join_date_div').datetimepicker({
            //     format: 'YYYY-MM-DD'
            // });

            $('#start_date_div_1').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#start_date_div_2').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#start_date_div_3').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#start_date_div_4').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('superadmin.student.list') }}",
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
                        data: 'chapter_label',
                        name: 'Chapter'
                    },
                    {
                        data: 'gender',
                        name: 'Gender'
                    },
                    {
                        data: 'age',
                        name: 'Age'
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

        function changeClass(button) {
            var item = $(button).data('item');
            console.log(item);
            $('#formChangeClass #student_id').val(item.id);
            $('#formChangeClass #name').val(item.name);
            $('#formChangeClass #previous_class').val(item.student_classes.name);
            // $('#formChangeClass #prev_class_id').val(item.student_classes.id);
            $('#class_id option[value="' + item.student_classes.id + '"]').attr("disabled", true);
        }

        function setClass(button) {
            var item = $(button).data('item');
            console.log(item);
            $('#formSetClass #student_id').val(item.id);
            $('#formSetClass #name').val(item.name);
        }

        function changeChapter(button) {
            var item = $(button).data('item');
            console.log(item);
            $('#formChangeChapter #student_id').val(item.id);
            $('#formChangeChapter #name').val(item.name);
            // $('#formChangeChapter #prev_chapter_id').val(item.student_chapters.id);
            $('#formChangeChapter #previous_chapter').val(item.student_chapters.name);
            $('#chapter_id option[value="' + item.student_chapters.id + '"]').attr("disabled", true);
        }

        function setChapter(button) {
            var item = $(button).data('item');
            console.log(item);
            $('#formSetChapter #student_id').val(item.id);
            $('#formSetChapter #name').val(item.name);
        }


        // $('#myModal').on('shown.bs.modal', function () {
        //     $('#myInput').trigger('focus')
        // })
    </script>
    @endsection