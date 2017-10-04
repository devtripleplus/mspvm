@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">VM</a>
    </li>
@endsection

@section('content')
    {!! Form::open([
        'url' => 'vmc/'.$vm->id.'/?c='.$control->getSlug()
    ]) !!}

    @foreach ($control->getFormItems($vm) as $item)
        {!! $item !!}
    @endforeach

    <input type="submit" value="Submit" class="btn btn-primary">

    {!! Form::close() !!}
@endsection