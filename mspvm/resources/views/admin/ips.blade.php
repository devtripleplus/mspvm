@extends('admin/main')

@section('content')
    <table class="table table-bordered" id="ips-table">
        <thead>
        <tr>
            <th>IP</th>
            <th>VM</th>
            <th style="width: 3px"></th>
        </tr>
        </thead>
    </table>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#ips-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ ips",
                    "sLengthMenu": "Show: _MENU_",
                    "sSearch": ""
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.table-ips', isset($extra) ? $extra : []) !!}',
                columns: [
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'vm', name: 'vm' },
                    { data: 'delete', name: 'delete' }
                ]
            });

            $('input[type="search"]').addClass('form-control');
        });
    </script>
@endsection