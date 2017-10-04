<?php namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

Class Notification extends Model implements Authenticatable   {
    use \Illuminate\Auth\Authenticatable;

    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'target_id',
        'target',
        'target_treshold',
        'email'
    ];

    public function getTypeName() {
        switch ($this->type) {
            case 1:
                return 'Node';
            case 2:
                return 'VM';
        }
    }

    public function getRepresentation() {
        switch ($this->target) {
            case 'useddisk':
                return 'Disk Utilization (MB)';
            case 'usedram':
                return 'RAM Utilization (MB)';
                break;
            case 'cpuutilizationaspercentage':
                return 'CPU Utilization (%)';
                break;
            default:
                return $this->target;
        }
    }

    public function getRepresentationWithThreshold() {
        return $this->getRepresentation().' >= '.$this->target_treshold;
    }
}