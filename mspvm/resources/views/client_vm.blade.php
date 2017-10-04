@extends('main')

@section('navigation')
    <li>
        <a href="#">My VMs</a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <h4 style="margin-top: unset">Controls</h4>
            @foreach ($controls as $control)
                <a href="{{$control->getUrl($vm)}}" class="btn btn-primary" style="margin-right: 6px; margin-bottom: 6px;">
                    {{$control->getName()}}
                </a>
            @endforeach
        </div>
        <div class="col-md-8">
            <h4>VM</h4>
            {!! \App\UI\Table\VMDetailsTable::detailed($vm) !!}
        </div>
    </div>
@endsection