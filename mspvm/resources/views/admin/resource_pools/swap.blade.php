<div class="progress">
    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$resource_pool->getUsedSwapPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$resource_pool->getUsedDiskPercentage()}}%;">
        {{$resource_pool->getUsedSwapPercentage()}}%
    </div>
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{$resource_pool->getFreeSwapPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$resource_pool->getFreeDiskPercentage()}}%;">
        {{$resource_pool->getFreeSwap()}} MB
    </div>
</div>