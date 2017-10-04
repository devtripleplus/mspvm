@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Alerts</a>
    </li>
@endsection

@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    Type
                </th>
                <th>
                    Resource
                </th>
                <th>
                    Threshold
                </th>
                <th>
                    Email
                </th>
                <th>
                    Action
                </th>
            </tr>
        </thead>
   @foreach ($alerts as $alert)
        <tr>
            <td>
                {!! $alert->getTypeName() !!}
            </td>
            <td>
                {!! $alert->getRepresentation() !!}
            </td>
            <td>
                {!! $alert->target_treshold !!}
            </td>
            <td>
                {!! $alert->email !!}
            </td>
            <td style="text-align: center">
                <a href="{{route('admin.notification-delete', ['alert_id' => $alert->id])}}">
                    Delete
                </a>
            </td>
        </tr>
    @endforeach
    </table>
@endsection