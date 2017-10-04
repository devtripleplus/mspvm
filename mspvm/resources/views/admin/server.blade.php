@extends('admin/main')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default panel-fill">
                <div class="panel-heading">
                    <h3 class="panel-title">Statistics</h3>
                </div>
                <div class="panel-body">
                    <div class="about-info-p">
                        <strong>Disk</strong>
                        <br>
                        <span class="text-muted">
                            {{$server->getUsedDiskInFriendlyFormat(false)}}/{{$server->getTotalDiskInFriendlyFormat()}}
                        </span>
                        <br />
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{$server->getUsedDiskPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$server->getUsedDiskPercentage()}}%; visibility: visible; animation-name: animationProgress;">
                            </div>
                        </div>
                    </div>

                    <div class="about-info-p">
                        <strong>RAM</strong>
                        <br>
                        <span class="text-muted">
                            {{$server->getUsedRAMInFriendlyFormat(false)}}/{{$server->getTotalRAMInFriendlyFormat()}}
                        </span>
                        <br />
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{$server->getUsedRAMPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$server->getUsedRAMPercentage()}}%; visibility: visible; animation-name: animationProgress;">
                            </div>
                        </div>
                    </div>

                    <div class="about-info-p">
                        <strong>CPU</strong>
                        <br>
                        <span class="text-muted">
                            {{$server->getUsedCPU()}}
                        </span>
                        <br />
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{$server->getUsedCPUPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$server->getUsedCPUPercentage()}}%; visibility: visible; animation-name: animationProgress;">
                            </div>
                        </div>
                    </div>

                    <div class="about-info-p">
                        <strong>Uptime</strong>
                        <br>
                        <span class="text-muted">
                            {!! $server->getUptimeForHumans() !!}
                        </span>
                    </div>
                </div>
            </div>

        </div>


        <div class="col-md-8">
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

        </div>

    </div>
@endsection

@section('actionbar')
    <a href="{{route('admin.server-delete', ['server_id' => $server->id])}}" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</a>
    <a href="{{route('admin.server-purge', ['server_id' => $server->id])}}" class="btn btn-danger" onclick="return confirm('Are you sure? IMPORTANT: THIS WILL DELETE ALL VMS ON THE SERVER!')"><i class="fa fa-trash"></i> Purge</a>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#vms-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ VMs on {{  $server->name }}",
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
                ajax: '{!! route('admin.table-vms', ['select' => ['server_id' => $server->id]]) !!}',
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