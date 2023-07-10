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

        {{request()->route('id')}}
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Day</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th width="280px">Action</th>
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
                ajax: "{{ url('teacher/class/session/list') }}" + '/' + idEncrypted,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'day',
                        name: 'Day'
                    },
                    {
                        data: 'session_start',
                        name: 'Start'
                    },
                    {
                        data: 'session_end',
                        name: 'End'
                    },
                    {
                        data: 'status',
                        name: 'Status'
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