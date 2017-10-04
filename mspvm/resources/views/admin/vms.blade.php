@extends('admin/main')

@section('content')
    <table class="table table-bordered" id="vms-table">
        <thead>
        <tr>
            <th style="width: 3px; text-align: center;">

            </th>
            <th>
                ID
            </th>
            <th style="width: 3px; text-align: center;">

            </th>
            <th style="width: 3px; text-align: center;">

            </th>
            <th>
                VMID
            </th>
            <th>
                Node
            </th>
            <th>
                User
            </th>
            <th>
                IP Address
            </th>
            <th>
                Bandwidth
            </th>
            <th style="width: 3px; text-align: center;">

            </th>
            <th style="width: 3px; text-align: center;">

            </th>
        </tr>
        </thead>
    </table>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#vms-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ VMs",
                    "sLengthMenu": "Show: _MENU_",
                    "sSearch": ""
                },
                order: [
                  [
                          1, 'asc'
                  ]
                ],
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.table-vms', ['select' => $extra]) !!}',
                columns: [
                    { data: 'status', name: 'status', sortable: false, searchable: false},
                    { data: 'id', name: 'id', sortable: true, searchable: false},
                    { data: 'virtualization_type', name: 'virtualization_type', sortable: false, searchable: false},
                    { data: 'conf', name: 'conf', sortable: false, searchable: false},
                    { data: 'virt_identifier', name: 'virt_identifier', sortable: true, searchable: true},
                    { data: 'server_id', name: 'server_id', sortable: true, searchable: false},
                    { data: 'user_id', name: 'user_id', sortable: true, searchable: false},
                    { data: 'primary_ip', name: 'primary_ip', sortable: true, searchable: true},
                    { data: 'bandwidth', name: 'bandwidth', sortable: false, searchable: false},
                    { data: 'speed', name: 'speed', sortable: false, searchable: false},
                    { data: 'delete', name: 'delete', sortable: false, searchable: false}
                ]
            });

            $('input[type="search"]').addClass('form-control');
        });
    </script>
@endsection