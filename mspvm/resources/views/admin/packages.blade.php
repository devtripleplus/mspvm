@extends('admin/main')

@section('content')
    <table class="table table-bordered" id="packages-table">
        <thead>
        <tr>
            <th>Package</th>
            <th>VMs</th>
            <th>Disk (MB)</th>
            <th>RAM (MB)</th>
            <th>Burst (MB)</th>
            <th>SWAP (MB)</th>
            <th>CPUs</th>
            <th></th>
        </tr>
        </thead>
    </table>
@endsection

@section('footer')
    <script>
        jQuery(document).ready(function() {
            $('#packages-table').DataTable({
                "oLanguage": {
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ packages",
                    "sLengthMenu": "Show: _MENU_",
                    "sSearch": ""
                },
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.table-packages', isset($extra) ? $extra : []) !!}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'vms', name: 'vms', sortable: false, searchable: false  },
                    { data: 'disk', name: 'disk' },
                    { data: 'ram', name: 'ram' },
                    { data: 'burst', name: 'burst' },
                    { data: 'swap', name: 'swap' },
                    { data: 'cpus', name: 'cpus' },
                    { data: 'delete', name: 'delete', sortable: false, searchable: false }
                ]
            });

            $('input[type="search"]').addClass('form-control');
        });
    </script>
@endsection