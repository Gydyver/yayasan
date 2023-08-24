@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User</h3>
        <div class="float-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Create</button>
        </div>
    </div>

    <div class="card-body">
        @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            <strong>Success</strong> {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-success" role="alert">
            <strong>Error</strong> {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>User Group</th>
                    <th>Username</th>
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
        <form id="add_user_form" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Username:</strong>
                                <input type="text" onkeydown="return /[a-z]/i.test(event.key)" name="username" class="form-control" placeholder="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>UserGroup:</strong>
                                <select name="usergroup_id" class="form-control">
                                    @foreach ($user_groups as $user_group)
                                    <option value="{{$user_group->id}}">{{$user_group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Class:</strong>
                                <select type="number" name="class_id" class="form-control">
                                    @foreach ($classes as $class)
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="text" name="phone" class="form-control" placeholder="User">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password:</strong>
                                <input type="password" onkeydown="return /[a-z]/i.test(event.key)" name="password" class="form-control" placeholder="User" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Monthly Fee:</strong>
                                <input type="number" name="monthly_fee" class="form-control" placeholder="Monthly Fee">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Gender:</strong>
                                <select name="gender" class="form-control">
                                    <option value="man">Man</option>
                                    <option value="woman">Woman</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Birth Date:</label>
                                <div class="input-group date" id="birth_date_create" data-target-input="nearest">
                                    <input name="birth_date" type="text" class="form-control datetimepicker-input" data-target="#birth_date_create">
                                    <div class="input-group-append" data-target="#birth_date_create" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Join Date:</label>
                                <div class="input-group date" id="join_date_div" data-target-input="nearest">
                                    <input name="join_date" type="text" class="form-control datetimepicker-input" data-target="#join_date_div">
                                    <div class="input-group-append" data-target="#join_date_div" data-toggle="datetimepicker">
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
        <form id="update_user_form" action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="id">
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
                                <strong>Previous Username:</strong>
                                <input type="text" name="username_old" id="username_old" class="form-control" placeholder="Username Previous" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Username:</strong>
                                <input type="text" onkeydown="return /[a-z]/i.test(event.key)" name="username" id="username" class="form-control" placeholder="Username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>UserGroup:</strong>
                                <select type="number" name="usergroup_id" id="usergroup_id" class="form-control" placeholder="Lowest Point">
                                    @foreach ($user_groups as $user_group)
                                    <option value="{{$user_group->id}}">{{$user_group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Class:</strong>
                                <select type="number" name="class_id" id="class_id" class="form-control" placeholder="Lowest Point">
                                    @foreach ($classes as $class)
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="User">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Password:</strong>
                                <input type="password" onkeydown="return /[a-z]/i.test(event.key)" name="password" id="password" class="form-control" placeholder="User">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Monthly Fee:</strong>
                                <input type="number" name="monthly_fee" id="monthly_fee" class="form-control" placeholder="Monthly Fee">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Gender:</strong>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="man">Man</option>
                                    <option value="woman">Woman</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Birth Date:</label>
                                <div class="input-group date" id="birth_date_div" data-target-input="nearest">
                                    <input name="birth_date" type="text" class="form-control datetimepicker-input" id="birth_date" data-target="#birth_date_div">
                                    <div class="input-group-append" data-target="#birth_date_div" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Join Date:</label>
                                <div class="input-group date" id="join_date_div" data-target-input="nearest">
                                    <input name="join_date" type="text" id="join_date" class="form-control datetimepicker-input" data-target="#join_date_div">
                                    <div class="input-group-append" data-target="#join_date_div" data-toggle="datetimepicker">
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
<script src="{{asset('js/jquery.md5.min.js')}}"></script>
<script>
    $(document).ready(function() {

        $('#birth_date_create').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('#join_date_div').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'Name'
                },
                {
                    data: 'usergroup_label',
                    name: 'User Group'
                },
                {
                    data: 'username_freetext',
                    name: 'Username'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#ModalAdd').on('submit', e => {
            e.preventDefault();
            var b_username = $('#add_user_form input[name="username"]').val()
            var b_password = $('#add_user_form input[name="password"]').val()
            // var data = $('#add_user_form').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('user.store') }}",
                data: {
                    name: $('#add_user_form input[name="name"]').val(),
                    username: b_username,
                    password: b_password,
                    phone: $('#add_user_form input[name="phone"]').val(),
                    monthly_fee: $('#add_user_form input[name="monthly_fee"]').val(),
                    gender: $('#add_user_form input[name="gender"]').val(),
                    birth_date: $('#add_user_form input[name="birth_date"]').val(),
                    join_date: $('#add_user_form input[name="join_date"]').val(),
                    usergroup_id: $('#add_user_form select[name="usergroup_id"]').find(":selected").val(),
                    gender: $('#add_user_form select[name="gender"]').find(":selected").val(),
                    // usergroup_id: $('#add_user_form #aioConceptName').find(":selected").val();
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        e.target.submit(); // rule met, allow form submission
                    } else {
                        console.log('Denied...');
                        // show message to user here...
                    }
                }
            });
        });


        $('#ModalUpdate').on('submit', e => {
            e.preventDefault();
            var b_username = $('#update_user_form input[name="username"]').val()
            var b_password = $('#update_user_form input[name="password"]').val()


            var datas = {
                name: $('#update_user_form input[name="name"]').val(),
                id: $('#update_user_form input[name="id"]').val(),
                // username: username,
                // password: password,
                phone: $('#update_user_form input[name="phone"]').val(),
                monthly_fee: $('#update_user_form input[name="monthly_fee"]').val(),
                gender: $('#update_user_form input[name="gender"]').val(),
                birth_date: $('#update_user_form input[name="birth_date"]').val(),
                join_date: $('#update_user_form input[name="join_date"]').val(),
                usergroup_id: $('#update_user_form select[name="usergroup_id"]').find(":selected").val(),
                gender: $('#update_user_form select[name="gender"]').find(":selected").val(),
                // usergroup_id: $('#add_user_form #aioConceptName').find(":selected").val();
                '_token': $('meta[name="csrf-token"]').attr('content')
            }


            if (b_username != null) {
                datas.username = b_username;
            }

            if (b_password != null) {
                datas.password = b_password;
            }
            // var data = $('#update_user_form').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('user.update') }}",
                data: datas,
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        e.target.submit(); // rule met, allow form submission
                    } else {
                        console.log('Denied...');
                        // show message to user here...
                    }
                }
            });
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
                    text: 'User Deleted successfully!',
                    icon: 'success'
                }).then(function() {
                    window.location.href = "{{ url('user/destroy/') }}/" + id;
                });
            } else {
                swal("Cancelled", "Your data is safe :)", "error");
            }
        })

    }

    function updateData(button) {
        // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
        var item = ($(button).data('item'));
        console.log(item);

        // item = JSON.parse(item)
        // $('#formEdit').attr('action', '{{ route('usergroup.edit') }}');
        // $('#formEdit').attr('action', '{{ route('usergroup.edit') }}');
        $('#update_user_form #user_id').val(item.id);
        $('#update_user_form #name').val(item.name);
        $('#update_user_form #username_old').val(item.username);
        $('#update_user_form #class_id').val(item.class_id);
        $('#update_user_form #usergroup_id').val(item.usergroup_id);
        $('#update_user_form #phone').val(item.phone);
        // $('#update_user_form #password').val(item.password);
        $('#update_user_form #monthly_fee').val(item.monthly_fee);
        $('#update_user_form #gender').val(item.gender);
        $('#update_user_form #birth_date').val(item.birth_date);
        $('#update_user_form #join_date').val(item.join_date);
    }
</script>
@endsection