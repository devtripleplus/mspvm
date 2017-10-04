<div class="progress">
    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$resource_pool->getUsedRAMPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$resource_pool->getUsedDiskPercentage()}}%;">
        {{$resource_pool->getUsedRAMPercentage()}}%
    </div>
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{$resource_pool->getFreeRAMPercentage()}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$resource_pool->getFreeDiskPercentage()}}%;">
        {{$resource_pool->getFreeRAM()}} MB
    </div>
</div>