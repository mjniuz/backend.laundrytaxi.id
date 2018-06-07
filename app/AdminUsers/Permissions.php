<?php

namespace App\AdminUsers;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AdminUser
 */
class Permissions extends Model
{

    protected $table = 'permissions';

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function children(){
        $child  = $this->hasOne(Permissions::class, 'parent_id')->whereNotNull('parent_id');

        return $child;
    }
}
