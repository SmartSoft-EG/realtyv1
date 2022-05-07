<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return Role::all();
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|min:3|unique:roles'
        ]);
        return Role::create(request(['name', 'permissions']));
    }
    public function show(Role $role)
    {
        return $role->load('users:id,name');
    }

    public function update(Role $role)
    {
        if (request()->attach_users) {
            $role->users()->syncWithoutDetaching(request()->users);
            return $role->users->map->only('id', 'name');
        }

        request()->validate([
            'name' => 'required|min:5|unique:roles,name,' . $role->id
        ]);
        $role->update(request(['name', 'permissions']));
        if (request()->role_users && is_array(request()->role_users)) {
            $role->users()->sync(request()->role_users);
            return $role->load('users:id,name');
        }
        return $role;
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['success' => 'Role was delete'], 202);
    }
}
