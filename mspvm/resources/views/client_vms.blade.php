@extends('main')

@section('content')
    @foreach ($servers as $server)
        <div class="panel">
            <div class="panel-heading">
                {{$server->getName()}}
                <a href="{{route('vm', ['vm_id' => $server->id])}}" class="btn btn-primary pull-right">
                    <i class="fa fa-cogs"></i> Manage
                </a>
            </div>
            <div class="panel-body">
                {!! $server->getShortDetails()!!}
            </div>
        </div>
    @endforeach
@endsection