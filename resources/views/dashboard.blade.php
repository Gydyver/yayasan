@extends('app/layout')

@section('content')
<div class="card">
    @if(Auth::User()->usergroup_id == 1)
    <div>
    </div>

    @elseif(Auth::User()->usergroup_id == 2)
    <div>
    </div>

    @elseif(Auth::User()->usergroup_id == 3)
    <div>
    </div>
    @else

    @endif

</div>
@endsection