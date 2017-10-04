<hr>
<div class="form-group">
    <label>
        Server:
    </label>
    {!! Form::select('backup_server_id', $servers, array_get($settings, 'backup_server_id'), ['class' => 'form-control']) !!}
</div>