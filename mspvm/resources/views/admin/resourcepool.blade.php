@extends('admin/main')

@section('content')
    <table class="table table-bordered">
        <tr>
            <td width="10%">
                Status:
            </td>
            <td>
                @if ($resource_pool->status)
                    Active <a href="{{route('admin.resourcepool-suspend', ['resource_pool_id' => $resource_pool->id])}}">[SUSPEND]</a>
                    @else
                    Suspended <a href="{{route('admin.resourcepool-unsuspend', ['resource_pool_id' => $resource_pool->id])}}">[UNSUSPEND]</a> <a href="{{route('admin.resourcepool-delete', ['resource_pool_id' => $resource_pool->id])}}" onclick="return confirm('Are you sure?');">[DELETE]</a>
                @endif
            </td>
        </tr>
        <tr>
            <td width="10%">
                Storage:
            </td>
            <td>
                {{$resource_pool->getTotalDisk()}}MB ({{$resource_pool->getUsedDiskPercentage()}}%)
            </td>
        </tr>
        <tr>
            <td width="10%">
                RAM:
            </td>
            <td>
                {{$resource_pool->getTotalRAM()}}MB ({{$resource_pool->getUsedRAMPercentage()}}%)
            </td>
        </tr>
        <tr>
            <td width="10%">
                SWAP:
            </td>
            <td>
                {{$resource_pool->getTotalSWAP()}}MB ({{$resource_pool->getUsedSwapPercentage()}}%)
            </td>
        </tr>
        <tr>
            <td width="10%">
                IPs
            </td>
            <td>
                {{$resource_pool->getIPCount()}} (Allocated: {{$resource_pool->getAllocatedIPCount()}})
            </td>
        </tr>
        <tr>
            <td width="10%">
                Package:
            </td>
            <td>
                {!! $resource_pool->package()->name !!}
            </td>
        </tr>
    </table>
    <h4>VMs</h4>
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
                ajax: '{!! route('admin.table-vms', ['select' => ['resource_pool_id' => $resource_pool->id]]) !!}',
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