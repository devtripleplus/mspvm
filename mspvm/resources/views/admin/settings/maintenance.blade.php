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
    {!! Form::open() !!}

    <div class="form-group">
        <label>Database Backup Frequency</label>
        {!! Form::select('database-backup-frequency', [
            '0' => 'Disabled',
            '1' => 'Hourly',
            '2' => '12 Hourly',
            '3' => 'Daily',
            '4' => 'Weekly'
        ], settings('database-backup-frequency'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Database Backups To Keep</label>
        {!! Form::text('database-backup-limit', settings('database-backup-limit'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Log Prune Interval</label>
        <p>hours</p>
        {!! Form::text('log-prune-interval', settings('log-prune-interval'), ['class' => 'form-control']) !!}
    </div>

    <input type="submit" class="btn btn-primary" value="Update">

    {!! Form::close() !!}
@endsection