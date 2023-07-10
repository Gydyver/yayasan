@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Access</h3>
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
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>
</div>


<!-- Modal Update -->
<div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('useraccess.update') }}" id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User Access</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="usergroup_id" name="usergroup_id" />
                    @foreach($menusWithChildren as $menu_p)
                    <!-- <div class="row">
                            <span style="font-weight: bold;padding: 5px 0px;">{{$menu_p->name}}</spa>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                <input class="form-check-input" name="`read_{{$menu_p->id}}`" type="checkbox" id="`accessRead_{{$menu_p->id}}`" value="option1">
                                <label class="form-check-label" for="`accessRead_{{$menu_p->id}}`">ACT Read</label>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                <input class="form-check-input" name="`create_{{$menu_p->id}}`" type="checkbox" id="`accessCreate_{{$menu_p->id}}`" value="option2">
                                <label class="form-check-label" for="`accessCreate_{{$menu_p->id}}`">ACT Create</label>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                <input class="form-check-input" name="`update_{{$menu_p->id}}`" type="checkbox" id="`accessUpdate_{{$menu_p->id}}`" value="option3">
                                <label class="form-check-label" for="`accessUpdate_{{$menu_p->id}}`">ACT Update</label>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                <input class="form-check-input" name="`delete_{{$menu_p->id}}`" type="checkbox" id="`accessDelete_{{$menu_p->id}}`" value="option4">
                                <label class="form-check-label" for="`accessDelete_{{$menu_p->id}}`">ACT Delete</label>
                            </div>
                        </div> -->
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                            <span style="font-weight: bold;padding: 5px 0px;">{{$menu_p->name}} ({{$menu_p->url}}) </spa>
                        </div>
                        <!-- <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                <input class="form-check-input" name="`form_access_{{$menu_p->id}}`" type="checkbox" id="`access{{$menu_p->id}}`" value="true">
                                <label class="form-check-label" for="`access{{$menu_p->id}}`">Access</label>
                            </div> -->
                        <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                            <input class="form-check-input" name='access_menu_id_[{{$menu_p->id}}]' type="checkbox" id="access_{{$menu_p->id}}" value="true">
                            <label class="form-check-label" for="access{{$menu_p->id}}">Active</label>
                        </div>
                    </div>

                    @if ($menu_p->children->count())
                    @foreach($menu_p->children as $menu_c)
                    <div class="row" style="padding-left : 50px;">
                        <!-- <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                        <span style="padding: 5px 0px;">{{$menu_c->name}}</span>
                                    </div> -->
                        <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                            <input class="form-check-input" name='access_menu_id_[{{$menu_c->id}}]' type="checkbox" id="access_{{$menu_c->id}}" value="true">
                            <label class="form-check-label" for="access_{{$menu_c->id}}">{{$menu_c->name}} ({{$menu_c->url}})</label>
                        </div>
                    </div>

                    <!-- <div class="row" style="padding-left : 50px;">
                                        <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                            <span style="padding: 5px 0px;">{{$menu_c->name}}</span>
                                        </div>
                                    </div>-->
                    <!-- <div class="row" style="padding-left : 50px;">
                                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                        <input class="form-check-input" name="`read_{{$menu_c->id}}`" type="checkbox" id="`accessRead_{{$menu_c->id}}`" value="option1">
                                        <label class="form-check-label" for="`accessRead_{{$menu_c->id}}`">ACT Read</label>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                        <input class="form-check-input" name="`create_{{$menu_c->id}}`" type="checkbox" id="`accessCreate_{{$menu_c->id}}`" value="option2">
                                        <label class="form-check-label" for="`accessCreate_{{$menu_c->id}}`">ACT Create</label>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                        <input class="form-check-input" name="`update_{{$menu_c->id}}`" type="checkbox" id="`accessUpdate_{{$menu_c->id}}`" value="option3">
                                        <label class="form-check-label" for="`accessUpdate_{{$menu_c->id}}`">ACT Update</label>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
                                        <input class="form-check-input" name="`delete_{{$menu_c->id}}`" type="checkbox" id="`accessDelete_{{$menu_c->id}}`" value="option4">
                                        <label class="form-check-label" for="`accessDelete_{{$menu_c->id}}`">ACT Delete</label>
                                    </div>
                                
                                </div> -->
                    @endforeach
                    @endif
                    <br>
                    @endforeach
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

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('useraccess.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
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
                    text: 'useraccess Deleted successfully!',
                    icon: 'success'
                }).then(function() {
                    window.location.href = "{{ url('useraccess/destroy/') }}/" + id;
                });
            } else {
                swal("Cancelled", "Your data is safe :)", "error");
            }
        })

    }

    function updateData(button) {
        // $('#formEdit .form-group .selectpicker option').removeAttr('selected');
        var item = ($(button).data('item'));

        this.getExistingData(item.id);
        $('#formEdit #usergroup_id').val(item.id);
    }

    function getExistingData(id) {
        console.log();
        $.ajax({
            type: 'GET', //THIS NEEDS TO BE GET
            // url: "{{ route('useraccess.detail', "+id+") }}",
            url: '/useraccess/detail/' + id,
            success: function(data) {
                var menus = JSON.parse(data);
                menus.forEach(el => {
                    $('#access_' + el.menu_id).prop('checked', true);
                    console.log('#access_' + el.menu_id);
                });
            },
            error: function() {
                console.log(data);
            }
        });
    }

    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#myInput').trigger('focus')
    // })
</script>
@endsection