@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Templates</a>
    </li>
@endsection

@section('content')
    {!! Form::open(['files' => true]) !!}

    <div class="form-group">
        <label>Name</label>
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Template (URL)</label>
        {!! Form::text('file', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Description</label>
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Virtualization</label>
        {!! Form::select('type', [1 => 'OpenVZ'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Architecture</label>
        {!! Form::select('architecture', [1 => 'x86', 2 => 'x64'], null, ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Submit">

    {!! Form::close() !!}
@endsection