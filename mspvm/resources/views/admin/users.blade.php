@extends('admin/main')

@section('content')
    <table class="table table-bordered" id="users-table">
        <thead>
        <tr>
            <th>Email</th>
            <th>Group</th>
            <th>Virtual Machines</th>
            <th>Registration</th>
        </tr>
        </thead>
    </table>
@endsection

@section('footer')
<script>
    jQuery(document).ready(function() {
        $('#users-table').DataTable({
            "oLanguage": {
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ users",
                "sLengthMenu": "Users: _MENU_",
                "sSearch": ""
            },
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.table-users', isset($extra) ? $extra : []) !!}',
            columns: [
                { data: 'email_address', name: 'email_address' },
                { data: 'access_level', name: 'access_level' },
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "searchable":     false,
                    "data":           'vms'
                },
                { data: 'created_at', name: 'created_at' },
            ]
        });

        $('input[type="search"]').addClass('form-control');
    });
</script>
@endsection