<?php namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

Class User extends Model implements Authenticatable   {
    use \Illuminate\Auth\Authenticatable;

    protected $table = 'accounts';

    protected $fillable = [
        'username',
        'email_address',
        'password',
        'access_level'
    ];

    public static $group = [
        1 => 'Client',
        2 => 'Support',
        3 => 'Admin'
    ];

    public function getGroupName() {
        return static::$group[$this->access_level];
    }

    public function servers() {
        return $this->hasMany('App\VM', 'user_id', 'id');
    }
}