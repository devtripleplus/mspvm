@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Servers</a>
    </li>
@endsection

@section('content')
    {!! Form::open(['files' => true]) !!}

    <div class="form-group">
        <label>Name</label>
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>IP</label>
        {!! Form::text('ip', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Authentication</label>
        {!! Form::select('type', [1 => 'Key', 2 => 'Basic'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>User</label>
        {!! Form::text('user', null, ['class' => 'form-control']) !!}
    </div>

    <div id="key">
        <div class="form-group">
            <label>Key</label>
            {!! Form::file('key') !!}
        </div>
    </div>

    <div id="basic" style="display: none;">
        <div class="form-group">
            <label>Password</label>
            {!! Form::text('password', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <input type="submit" class="btn btn-primary" value="Submit">

    {!! Form::close() !!}
@endsection

@section('footer')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
           $("select[name='type']").change(function () {
             if ($(this).val() == 1) {
                 $("#basic").hide();
                 $('#key').show();
             } else {
                 $("#basic").show();
                 $('#key').hide();
             }
           });
        });
    </script>
@endsection