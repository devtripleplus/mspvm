<?php namespace App;

use Illuminate\Database\Eloquent\Model;

Class Log extends Model  {
    protected $table = 'vps_logs';

    protected $fillable = [
        'vps_id',
        'command',
        'entry'
    ];
}