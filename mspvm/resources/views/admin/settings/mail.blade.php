@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Settings</a>
    </li>
    <li>
        <a href="#">Email Settings</a>
    </li>
@endsection

@section('content')
    {!! Form::open() !!}

    <div class="form-group">
        <label>Method</label>
        {!! Form::select('method', [
            'sendmail' => 'PHP sendmail',
            'smtp' => 'SMTP'
        ], settings('mail.method'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Host</label>
        {!! Form::text('host', settings('mail.host'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Port</label>
        {!! Form::text('port', settings('mail.port'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Security</label>
        {!! Form::select('security', [
            'none' => 'None',
            'tls' => 'TLS',
            'ssl' => 'SSL'
        ], settings('mail.security'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>User</label>
        {!! Form::text('user', settings('mail.user'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Password</label>
        {!! Form::text('password', settings('mail.password'), ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Update">

    {!! Form::close() !!}
@endsection