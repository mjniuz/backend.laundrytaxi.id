<?php

namespace App\AdminUsers;

use Carbon\Carbon;
use DB;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Cartalyst\Sentinel\Activations\EloquentActivation as Activation;

class AdminUserRepository
{
    public function __construct() {
    }

    public function find($id = 0){
        return AdminUser::with(['role.role'])->find($id);
    }

    public function myProfile(){
        return Sentinel::check();
    }

    public function findByEmail($email = ""){
        return AdminUser::where("email", $email)->first();
    }

    public function generateTokenForgotPassword($user){
        if(!$user OR is_null($user)){
            return false;
        }

        $token  = str_random(190);

        $pw_reset = DB::table('password_resets')->insert(
            [
                'email' => $user->email,
                'token' => $token
            ]
        );

        $adminUser  = DB::table('password_resets')
            ->where('email', $user->email)
            ->where('token', '<>', $token)
            ->delete();

        return $token;
    }

    public function getUserByToken($token = ""){
        $carbon = new Carbon();
        $addHour    = $carbon->parse(date("Y-m-d H:i:s"))->addHours(3)->toDateTimeString();

        $adminUser  = DB::table('password_resets')
            ->where('created_at', '<', $addHour)
            ->where('token', $token)
            ->first();

        return $adminUser;
    }

    public function changePassword($email, $newPass){
        $adminUser  = $this->findByEmail($email);
        $adminUser->password    = Hash::make($newPass);
        $adminUser->save();

        $adminUser  = DB::table('password_resets')
            ->where('email', $email)
            ->delete();


        return $adminUser;
    }
    
    public function adminLogin($email, $password){
        $credentials = [
            'email'    => $email,
            'password' => $password,
        ];


        /*if(in_array($email, ["faizal.edrus@gmail.com", "alan@importir.org"])){
            // remember
            $user = Sentinel::authenticateAndRemember($credentials);
        }else{*/

            $user = Sentinel::authenticateAndRemember($credentials);
        //}

        if ($user) {
            return $user;
        }

        return false;
    }

    public function adminLogout(){
        return Sentinel::logout();
    }

    public function updateProfile($inputs){
        $user   = Sentinel::check();

        $data   = [
            'email'         => $inputs['email'],
            'password'      => $inputs['password'],
            'first_name'    => $inputs['first_name'],
            'last_name'     => $inputs['last_name']
        ];
        if(!$inputs['password']){
            unset($inputs['password']);
        }

        try{
            Sentinel::update($user, $data);
        }catch (QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return false;
            }
        }

        return true;
    }

    public function createUpdateAdmin($id = 0, array $inputs){
        $user = $this->find($id);
        $data   = [
            'email'         => $inputs['email'],
            'password'      => $inputs['password'],
            'first_name'    => $inputs['first_name'],
            'last_name'     => $inputs['last_name'],
            'group_id'      => $inputs['group_id']
        ];

        if(!$user){
            $user   = new AdminUser();
        }

        if(!is_null($data['password'])){
            $user->password     = Hash::make($data['password']);
        }
        $user->email        = $data['email'];
        $user->first_name   = $data['first_name'];
        $user->last_name    = $data['last_name'];
        $user->group_id     = $data['group_id'];

        $user->save();

        $activate   = new Activation();
        $activate->user_id      = $user->id;
        $activate->code         = md5(date("Y-m-d H:i:s"));
        $activate->completed    = 1;
        $activate->completed_at = date("Y-m-d H:i:s");
        $activate->save();

        return $user;
    }

    public function delete($id = 0){
        $user   = $this->find($id);
        if($user){
            $user->delete();
            return true;
        }

        return false;
    }

    public function getFilters($filters = [], $paginate = 0){
        $users = AdminUser::with(['role.role','group']);

        if(!empty($filters['full_name'])){
            $users   = $users->where('full_name', 'like', '%' . $filters['full_name'] . '%');
        }

        if(isset($filters['email'])){
            $users   = $users->where('email', 'like', $filters['email']);
        }


        if($paginate > 0){
            return $users->paginate($paginate);
        }

        return $users->get();
    }

    public function findUserRole($id = 0){

        $user   = Sentinel::findById($id);
        $role = Sentinel::findRoleBySlug('member-admin');
        $result = $role->users()->attach($user);
        return $result;
    }

    public function getRoles($paginate = false){
        if(!$paginate){
            return Sentinel::getRoleRepository()->get();
        }

        return Sentinel::getRoleRepository()->paginate($paginate);
    }

    public function findRole($id = 0){
        return Roles::with(['permission'])->where('id', $id)->first();
    }

    public function updateRole($id = 0, $inputs = []){
        $role   = $this->findRole($id);
        if(!$role){
            $role = Sentinel::getRoleRepository()->createModel();
        }

        $role->name = $inputs['name'];
        $role->slug = $inputs['slug'];
        $role->save();

        return false;
    }

    public function updateRoleUser($id, $roles){
        $user   = $this->find($id);
        if(!$user){
            return false;
        }

        // detach role
        foreach ($user->role as $item){
            $roleUser = RoleUsers::where('user_id', $item->user_id);
            $roleUser->delete();
        }

        // attach
        if(!is_null($roles)){
            foreach($roles as $role){
                $roleUser = new RoleUsers();
                $roleUser->role_id  = $role;
                $roleUser->user_id  = $id;
                $roleUser->save();
            }
        }

        return $this->find($id);
    }

    public function createRoles($name = '', $slug = ''){
        $role   = Sentinel::getRoleRepository()->createModel()->create([
            'name' => $name,
            'slug' => $slug,
        ]);

        return $role;
    }

    public function getPermissions(){
        return Permissions::with(['children'])->get();
    }

    public function rolePermission($id){
        $role = $this->findRole($id);
        if(!$role){
            return false;
        }

        return $role;
    }

    public function findPermission($id = 0){
        return Permissions::with(['children'])->find($id);
    }

    public function getParentPermissions(){
        return Permissions::with([])->whereNull('parent_id')->get();
    }

    public function permissionSave($id, $inputs){
        $permission = $this->findPermission($id);
        if(!$permission){
            $permission = new Permissions();
        }

        $permission->parent_id  = !empty($inputs['parent_id']) ? $inputs['parent_id'] : null;
        $permission->name       = $inputs['name'];
        $permission->save();
    }

    public function permissionDelete($id = 0){
        $permission = $this->findPermission($id);
        if(!$permission){
            return false;
        }

        if(!is_null($permission->children)){
            return false;
        }

        return $permission->delete();
    }

    public function setPermissions(){
        $role = Sentinel::findRoleById(1);

        $role->permissions = [
            'user.update' => true,
            'user.view' => true,
        ];

        return $role->save();
    }


    /*
     * Check role permission
     */
    public function checkRolePermission($id, $permissions){
        if(is_null($permissions)){
            return false;
        }

        DB::beginTransaction();
        $permissions    = array_values($permissions);
        $this->saveRolePermissions($id, $permissions);

        $roles          = Permissions::with([])->whereIn('id', $permissions)->get();

        $permissionData = new ItemsHelper($roles);
        $permissionList = $permissionData->itemArray();
        $flatten        = $this->flatten($permissionList);

        $role = Sentinel::findRoleById($id);

        $role->permissions = $flatten;

        $role->save();

        DB::commit();

        return true;
    }

    private function flatten($array, $prefix = '') {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $result = $result + $this->flatten($value, $prefix . $key . '.');
            }
            else {
                if($key == "*"){
                    $result[$prefix . $key ] = $value;
                }else{
                    $result[$prefix . $key . "."] = $value;
                }
            }
        }
        return $result;
    }

    private function saveRolePermissions($roleID = 0, $permissions){
        $permExist  = RolePermissions::with([])->where('role_id', $roleID)->get();
        if($permExist->count()){
            foreach ($permExist as $item){
                RolePermissions::with([])->where('role_id', '=', $item->role_id)->delete();
            }
        }

        foreach ($permissions as $permission){
            $perm   = new RolePermissions();
            $perm->role_id          = $roleID;
            $perm->permission_id    = $permission;
            $perm->save();
        }
    }

    public function pluckRole($user){
        $userRole = $user->role->pluck('role_id')->toArray();
        $newArray = [];
        foreach($userRole as $items) {
            $newArray[$items] = $items;
        }

        return $newArray;
    }

    public function getParentName($parentID = 0){
        $permission = Permissions::find($parentID);
        if(!$permission){
            return null;
        }

        return $permission->name;
    }

    public function getGroups($paginate = 0){
        if($paginate > 0){
            return AdminGroup::with([])->paginate($paginate);
        }

        return AdminGroup::all();
    }

    public function findGroup($id){
        return AdminGroup::find($id);
    }

    public function deleteGroup($id){
        $group  = $this->findGroup($id);
        if(!$group){
            return false;
        }

        return $group->delete();
    }

    public function groupSave($id = null, $inputs = []){
        $group  = $this->findGroup($id);
        if(!$group){
            $group  = new AdminGroup();
        }

        $group->title   = $inputs['title'];
        $group->slug    = $inputs['slug'];
        $group->save();

        return $group;
    }
}
