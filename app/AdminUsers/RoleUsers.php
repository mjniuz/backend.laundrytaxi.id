<?php

namespace App\AdminUsers;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AdminUser
 */
class RoleUsers extends Model
{
    protected $table = 'role_users';
    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function role(){
        $child  = $this->belongsTo('App\\AdminUsers\\Roles', 'role_id');

        return $child;
    }

    public function user(){
        $child  = $this->belongsTo('App\\AdminUsers\\AdminUser', 'user_id');

        return $child;
    }
}
