<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use MongoDB\Driver\Session;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->paginate(25);
        return view('permissions.list', [
            'permissions' => $permissions
        ]);
    }

    public function permissionsSync(Request $request){
        $routeCollection = Route::getRoutes();
        $inputArray = [];
        $added_items = Permission::pluck('name')->toArray(); //remove them from route

        foreach ($routeCollection as $value) {
            if (isset($value->action['as'])) {
                array_push($inputArray, $value->action['as']);
            }
        }

        $need_to_add = array_diff($inputArray, $added_items);

        foreach ($need_to_add as $value) {
            Permission::create(['name' => $value]);
        }

        dd("ok");
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);
        if ($validator->passes()) {

            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission created successfully!');

        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $permission = Permission::findById($id);
        return view('permissions.edit', [
            'permission' => $permission
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id . ',id'
        ]);
        if ($validator->passes()) {
            $permission = Permission::findById($id);
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('permissions.index')->with('success', 'Permission Updated successfully!');

        } else {
            return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }
//    public function destroy(Request $request){
//        $id = $request->id;
//
//        $permission = Permission::find($id);
//
////        if($permission == null){
////            session()->flash("error","Permission not found");
////            return response()->json([
////                'status' => false,
////            ]);
////        }
////        $permission->delete();
////        session()->flash("success","Permission Deleted Successfully");
////        return response()->json([
////            'status' => true,
////        ]);
//    }

    public function destroy($id)
    {
            $permission = Permission::find($id);
             $permission->delete();
            return redirect(route('permissions.index'))->with('success', 'Permission deleted successfully!');
        }

}

