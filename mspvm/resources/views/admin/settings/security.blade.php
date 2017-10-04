@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Settings</a>
    </li>
    <li>
        <a href="#">Security Settings</a>
    </li>
@endsection

@section('content')
    {!! Form::open() !!}

    <div class="form-group">
        <label>Hijack Check Type</label>
        {!! Form::select('hijack-check-type', [
            1 => 'IP + Browser',
            2 => 'IP',
            3 => 'Browser'
        ], settings('hijack-check-type'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Client hijack check</label>
        {!! Form::select('client-hijack-check', [
            1 => 'Enabled',
            0 => 'Disabled'
        ], settings('client-hijack-check'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Admin hijack check</label>
        {!! Form::select('admin-hijack-check', [
            1 => 'Enabled',
            0 => 'Disabled'
        ], settings('admin-hijack-check'), ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Update">

    {!! Form::close() !!}
@endsection