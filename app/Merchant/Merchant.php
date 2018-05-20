<?php

namespace App\Merchant;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model{
    protected $table = 'merchant';
    public $timestamps = true;
    protected $hidden = array('password');
}