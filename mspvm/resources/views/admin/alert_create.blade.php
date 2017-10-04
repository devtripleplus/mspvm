@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Alerts</a>
    </li>
@endsection

@section('content')
    {!! Form::open() !!}

    <div class="form-group">
        <label>Type</label>
        {!! Form::select('type', [1 => 'Node', 2 => 'VM'],null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group target" id="target_1" style="display: none;">
        <label>Resource</label>
        {!! Form::select('target_1', ['usedram' => 'RAM Utilization (MB)', 'useddisk' => 'Disk Utilization (MB)'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group target" id="target_2" style="display: none;">
        <label>Resource</label>
        {!! Form::select('target_2', ['cpuutilizationaspercentage' => 'CPU Utilization Percentage', 'usedram' => 'RAM Utilization (MB)', 'useddisk' => 'Disk Utilization (MB)'], null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Threshold</label>
        {!! Form::text('threshold', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Email</label>
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Submit">

    {!! Form::close() !!}
@endsection

@section('footer')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $("select[name='type']").on('change', function () {
                $('.target').hide();

                console.log('#target_'+$(this).val());

                $('#target_'+$(this).val()).show();
            });

            $("select[name='type']").change();
        });
    </script>
@endsection