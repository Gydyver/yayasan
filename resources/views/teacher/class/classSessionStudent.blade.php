@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Point History</h3>
        <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Add</button>
        </div>
    </div>
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <!-- @if ($message = Session::get('errorMessage'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif -->

        @if(session('errorMessageDuration'))
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session('errorMessageDuration') }}
            {{ Input::get('title') }}
        </div>
        @endif


        <p>Class Name : {{$class_info->name}}</p>
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Point</th>
                    <!-- <th width="280px">Action</th> -->
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <!-- Modal Add -->
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('teacherclass.session.history.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="session_generated_id" value="{{$decrypted}}" class="form-control">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Student:</strong>
                                    <select name="student_id" id="student_id" class="form-control">
                                        @foreach ($students as $student)
                                        <option value="">--</option>
                                        <option value="{{$student->id}}">{{$student->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div style="padding:20px;">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <p>Point History</p>
                                </div>
                            </div>
                            <div id="point_history_container">
                            </div>
                            <hr>
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

            var idSession = "{{request('idEncrypted')}}"
            var idClass = "{{request('idEncryptedClass')}}"
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/teacher/class/session/point/list/') }}" + '/' + idSession,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'Day'
                    },
                    {
                        data: 'point_history',
                        name: 'Point History'
                    },
                    // {
                    //     data: 'action', 
                    //     name: 'action', 
                    //     orderable: false, 
                    //     searchable: false
                    // },
                ]
            });



        });

        function updateData(button) {
            // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
            var item = $(button).data('item');
            console.log(item)
            $('#formEdit #class_id').val(item.id);
            $('#formEdit #name').val(item.name);
            $('#chapter_id').val(item.chapter_id).select2();
            $('#class_type_id').val(item.class_type_id).select2();
            $('#teacher_id').val(item.teacher_id).select2();
            $('#formEdit #class_start').val(item.class_start);

        }

        $('#student_id').change(function() {
            console.log('studentid changed');
            console.log($(this).val());
            $.ajax({
                // "{{ url('/teacher/class/session/point/list/') }}" + '/' + idClass,
                url: "{{ url('/teacher/class/session/getPointAspectStudent') }}" + "/" + $(this).val(),
                type: 'get',
                data: {
                    id: $(this).val()
                },
                success: function(response) {
                    console.log('response');
                    console.log(response);
                    var html = '';
                    if (response.length > 0) {
                        response.forEach(element => {
                            html += `<hr>
                            <div class="row">
                                <strong>` +
                                element.name +
                                `</strong>
                                <input type="hidden" name="point_aspect_id[]" value="` +
                                element.id +
                                `" class="form-control" placeholder="Point">
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <p>Point</p>
                                        <input type="text" name="point[]" class="form-control" placeholder="Point">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <p>Notes</p>
                                        <textarea class="form-control" name="notes[]" rows="3" class=""></textarea>
                                    </div>
                                </div>
                            </div>`
                        });
                    }

                    // var html = 


                    $('#point_history_container').append(html)
                }
            });
            // var Biaya = $(this).find(':selected').attr('data-Biaya')

        });


        // $('#myModal').on('shown.bs.modal', function () {
        //     $('#myInput').trigger('focus')
        // })
    </script>
    @endsection