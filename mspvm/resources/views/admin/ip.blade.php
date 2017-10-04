@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">IPs</a>
    </li>
@endsection

@section('content')
    <div class="well">
        {{$ip->ip_address}}
    </div>
@endsection

@section('actionbar')
    <a href="{{route('admin.ip-delete', ['ip_id' => $ip->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
@endsection