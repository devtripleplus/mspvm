<?php namespace App;

use App\UI\Table\VMDetailsTable;
use App\Virtualization\OpenVZ;
use Illuminate\Database\Eloquent\Model;

Class Package extends Model  {
    protected $table = 'packages';

    protected $fillable = [
        'name',
        'ram',
        'swap',
        'disk',
        'cpu_units',
        'cpu_limit',
        'bandwith_limit',
        'inode_limit',
        'burst',
        'cpus',
        'network_speed',
        'resource_pool_id'
    ];
}