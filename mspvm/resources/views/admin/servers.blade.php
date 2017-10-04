@extends('admin/main')

@section('content')
    <table class="table table-bordered" id="servers-table">
        <thead>
        <tr>
            <th>Server</th>
            <th>Type</th>
            <th>Virtual Machines</th>
        </tr>
        </thead>
    </table>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#servers-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ servers",
                    "sLengthMenu": "Servers: _MENU_",
                    "sSearch": ""
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.table-servers') !!}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'type', name: 'type' },
                    { data: 'vms', name: 'vms' },
                ]
            });

            $('input[type="search"]').addClass('form-control');
        });
    </script>
@endsection