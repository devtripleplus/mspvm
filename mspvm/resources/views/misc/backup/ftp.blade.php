<hr>
<div class="form-group">
    <label>
        Host:
    </label>
    {!! Form::text('host', array_get($settings, 'host'), ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <label>
        User:
    </label>
    {!! Form::text('user', array_get($settings, 'user'), ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <label>
        Password:
    </label>
    {!! Form::input('password','password', array_get($settings, 'password'), ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    <label>
        Directory:
    </label>
    {!! Form::text('dir', array_get($settings, 'dir'), ['class' => 'form-control']) !!}
</div>