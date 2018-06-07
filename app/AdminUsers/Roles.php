<?php

namespace App\AdminUsers;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AdminUser
 */
class Roles extends Model
{

    protected $table = 'roles';

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function permission(){
        $child  = $this->hasMany('App\\AdminUsers\\RolePermissions', 'role_id');

        return $child;
    }
}
