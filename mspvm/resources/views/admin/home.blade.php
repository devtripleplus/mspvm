@extends('admin/main')

@section('head')
    <link rel="stylesheet" href="{{asset('css/radial.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default panel-fill">
                <div class="panel-heading">
                    Statistics
                </div>
                <div class="panel-body">
                    <div class="pull-right" style="width: 200px; text-align: right;">
                        Server: &nbsp;
                        <select name="server_id" style="display: inline-block; width: unset;" class="form-control">
                            @foreach (\App\Server::all() as $server)
                                <option value="{{$server->id}}">{{$server->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="stats">
                        Retrieving server stats.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default panel-fill">
                <div class="panel-heading">
                    Statistics
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="about-info-p">
                            <span>
                                Total OpenVZ Virtual Servers
                            </span>
                            <span class="label label-info pull-right">
                                {{\App\Server::count()}}
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Total Xen PV Virtual Servers
                            </span>
                            <span class="label label-info pull-right">
                                0
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Total Xen HVM Virtual Servers
                            </span>
                            <span class="label label-info pull-right">
                                0
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Total KVM Virtual Servers
                            </span>
                            <span class="label label-info pull-right">
                                0
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Total Nodes
                            </span>
                            <span class="label label-success pull-right">
                                {{\App\VM::count()}}
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Free IPv4
                            </span>
                            <span class="label label-success pull-right">
                                {{\App\IP::where('vps_id', '=', 0)->count()}}
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Used IPv4
                            </span>
                            <span class="label label-success pull-right">
                                {{\App\IP::where('vps_id', '!=', 0)->count()}}
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Free IPv6
                            </span>
                            <span class="label label-success pull-right">
                                0
                            </span>
                        </div>
                        <div class="about-info-p">
                            <span>
                                Used IPv6
                            </span>
                            <span class="label label-success pull-right">
                                0
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default panel-fill">
                <div class="panel-heading">
                    User Statistics
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="about-info-p">
                            <span>
                                Total Clients
                            </span>
                            <span class="label label-info pull-right">
                                {{\App\User::where('access_level', '=', 1)->count()}}
                            </span>
                        </div>

                        <div class="about-info-p">
                            <span>
                                Total Resellers
                            </span>
                            <span class="label label-info pull-right">
                                -/-
                            </span>
                        </div>

                        <div class="about-info-p">
                            <span>
                                Total Administrators
                            </span>
                            <span class="label label-info pull-right">
                                {{\App\User::where('access_level', '=', 3)->count()}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5>Suspended VMs</h5>
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
            $("select[name='server_id']").change(function () {
                $("#stats").html('Retrieving server stats...');
                $.get('{!! route('admin.home') !!}/server/'+$(this).val()+'/stats', function (data) {
                    $("#stats").html(data);
                });
            });

            $("select[name='server_id']").change();

            $('#vms-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ suspended VMs",
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
                ajax: '{!! route('admin.table-vms', ['select' => ['suspended' => 1]]) !!}',
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