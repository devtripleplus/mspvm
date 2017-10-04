<div class="progress">
    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$resource_pool->getUsedDiskPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$resource_pool->getUsedDiskPercentage()}}%;">
        {{$resource_pool->getUsedDiskPercentage()}}%
    </div>
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{$resource_pool->getFreeDiskPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$resource_pool->getFreeDiskPercentage()}}%;">
        {{$resource_pool->getFreeDisk()}} MB
    </div>
</div>