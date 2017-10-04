@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Packages</a>
    </li>
@endsection

@section('content')
    {!! Form::model($package) !!}

    <div class="form-group">
        <label>Name</label>
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>RAM (MB)</label>
        {!! Form::text('ram', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Burst (MB)</label>
        {!! Form::text('burst', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>SWAP (MB)</label>
        {!! Form::text('swap', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Disk (GB)</label>
        {!! Form::text('disk', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>CPUs</label>
        {!! Form::text('cpus', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>CPU Units</label>
        {!! Form::text('cpu_units', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>CPU Limit</label>
        {!! Form::text('cpu_limit', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Bandwidth Limit (GB)</label>
        {!! Form::text('bandwith_limit', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Inode Limit</label>
        {!! Form::text('inode_limit', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Network Speed</label>
        {!! Form::select('network_speed', [10 => '10MBps', 100 => '100Mbps', 1000 => '1Gbps'], null, ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Update">

    {!! Form::close() !!}
@endsection

@section('actionbar')
    <a href="{{route('admin.package-delete', ['package_id' => $package->id])}}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
@endsection