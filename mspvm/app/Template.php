<?php namespace App;

use App\UI\Table\VMDetailsTable;
use App\Virtualization\OpenVZ;
use Illuminate\Database\Eloquent\Model;

Class Template extends Model  {
    protected $table = 'templates';

    protected $fillable = [
        'name',
        'file',
        'type',
        'path',
        'size',
        'description',
        'architecture'
    ];

    public function getName() {
        $arr = explode(".", $this->path);
        return $arr[0];
    }

    public function getPath() {
        return '/var/mspvm/templates/'.strtolower(Server::$virtualization[$this->type]).'/'.$this->path;
    }
}