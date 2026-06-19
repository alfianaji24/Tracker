<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionGroupController extends Controller
{
    public function index()
    {
        $groups = PermissionGroup::withCount('permissions')->get();
        return view('master.permission-groups.index', compact('groups'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('master.permission-groups.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $group = PermissionGroup::create($request->only(['name', 'description']));

        if ($request->has('permissions')) {
            $group->permissions()->sync($request->permissions);
        }

        return redirect()->route('master.permission-groups.index')->with('success', 'Permission Group berhasil ditambahkan.');
    }

    public function edit(PermissionGroup $permissionGroup)
    {
        $permissions = Permission::all();
        return view('master.permission-groups.edit', compact('permissionGroup', 'permissions'));
    }

    public function update(Request $request, PermissionGroup $permissionGroup)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionGroup->update($request->only(['name', 'description']));

        if ($request->has('permissions')) {
            $permissionGroup->permissions()->sync($request->permissions);
        }

        return redirect()->route('master.permission-groups.index')->with('success', 'Permission Group berhasil diperbarui.');
    }

    public function destroy(PermissionGroup $permissionGroup)
    {
        $permissionGroup->permissions()->detach();
        $permissionGroup->delete();
        return redirect()->route('master.permission-groups.index')->with('success', 'Permission Group berhasil dihapus.');
    }
}
