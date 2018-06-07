<?php

namespace App\AdminUsers;

use Cartalyst\Sentinel\Users\EloquentUser;

use Illuminate\Notifications\Notifiable;

/**
 * Class AdminUser
 */
class AdminUser extends EloquentUser
{
    use Notifiable;
    protected $table = 'admin_users';

    public $timestamps = true;

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $guarded = [];

    public function role(){
        $child  = $this->hasMany('App\AdminUsers\RoleUsers', 'user_id');

        return $child;
    }

    public function group(){
        return $this->belongsTo('App\AdminUsers\AdminGroup', 'group_id');
    }

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
}
