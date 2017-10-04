<?php namespace App;

use Illuminate\Database\Eloquent\Model;

Class Backup extends Model  {
    protected $table = 'backups';

    protected $fillable = [
        'vps_id',
        'date',
        'method',
        'checksum'
    ];
}