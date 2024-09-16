<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::orderBy('name', 'asc')->paginate(10);
       return view('roles.list',[
           'roles' => $roles
       ]);
    }
    public function create(){
        $permissions = Permission::orderBy('name','asc')->get();
        return view('roles.create',[
            'permissions' => $permissions
        ]);
    }
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required||unique:roles|min:3'
        ]);
        if ($validator->passes()) {

           $role = Role::create(['name'=>$request->name]);
           if(!empty($request->permission)){
               foreach($request->permission as $name){
                   $role->givePermissionTo($name);
               }
           }
            return redirect()->route('roles.index')->with('success', 'Role added successfully!');

        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }
    public function edit($id){
        $role= Role::findById($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name','asc')->get();
        return view('roles.edit',[
            'permissions' => $permissions,
            'hasPermissions'=> $hasPermissions,
            'role' => $role

        ]);
    }
    public function update(Request $request, $id){
        $role= Role::findById($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,'.$id.',id'
        ]);
        if ($validator->passes()) {
            $role->name =$request->name;
            $role->save();
            if(!empty($request->permission)){
                $role->syncPermissions($request->permission);
            }else{
                $role->syncPermissions([]);
            }
            return redirect()->route('roles.index')->with('success', 'Role Updated successfully!');

        } else {
            return redirect()->route('roles.edit',$id)->withInput()->withErrors($validator);
        }
    }
    public function destroy($id){
        $role = Role::findOrfail($id);
        $role->delete();
        return redirect(route('roles.index'))->with('success', 'Roles deleted successfully!');
    }
}
