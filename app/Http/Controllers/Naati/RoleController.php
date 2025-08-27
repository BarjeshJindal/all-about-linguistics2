<?php

namespace App\Http\Controllers\Naati;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller; 
use App\Models\Admin;
use Hash;

class RoleController extends Controller
{   
    // public function __construct()
    // {
    //     $this->middleware('permission:show-role')->only(['index']);
    //     $this->middleware('permission:create-role')->only(['createRole', 'store']);
    //     $this->middleware('permission:edit-role')->only(['edit', 'update']);
    //     $this->middleware('permission:delete-role')->only(['destroy']);
    // }
    public function index(){
         $roles = Role::all();
        return view('admin.roles.index',compact("roles"));
    }
    public function createRole()
    {
        // $permissions = DB::select("SELECT * FROM permissions");

        // dd($permissionssss);
          $permissions =Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }   
    public function store(Request $request)
    {

        $request->validate([
            'name'=>'required|string|min:3',

                    ] );
        $role=Role::create(['name'=>$request->name]);
        $role->syncPermissions($request->permissions);
        // dd('permissions created');
        return redirect()->route('admin.roles.index')->with("success","Role is created");


    }
    public function edit($id){
          $role=Role::find($id);
            $permissions =Permission::all();
           return view('admin.roles.edit',['role'=>$role,'permissions'=>$permissions]);
    }
    public function update(Request $request,$id){
        
        $role = Role::findOrFail($id);

        // 🔒 Prevent renaming or changing super-admin role
        if ($role->name === 'admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Sorry Admin  cannot be modified.');
        }
        $request->validate([
             'name'=>'required|string|min:3',

            ]);
            $role=Role::find($id);
            $role->name=$request->name;
            $role->save();
            $role->syncPermissions($request->permissions);

            return redirect()->route('admin.roles.index')->with("success","Role is Updated");

    }
    public function destroy($id){
        $role = Role::findOrFail($id);

        // 🔒 Prevent deleting super-admin role
        if ($role->name === 'admin') {
            return redirect()->back()->with("error", "Super Admin role cannot be deleted.");
        }

        $role=Role::find($id);
        $role->delete();
        return redirect()->back()->with("success",'Role Deleted');
         
    }
    public function createUser(){
         $roles = Role::where('name', '!=', 'admin')->get();
         return view('admin.user.add-user',compact('roles'));
    }
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:admins',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required'
        ]);

        if ($request->role === 'admin') {
            return redirect()->back()->with('error', 'You cannot create another admin.');
        }

        $admin = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin->assignRole($request->role);

        return redirect()->back()->with('success', 'User created successfully.');
    }
    
}
