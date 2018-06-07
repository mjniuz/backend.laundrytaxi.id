<?php

namespace App\Http\Controllers\Backend\AdminUsers;

use App\AdminUsers\ItemsHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use App\AdminUsers\AdminUserRepository;



class AdminUsersController extends Controller
{
	protected $admin_user;
	public function __construct(AdminUserRepository $admin_user){
		$this->admin_user   = $admin_user;
	}

    public function index(Request $request){
	    $filters    = $request->only([
            'full_name', 'email'
        ]);
    	$users	= $this->admin_user->getFilters($filters, 50);

        return view('backend.admin-users.index', compact('users'));
    }

    public function form($id = NULL){
        if($id){
            $user   = $this->admin_user->find($id);
        }
        $groups = $this->admin_user->getGroups();

        return view('backend.admin-users.form', compact('user','groups'));
    }

    public function save($id = 0, Request $request){
		$inputs	= $request->only([
			'first_name', 'last_name', 'email','password', 'group_id'
		]);
		
		$this->admin_user->createUpdateAdmin($id, $inputs);

        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'Admin User Success');

        return redirect(url("backend/administrator"));
    }

    public function delete($id,  Request $request){
		$this->admin_user->delete($id);

        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'admin User Deleted');

    	return redirect("backend/administrator");
    }

    public function roleUser($id = 0, Request $request){
        $user   = $this->admin_user->find($id);
        if(!$user){
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', 'User not found');
            return redirect(url('backend/administrator'));
        }
        $userRole   = $this->admin_user->pluckRole($user);
        $roles      = $this->admin_user->getRoles();

        return view('backend.admin-users.role.user-form', compact('user','roles', 'userRole'));
    }

    public function roleUserUpdate($id = 0, Request $request){
        $roles  = $request->get('role');
        $user   = $this->admin_user->updateRoleUser($id, $roles);

        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'User updated');
        return redirect(url('backend/administrator'));
    }

    public function roleIndex(){
        $roles  = $this->admin_user->getRoles(100);
        return view('backend.admin-users.role.index', compact('roles'));
    }

    public function roleForm($id = 0){
        if($id){
            $role   = $this->admin_user->findRole($id);
        }

        return view('backend.admin-users.role.form', compact('role'));
    }

    public function roleSave($id = 0, Request $request){
        $inputs	= $request->only([
            'name', 'slug'
        ]);

        $this->admin_user->updateRole($id, $inputs);

        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'Admin User Success');

        return redirect(url("backend/administrator/role"));
    }

    public function rolePermission($id = 0){
        $role           = $this->admin_user->rolePermission($id);
        $rolePermission = [];
        if($role){
            $rolePermission    = $role->permission->pluck('permission_id')->toArray();
        }

        $permissions    = $this->admin_user->getPermissions();
        $permissionData = new ItemsHelper($permissions, $rolePermission);
        $permissionList = $permissionData->itemArrayList(true);

        return view('backend.admin-users.role.permission', compact('permissionList','role'));

    }

    public function rolePermissionSave($id, Request $request){
        $permission     = $request->get('permission');

        $permissionList = $this->admin_user->checkRolePermission($id, $permission);

        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'Admin User Success');

        return redirect(url("backend/administrator/role"));
    }

    public function permissionIndex(){
        $permissions     = $this->admin_user->getPermissions();
        $permissionData     = new ItemsHelper($permissions);
        $permissionList = $permissionData->itemArrayList();

        return view('backend.admin-users.permission.index', compact('permissionList'));
    }

    public function permissionForm($id = 0){
        if($id){
            $permission         = $this->admin_user->findPermission($id);
        }

        $permissions    = $this->admin_user->getPermissions();
        $permissionData = new ItemsHelper($permissions);
        $permissionList = $permissionData->itemMultiArray();

        return view('backend.admin-users.permission.form', compact('permission','permissionList'));
    }

    public function permissionSave($id = 0, Request $request){
        $inputs = $request->all();
        $this->admin_user->permissionSave($id, $inputs);
        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'Admin User Success');

        return redirect(url("backend/administrator/permission"));
    }

    public function permissionDelete($id = 0, Request $request){
        $delete = $this->admin_user->permissionDelete($id);

        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'Admin User Success');
        if(!$delete){
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', 'Permission tidak dapat dihapus karena masih memiliki child, silahkan hapus child dulu!');
        }

        return redirect(url("backend/administrator/permission"));
    }

    public function myProfile(){
        $user       = $this->admin_user->myProfile();
        $groups     = $this->admin_user->getGroups();
        $myProfile  = true;
        return view('backend.admin-users.form', compact('user', 'myProfile','groups'));
    }


    public function profileUpdate(Request $request){
        $inputs	= $request->only([
            'first_name', 'last_name', 'email','password'
        ]);

        $isSuccess = $this->admin_user->updateProfile($inputs);

        if($isSuccess){
            $request->session()->flash('alert-class','success');
            $request->session()->flash('status', 'User Success Updated');
        }else{
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', 'Something error, please try again later');
        }

        return redirect(url("backend/profile"));
    }

    public function groupIndex(){
        $groups         = $this->admin_user->getGroups(50);

        return view('backend.admin-users.group.index', compact('groups'));
    }

    public function groupForm($id = null){
        $group  = $this->admin_user->findGroup($id);

        return view('backend.admin-users.group.form', compact('group'));
    }


    public function groupSave($id = null, Request $request){
        $inputs	= $request->only([
            'title', 'slug'
        ]);

        $isSuccess = $this->admin_user->groupSave($id, $inputs);

        if($isSuccess){
            $request->session()->flash('alert-class','success');
            $request->session()->flash('status', 'Group Success Updated');
        }else{
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', 'Something error, please try again later');
        }

        return redirect(url("backend/administrator/group"));
    }

    public function groupDelete($id = null, Request $request){
        $isSuccess  = $this->admin_user->findGroup($id);

        if($isSuccess){
            $request->session()->flash('alert-class','success');
            $request->session()->flash('status', 'Group Success Deleted');
        }else{
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', 'Something error, please try again later');
        }

        return redirect(url("backend/administrator/group"));
    }
}
