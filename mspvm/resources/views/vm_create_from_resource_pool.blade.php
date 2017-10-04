@extends('main')

@section('navigation')
    <li>
        <a href="#">VMs</a>
    </li>
@endsection

@section('content')
    {!! Form::open() !!}

    <div class="form-group">
        <label>Hostname</label>
        {!! Form::text('hostname', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Storage (MB)</label>
        {!! Form::text('disk', null, ['class' => 'form-control', 'placeholder' => $resource_pool->getFreeDisk()]) !!}
    </div>

    <div class="form-group">
        <label>RAM (MB)</label>
        {!! Form::text('ram', null, ['class' => 'form-control', 'placeholder' => $resource_pool->getFreeRAM()]) !!}
    </div>

    <div class="form-group">
        <label>Swap (MB)</label>
        {!! Form::text('swap', null, ['class' => 'form-control', 'placeholder' => $resource_pool->getFreeSwap()]) !!}
    </div>

    <div class="form-group">
        <label>Password</label>
        {!! Form::password('password', ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Server</label>
        {!! Form::select('server_id', $servers, null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Template</label>
        {!! Form::select('template_id', $templates, null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>IP</label>
        {!! Form::select('ip_id', $ips, null, ['class' => 'form-control', 'multiselect' => 'true']) !!}
    </div>

    <div class="form-group">
        <label>DNS 1</label>
        {!! Form::text('dns1', '8.8.8.8', ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>DNS 2</label>
        {!! Form::text('dns2', '8.8.4.4', ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Submit">

    {!! Form::close() !!}
@endsection