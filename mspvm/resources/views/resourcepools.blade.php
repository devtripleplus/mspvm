@extends('main')

@section('navigation')
    <li>
        <a href="#">Resource Pools</a>
    </li>
@endsection

@section('content')
    <table class="table table-bordered" id="resourcepools-table">
        <thead>
            <tr>
                <th style="width: 50%">
                    #
                </th>
                <th style="width: 50%">
                    #
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
                <th>
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
                    { data: 'id', name: 'id' },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'disk', name: 'disk' },
                    { data: 'ram', name: 'ram' },
                    { data: 'swap', name: 'swap' },
                    { data: 'ips', name: 'ips' },
                ]
            });

            $('input[type="search"]').addClass('form-control');
        });
    </script>
@endsection