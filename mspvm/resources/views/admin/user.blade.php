@extends('admin/main')

@section('content')
    <div class="well">
        {{$theuser->username}} ({{$theuser->email_address}}) <br />
        {{$theuser->getGroupName()}} <br />
        <a href="{{route('admin.user-login', ['user_id' => $theuser->id])}}">
            Log in as this user
        </a>
    </div>

    <h4>Reset Password</h4>

    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'New Password']) !!}
    </div>
    <input type="submit" class="btn btn-primary pull-right" value="Update">
    <br />
    {!! Form::close() !!}

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

@section('actionbar')
    <a href="{{route('admin.user-delete', ['user_id' => $theuser->id])}}" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</a>

    <a href="{{route('admin.user-purge', ['user_id' => $theuser->id])}}" class="btn btn-danger" onclick="return confirm('Are you sure? IMPORTANT: THIS WILL ALSO REMOVE ALL VIRTUAL MACHINES AND RESOURCE POOLS!')"><i class="fa fa-trash"></i> Purge</a>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#vms-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ VMs from {{  $theuser->username }}",
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
                ajax: '{!! route('admin.table-vms', ['select' => ['user_id' => $theuser->id]]) !!}',
                columns: [
                    { data: 'status', name: 'status', sortable: false, searchable: false},
                    { data: 'id', name: 'id', sortable: true, searchable: false},
                    { data: 'virtualization_type', name: 'virtualization_type', sortable: false, searchable: false},
                    { data: 'conf', name: 'conf', sortable: false, searchable: false},
                    { data: 'virt_identifier', name: 'virt_identifier', sortable: true, searchable: true},
                    { data: 'server_id', name: 'server_id', sortable: true, searchable: false},
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