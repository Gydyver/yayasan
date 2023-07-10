@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Billing</h3>
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

        @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
        @endif
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Student</th>
                    <th>Billing</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal Add -->
<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('billing.generateMonthlyBilling') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Billing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Student:</label>
                                <select class="form-control select2" style="width: 100%;" name="student_id">
                                    <option value="null">--</option>
                                    @foreach ($students as $student)
                                    <option value="{{ $student->id}}">{{ $student->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Month: *</label>
                                <select class="form-control select2" style="width: 100%;" name="month">
                                    <option>--</option>
                                    @foreach ($months as $month)
                                    <option value="{{ $month}}">{{ $month }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Year: *</label>
                                <select class="form-control select2" style="width: 100%;" name="year">
                                    <option>--</option>
                                    @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>

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


<!-- Modal Detail -->
<div class="modal fade" id="ModalDetailBilling" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Billing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p>Student Name : </p>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p id="name"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p>Billing : </p>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p id="billing_total"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p>Month Year : </p>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <p id="month_year"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {


        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('billing.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'student_label',
                    name: 'Student Name'
                },
                {
                    data: 'billing',
                    name: 'Billing'
                },
                {
                    data: 'month',
                    name: 'Month'
                },
                {
                    data: 'year',
                    name: 'Year'
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
                    window.location.href = "{{ url('billing/destroy/') }}/" + id;
                });
            } else {
                swal("Cancelled", "Your data is safe :)", "error");
            }
        })

    }


    function detailBilling(button) {
        var item = ($(button).data('item'));

        console.log(item);
        console.log(item.students.name);
        console.log(item.billing);
        console.log(item.monthName);
        console.log(item.year);


        $('#name').html(item.students.name);
        $('#billing_total').html(item.billing);
        $('#month_year').html(item.monthName + ' ' + item.year);
    }

    // $('.yearpicker').yearpicker();


    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#myInput').trigger('focus')
    // })
</script>
@endsection