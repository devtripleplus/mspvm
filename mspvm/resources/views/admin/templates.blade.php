@extends('admin/main')

@section('content')
    <table class="table table-bordered" id="templates-table">
        <thead>
        <tr>
            <th>Template</th>
            <th>Created</th>
        </tr>
        </thead>
    </table>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#templates-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ templates",
                    "sLengthMenu": "Templates: _MENU_",
                    "sSearch": ""
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.table-templates', isset($extra) ? $extra : []) !!}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'created_at', name: 'created_at' },
                ]
            });

            $('input[type="search"]').addClass('form-control');
        });
    </script>
@endsection