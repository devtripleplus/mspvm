<?php namespace App;

use App\UI\Table\VMDetailsTable;
use App\Virtualization\OpenVZ;
use Illuminate\Database\Eloquent\Model;

Class TemplateDeployment extends Model  {
    protected $table = 'template_deployment';

    protected $fillable = [
        'server_id',
        'template_id',
        'status'
    ];

    /**
     * @return Template
     */
    public function template() {
        return Template::find($this->template_id);
    }

    /**
     * @return Server
     */
    public function server() {
        return Server::find($this->server_id);
    }
}