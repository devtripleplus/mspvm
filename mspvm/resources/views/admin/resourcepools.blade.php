@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Resource Pools</a>
    </li>
@endsection

@section('content')
    <table class="table table-bordered" id="resourcepools-table">
        <thead>
        <tr>
            <th style="width: 30%">
                #
            </th>
            <th style="width: 30%">
                User
            </th>
            <th>
                Disk
            </th>
            <th>
                RAM
            </th>
            <th>
                Swap
            </th>
            <th style="width: 20%">
                IPs
            </th>
        </tr>
        </thead>
    </table>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#resourcepools-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ resource pools",
                    "sLengthMenu": "Show: _MENU_",
                    "sSearch": ""
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.table-resource-pools') !!}',
                columns: [
                    { data: 'id', name: 'id', sortable: false, searchable: false},
                    { data: 'user_id', name: 'user_id', sortable: false, searchable: false},
                    { data: 'disk', name: 'disk' , sortable: true, searchable: false},
                    { data: 'ram', name: 'ram' , sortable: true, searchable: false},
                    { data: 'swap', name: 'swap', sortable: true, searchable: false },
                    { data: 'ips', name: 'ips' , sortable: true, searchable: false},
                ]
            });

            $('input[type="search"]').addClass('form-control');
        });
    </script>
@endsection