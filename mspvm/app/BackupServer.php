<?php namespace App;

use Illuminate\Database\Eloquent\Model;

Class BackupServer extends Model  {
    protected $table = 'backup_servers';

    protected $fillable = [
        'host',
        'user',
        'password',
        'port',
        'directory'
    ];
}