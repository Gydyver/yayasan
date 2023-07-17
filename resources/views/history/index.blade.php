@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">History</h3>
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
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Class</th>
                    <th>Teacher</th>
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
    @endsection

    @section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select data"
            })

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('history_student.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'class_label',
                        name: 'Kelas'
                    },
                    {
                        data: 'teacher_label',
                        name: 'Guru'
                    },
                    {
                        data: 'status_label',
                        name: 'Status Kelas'
                    },
                    {
                        data: 'session_start',
                        name: 'class_start'
                    },
                    {
                        data: 'session_end',
                        name: 'class_end'
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
            console.log(item)
            $('#formEdit #class_id').val(item.id);
            $('#formEdit #name').val(item.name);
            $('#class_type_id').val(item.class_type_id).select2();
            $('#teacher_id').val(item.teacher_id).select2();
            $('#formEdit #class_start').val(item.class_start);

        }

        function closeClass(button) {
            // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
            var item = $(button).data('item');
            console.log(item)

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            // today = mm + '/' + dd + '/' + yyyy;
            today_date = yyyy + '-' + mm + '-' + dd;
            $('#formClose #class_id').val(item.id);
            $('#formClose #class_end').val(today_date);
            $('#class_end_div').val(today_date)
        }


        // $('#myModal').on('shown.bs.modal', function () {
        //     $('#myInput').trigger('focus')
        // })
    </script>
    @endsection