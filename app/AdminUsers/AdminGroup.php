<?php

namespace App\AdminUsers;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AdminUser
 */
class AdminGroup extends Model
{

    protected $table = 'admin_group';

    public $timestamps = true;
    protected $guarded = [];

    public function users(){
        return $this->hasMany('App\AdminUsers\AdminUser', 'group_id');
    }
}
