@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">New Backup Server</a>
    </li>
@endsection

@section('content')
   {!! Form::open() !!}

   <div class="form-group">
       <label>Host</label>
       {!! Form::text('host', null, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>User</label>
       {!! Form::text('user', null, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>Password</label>
       {!! Form::password('password', ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>Port</label>
       {!! Form::text('port', 22, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>Directory</label>
       {!! Form::text('directory', '/', ['class' => 'form-control']) !!}
   </div>

   <input type="submit" class="btn btn-primary pull-right" value="Submit">

    {!! Form::close() !!}
@endsection