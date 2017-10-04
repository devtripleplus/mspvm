@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Users</a>
    </li>
@endsection

@section('content')
    {!! Form::open(['files' => true]) !!}

    <div class="form-group">
        <label>Username</label>
        {!! Form::text('username', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Email</label>
        {!! Form::text('email_address', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Password</label>
        {!! Form::text('password', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Group</label>
        {!! Form::select('access_level', [1 => 'Client', 2 => 'Support', 3 => 'Admin'], null, ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Submit">

    {!! Form::close() !!}
@endsection