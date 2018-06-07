<?php

namespace App\AdminUsers;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminUser
 */
class RolePermissions extends Model
{
    protected $table = 'role_permissions';
    public $timestamps = false;

    public function permission(){
        $child  = $this->belongsTo('App\\AdminUsers\\Permissions', 'permission_id');

        return $child;
    }

    public function role(){
        $child  = $this->belongsTo('App\\AdminUsers\\Roles', 'role_id');

        return $child;
    }
}
