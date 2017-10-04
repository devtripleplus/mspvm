<div class="panel panel-default panel-fill">
    <div class="panel-heading">
        <div class="checkbox checkbox-success">
            <input id="backup-method-{{$method->getID()}}" type="checkbox" class="backup-method-toggle" data-method="{{$method->getID()}}" value="1"{{$vm->hasBackupMethodEnabled($method) ? ' checked' : ''}}>
            <label for="backup-method-{{$method->getID()}}">
                {{$method->getName()}}
            </label>
        </div>
    </div>
    <div class="panel-body">
        <p>
            {{$method->getDescription()}}
        </p>
        @if ($vm->hasBackupMethodEnabled($method) && $method->hasConfigForm($vm))
            <hr />
            {!! Form::open(['route' => ['admin.vm-ajax.updatebackupmethod', $vm->id], 'class' => 'backup-method-form']) !!}
            <input type="hidden" name="method" value="{{$method->getID()}}">
            {!! $method->getConfigForm($vm) !!}
         <input type="submit" class="btn btn-primary pull-right" value="Update">
            {!! Form::close() !!}
        @endif
    </div>
</div>