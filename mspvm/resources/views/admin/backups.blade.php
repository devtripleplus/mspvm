@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Backups</a>
    </li>
@endsection

@section('content')
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>
                Backup
            </td>
            <td>
                Created
            </td>
            <td>
                Manage
            </td>
        </tr>
        </thead>
    <tbody>
    @foreach ($backups as $backup)
        <tr>
            <td>
                {!! $backup['name'] !!}
                </td>
            <td>
                {!! $backup['date']->diffForHumans() !!}
            </td>
            <td>
                <a href="{{route('admin.backup-delete', ['backup' => $backup['name']])}}" onclick="return confirm('Are you sure?');">
                    Delete
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
@endsection
