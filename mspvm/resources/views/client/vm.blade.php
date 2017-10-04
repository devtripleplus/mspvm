@extends('main')

@section('navigation')
    <li>
        <a href="#">VM</a>
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default panel-fill">
                <div class="panel-heading">
                    <h3 class="panel-title">Statistics</h3>
                </div>
                <div class="panel-body">
                    @if (empty($vm->stats()))
                        <div class="about-info-p">
                            <strong>Disk</strong>
                            <br>
                                    <span class="text-muted">
                            {{$vm->getUsedDiskInFriendlyFormat(false)}}/{{$vm->getTotalDiskInFriendlyFormat()}}
                        </span>
                            <br />
                            <div class="progress">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{$vm->getUsedDiskPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$vm->getUsedDiskPercentage()}}%; visibility: visible; animation-name: animationProgress;">
                                </div>
                            </div>
                        </div>

                        <div class="about-info-p">
                            <strong>RAM</strong>
                            <br>
                                    <span class="text-muted">
                            {{$vm->getUsedRAMInFriendlyFormat(false)}}/{{$vm->getTotalRAMInFriendlyFormat()}}
                        </span>
                            <br />
                            <div class="progress">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{$vm->getUsedRAMPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$vm->getUsedRAMPercentage()}}%; visibility: visible; animation-name: animationProgress;">
                                </div>
                            </div>
                        </div>

                        <div class="about-info-p">
                            <strong>CPU</strong>
                            <br>
                                    <span class="text-muted">
                            {{$vm->getUsedCPU()}}
                        </span>
                            <br />
                            <div class="progress">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{$vm->getUsedCPUPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$vm->getUsedCPUPercentage()}}%; visibility: visible; animation-name: animationProgress;">
                                </div>
                            </div>
                        </div>

                        <div class="about-info-p">
                            <strong>Operating System</strong>
                            <br>
                                    <span class="text-muted">
                                        {!! $vm->getOS() !!}
                                    </span>
                        </div>

                        <div class="about-info-p">
                            <strong>Main IP Address</strong>
                            <br>
                                    <span class="text-muted">
                                        {!! $vm->getMainIP() !!}
                                    </span>
                        </div>

                        <div class="about-info-p">
                            <strong>Hostname</strong>
                            <br>
                                    <span class="text-muted">
                                        {!! $vm->getHostname() !!}
                                    </span>
                        </div>

                        <div class="about-info-p">
                            <strong>ID</strong>
                            <br>
                                    <span class="text-muted">
                                        {!! $vm->getVirtID() !!}
                                    </span>
                        </div>

                        <div class="about-info-p">
                            <strong>Memory</strong>
                            <br>
                                    <span class="text-muted">
                                        {!! $vm->getTotalRAMInFriendlyFormat() !!}
                                    </span>
                        </div>

                        <div class="about-info-p">
                            <strong>Bandwidth</strong>
                            <br>
                                    <span class="text-muted">
                                        {!! $vm->getTotalBandwidthInFriendlyFormat() !!}
                                    </span>
                        </div>
                    @else
                        <p>
                            Statistics are currently unavailable for this VM.
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row user-tabs">
                <div class="col-lg-9 col-md-10 col-sm-9">
                    <ul class="nav nav-tabs tabs" style="width: 100%;">
                        <li class="tab active" style="width: 15%;">
                            <a href="#home-2" data-toggle="tab" aria-expanded="true" class="active">
                                <span class="hidden-xs">Details</span>
                            </a>
                        </li>
                        <li class="tab" style="width: 15%;">
                            <a href="#networking" data-toggle="tab" aria-expanded="true">
                                <span class="visible-xs"><i class="fa fa-home"></i></span>
                                <span class="hidden-xs">Networking</span>
                            </a>
                        </li>
                        <li class="tab" style="width: 15%;">
                            <a href="#logs" data-toggle="tab" aria-expanded="false" class="">
                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                <span class="hidden-xs">Log</span>
                            </a>
                        </li>
                        <li class="tab" style="width: 15%;">
                            <a href="#backups" data-toggle="tab" aria-expanded="false" class="">
                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                <span class="hidden-xs">Backups</span>
                            </a>
                        </li>
                        <div class="indicator" style="right: 356px; left: 0px;"></div><div class="indicator" style="right: 356px; left: 0px;"></div></ul>
                </div>
                <div class="col-lg-3 col-md-10 col-sm-3 hidden-xs">
                    <div class="pull-right">
                        <div class="dropdown">
                            <a class="btn btn-primary waves-effect waves-light" href="{{app('url')->to('vmc/'.$vm->id.'/?c=reboot')}}"> Reboot <span class="fa fa-cycle"></span></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content profile-tab-content">
                <div class="tab-pane active" id="home-2">
                    <table class="table table-bordered" style="background-color: #FFF;">
                        <tr>
                            <td style="width: 20%">
                                Status:
                            </td>
                            <td>
                                @if ($vm->suspended)
                                    Suspended
                                @else
                                    Active
                                    @if ($vm->online)
                                        (Online)
                                    @else
                                    (Offline)
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%">
                                Customer:
                            </td>
                            <td>
                                <a href="{{route('admin.user', ['user_id' => $vm->user_id])}}">
                                    {{$vm->user()->username}}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Created:
                            </td>
                            <td>
                                {{$vm->created_at->format('Y-m-d')}}
                            </td>
                        </tr>
                    </table>


                    @foreach ($controls as $control)
                        <a href="{{$control->getUrl($vm)}}" class="btn btn-control" data-slug="{{$control->getSlug()}}" style="margin-right: 6px; margin-bottom: 6px;">
                                    <span class="icon">
                                        {!! $control->getIcon() !!}
                                    </span>
                            {{$control->getName()}}
                        </a>
                    @endforeach

                </div>

                <div class="tab-pane" id="networking" style="display: block;">
                    <table id="datatable" class="table table-striped" style="background-color: #FFF;">
                        @foreach ($ips as $ip)
                            <tr>
                                <td>
                                    {{$ip->ip_address}}
                                    @if ($ip->ip_address == $vm->primary_ip)
                                        (primary)
                                    @endif
                                </td>
                                <td>
                                    {{$ip->created_at->diffForHumans()}}
                                </td>
                                <td style="text-align: center; width: 100px;">
                                    @if ($ip->ip_address != $vm->primary_ip)
                                        <a href="{{route('admin.ip-remove', ['ip_id' => $ip->id])}}" class="btn btn-danger btn-xs">
                                            Remove
                                        </a>
                                    @endif
                                </td>
                            </tr>
                    @endforeach
                </div>
                </table>
                <br /><br />
                <h5>Assign new IP address</h5>
                {!! Form::open([
                    'route' => [
                        'admin.vm-ipassign',
                        $vm->id
                    ]
                ]) !!}

                <div class="form-group">
                    {!! Form::select('ip_id', $available_ips, null, ['class' => 'form-control']) !!}
                </div>

                <input type="submit" class="btn btn-success pull-right" value="Submit">

                {!! Form::close() !!}

                <br /><br />
                <h5>Network Speed</h5>
                {!! Form::open([
                    'route' => [
                        'admin.vm-tccontrol',
                        $vm->id
                    ]
                ]) !!}

                <div class="form-group">
                    {!! Form::select('network_speed', [
                        10 => '10Mbit/s',
                        100 => '100Mbit/s',
                        1000 => '1000Mbit/s'
                    ], $vm->network_speed, ['class' => 'form-control']) !!}
                </div>

                <input type="submit" class="btn btn-success pull-right" value="Submit">

                {!! Form::close() !!}
            </div>


            <div class="tab-pane" id="logs" style="display: none;">
                <div class="panel panel-default panel-fill">

                    <div class="panel-body">
                        @if (count($logs))
                            <div class="timeline-2">
                                @foreach ($logs as $entry)
                                    <div class="time-item">
                                        <div class="item-info">
                                            <div class="text-muted">{{$entry->created_at->diffForHumans()}}</div>
                                            <p>{!! $entry->entry !!}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>The log is empty</p>
                        @endif

                    </div>
                </div>

            </div>


            <div class="tab-pane" id="backups" style="display: none;">
                @foreach ($backup_methods as $method)
                    @include('misc/backup/method_panel')
                @endforeach

                <table class="table table-bordered" style="background-color: #FFF;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($backups as $backup)
                            <tr>
                                <td>
                                    {{\Carbon\Carbon::createFromTimestamp($backup->date)->diffForHumans()}}
                                </td>
                                <td>
                                    {{get_friendly_backup_method_name($backup->method)}}
                                </td>
                                <td style="text-align: center;">
                                    <a href="#" class="btn btn-primary btn-xs">Restore</a>
                                    <a href="#" class="btn btn-success btn-xs">Download</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No backups to show</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('footer')
    <script type="text/javascript">
        jQuery(document).ready(function () {
           $(document).on('change', ".backup-method-toggle", function (e, trigger) {
               var $this = $(this);
               $.ajax({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                  url: '{{route('admin.vm-ajax.togglebackupmethod', ['vm_id' => $vm->id])}}',
                   data: {
                        method: $(this).attr('data-method')
                   },
                   type: 'post',
                   dataType: 'json',
                   success: function (data) {
                       if (data.error) {
                           $.Notification.notify('error', 'top right', 'Error', data.message);

                            return;
                       }

                       if ($this.is(':checked')) {
                           $.Notification.notify('success', 'top right', $this.closest('.checkbox').find('label').first().html(), 'The backup method has been enabled for this VM!')
                       } else {
                           $.Notification.notify('success', 'top right', $this.closest('.checkbox').find('label').first().html(), 'The backup method has been disabled for this VM!')
                       }

                       $this.closest('.panel').replaceWith(data.message);
                   },
                   error: function () {
                       $.Notification.notify('error', 'top right', 'Oops..', 'Something went wrong while trying to update the backup method.')
                   }
               });
           });

            $(document).on('submit', ".backup-method-form", function (e) {
                e.preventDefault();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{route('admin.vm-ajax.updatebackupmethod', ['vm_id' => $vm->id])}}',
                    data: $(this).serializeArray(),
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        $.Notification.notify('success', 'top right', 'The backup method has been updated!')
                    },
                    error: function () {
                        $.Notification.notify('error', 'top right', 'Oops..', 'Something went wrong while trying to update the backup method.')
                    }
                });

                return false;
            });
        });
    </script>
@endsection