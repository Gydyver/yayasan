@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Class</h3>
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
                <th>Class Start</th>
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
    $(document).ready(function(){    
      
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teacher.class.list') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'class_start', name: 'class_start'},
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