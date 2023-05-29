@extends('app/layout')

@section('content')
<div class="card">
    <div class="card-header d-flex">
        <h3 class="card-title">Student</h3>
        <div class="pull-right mb-2">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalAdd">Create</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){    
        
    });
</script>
@endsection