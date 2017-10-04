@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Backup Servers</a>
    </li>
@endsection

@section('content')
    <table id="datatable" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style="width: 50%">
                Host
            </th>
            <th>
                Port
            </th>
            <th>
                Backups
            </th>
        </tr>
        </thead>
        <tbody>
    @foreach ($backup_servers as $server)
            <tr>
                <td>
                    {{$server->host}}
                </td>
                <td>
                    {{$server->port}}
                </td>
                <td>
                    0
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection