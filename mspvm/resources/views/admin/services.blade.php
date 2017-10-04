@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Services</a>
    </li>
@endsection

@section('content')
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>
                Service
            </td>
            <td>
                Action
            </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                NGINX
            </td>
            <td>
                <a href="{{route('admin.service-restart', ['service_name' => 'nginx'])}}"> Restart </a>
            </td>
        </tr>
        <tr>
            <td>
                MySQL
            </td>
            <td>
                <a href="{{route('admin.service-restart', ['service_name' => 'mysqld'])}}"> Restart </a>
            </td>
        </tr>
        <tr>
            <td>
                Network
            </td>
            <td>
                <a href="{{route('admin.service-restart', ['service_name' => 'network'])}}"> Restart </a>
            </td>
        </tr>
        <tr>
            <td>
                IPTables
            </td>
            <td>
                <a href="{{route('admin.service-restart', ['service_name' => 'iptables'])}}"> Restart </a>
            </td>
        </tr>
        </tbody>
    </table>
@endsection
