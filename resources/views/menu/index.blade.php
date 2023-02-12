@extends('app/layout')
@section('content')
    <div class="container mt-2">
            <!-- Button trigger modal -->
            Launch demo modal
            </button>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Laravel 9 CRUD Example Tutorial</h2>
                    </div>
                    <div class="pull-right mb-2">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Create</button>
                    </div>
                </div>
            </div>
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

        <!-- Modal Add -->
        <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('Menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Name:</strong>
                                        <input type="text" name="name" class="form-control" placeholder="Menu">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Menu Parent:</strong>
                                        <select name="menuparent_id" class="form-control">
                                            @foreach ($menu_parents as $menu_parent)
                                                <option value="{{$menu_parent->id}}">{{$menu_parent->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Url:</strong>
                                        <input type="text" name="url" class="form-control" placeholder="Menu">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Icon:</strong>
                                        <input type="text" name="icon" class="form-control" placeholder="Menu">
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
                <form action="{{ route('Menu.update') }}" id="formEdit" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="Menu_id" name="id"> 
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Name:</strong>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Menu">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Menu Parent:</strong>
                                        <select name="menuparent_id" id="menuparent_id" class="form-control">
                                            @foreach ($menu_parents as $menu_parent)
                                                <option value="{{$menu_parent->id}}">{{$menu_parent->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Url:</strong>
                                        <input type="text" name="url" id="url" class="form-control" placeholder="Menu">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Icon:</strong>
                                        <input type="text" name="icon"  id="icon" class="form-control" placeholder="Menu">
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
    </div>
@endsection
<script>
    $(document).ready(function(){
    
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('menu.list') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
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
                    text: 'Menu Deleted successfully!',
                    icon: 'success'
                }).then(function() {
                    window.location.href = "{{ url('menu/destroy/') }}/" + id;
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
        // $('#formEdit').attr('action', '{{ route('Menugroup.edit') }}');
        // $('#formEdit').attr('action', '{{ route('Menugroup.edit') }}');
        $('#formEdit #id').val(item.id);
        $('#formEdit #name').val(item.name);
        $('#formEdit #menuparent_id').val(item.menuparent_id);
        $('#formEdit #url').val(item.url);
        $('#formEdit #icon').val(item.icon);
    }


    // $('#myModal').on('shown.bs.modal', function () {
    //     $('#myInput').trigger('focus')
    // })
</script>