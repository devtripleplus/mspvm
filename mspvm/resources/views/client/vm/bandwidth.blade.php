<div class="progress">
    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$vm->getUsedBandwidthAsPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$vm->getUsedBandwidthAsPercentage()}}%;">
        {{$vm->getUsedBandwidthInFriendlyFormat()}}
    </div>
</div>