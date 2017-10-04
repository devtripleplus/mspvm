@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">New Resource Pool</a>
    </li>
@endsection

@section('content')
   {!! Form::open() !!}

   <div class="form-group">
       <label>Disk (MB)</label>
       {!! Form::text('disk', null, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>RAM (MB)</label>
       {!! Form::text('ram', null, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>Swap (MB)</label>
       {!! Form::text('swap', null, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>IPs</label>
       {!! Form::text('ips', null, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>Package</label>
       {!! Form::select('package_id', $packages, null, ['class' => 'form-control']) !!}
   </div>

   <div class="form-group">
       <label>User</label>
       {!! Form::select('user_id', $users, null, ['class' => 'form-control']) !!}
   </div>

   <input type="submit" class="btn btn-primary pull-right" value="Submit">

    {!! Form::close() !!}
@endsection