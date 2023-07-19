@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Class</h3>
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
                <p>{{$class[0]->name}}</p>
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

        <div class="row">
            <div class="col-md-1">
                <p>Start Time : </p>
            </div>
            <div class="col-md-11">
                <p>{{$session_generated[0]->session_start}}</p>

            </div>

        </div>
        <div class="row">
            <div class="col-md-1">
                <p>End Time : </p>
            </div>
            <div class="col-md-11">
                <p>{{$session_generated[0]->session_end}}</p>

            </div>

        </div>

        {{request()->route('id')}}
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Grade</th>
                    <th>Point</th>
                    <th>Notes</th>
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

            var idEncrypted = "{{request('idEncrypted')}}"

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('history/getDatatableHistory/list') }}" + '/' + idEncrypted,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'grade_label',
                        name: 'Grade'
                    },
                    {
                        data: 'point',
                        name: 'Point'
                    },
                    {
                        data: 'teacher_notes',
                        name: 'Catatan Guru'
                    }
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


        // $('#myModal').on('shown.bs.modal', function () {
        //     $('#myInput').trigger('focus')
        // })
    </script>
    @endsection