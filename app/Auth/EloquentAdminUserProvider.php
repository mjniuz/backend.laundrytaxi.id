<?php
/**
 * Created by PhpStorm.
 * User: Alan El
 * Date: 8/25/2017
 * Time: 5:13 PM
 */
namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;

class EloquentAdminUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        // Of course here, you could perform the query yourself with the is_admin comparison, but
        // I think it's best to avoid as much duplication as possible
        $user = parent::retrieveByCredentials($credentials);

        return $user;
    }
}