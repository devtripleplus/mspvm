@if ($server->stats()->isAvailable())
    <div class="server">
        <div class="main">
            <div class="ip">
                {{$server->ip}}
            </div>
            <div class="name">
                {{$server->name}}
            </div>
            <div class="status">
                {{$server->VMCount()}} VMs &nbsp;    <i class="fa fa-circle"></i>  {{$server->onlineVMCount()}} online
            </div>
        </div>
        <div class="boxes">
            <div class="box">
                <div class="value">
                    {{ $server->stats()->getLoadAverage() }}
                </div>
                <div class="name">
                    Load Average
                </div>
            </div>
            <div class="box">
                <div class="value">
                    {{ $server->stats()->getCPUUtilization() }}
                </div>
                <div class="name">
                    CPU
                </div>
            </div>
            <div class="box">
                <div class="value uptime">
                    {!! $server->getUptimeForHumans() !!}
                </div>
            </div>
        </div>
        <div class="charts">
            <div data-label="HDD" class="radial-bar radial-bar-{{$server->getDiskRoundedToNearestFive()}} radial-bar-md radial-bar-danger">
            <span>
                {{$server->getUsedDiskInFriendlyFormat(false)}}/{{$server->getTotalDiskInFriendlyFormat()}}
            </span>
            </div>
            <div data-label="RAM" class="radial-bar radial-bar-{{$server->getRAMRoundedToNearestFive()}} radial-bar-md radial-bar-danger">
            <span>
                {{$server->getUsedRAMInFriendlyFormat(false)}}/{{$server->getTotalRAMInFriendlyFormat()}}
            </span>
            </div>
        </div>
        <div class="refresh">
            {{\Carbon\Carbon::createFromTimestamp($server->stats()->time)->diffForHumans()}}
        </div>
    </div>
@else
    <div class="server">
        <div class="main">
            <div class="ip">
                {{$server->ip}}
            </div>
            <div class="name">
                {{$server->name}}
            </div>
            <div class="status">
                {{$server->VMCount()}} VMs &nbsp;    <i class="fa fa-circle"></i>  {{$server->onlineVMCount()}} online
            </div>
        </div>
        <div class="boxes">
            Unavailable
        </div>
    </div>
@endif