@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Settings</a>
    </li>
    <li>
        <a href="#">General</a>
    </li>
@endsection

@section('content')
    {!! Form::open( ['method' => 'post', 'files' => true] ) !!}

    <div class="form-group">
        <label>Site Logo</label>
        <input type="file" name="logo">
    </div>

    <div class="form-group">
        <label>Site Title</label>
        {!! Form::text('title', settings('title'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Support URL</label>
        {!! Form::text('supporturl', settings('supporturl'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>System Timezone</label>
        {!! Form::text('timezone', settings('timezone'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Admin Email</label>
        <p>
            Used for contact form email.
        </p>
        {!! Form::text('email', settings('email'), ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Update">

    {!! Form::close() !!}
@endsection