@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">IPs</a>
    </li>
@endsection

@section('content')
    {!! Form::open() !!}

    <div class="form-group">
        <label>Address/Range</label>
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Submit">

    {!! Form::close() !!}
@endsection