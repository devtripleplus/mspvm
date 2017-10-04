<?php namespace App;

use Illuminate\Database\Eloquent\Model;

Class Option extends Model  {
    protected $table = 'settings';

    public $timestamps = false;

    public $primaryKey = 'setting_name';

    public $incrementing = false;

    protected $fillable = [
        'setting_name','setting_value','setting_group'
    ];
}