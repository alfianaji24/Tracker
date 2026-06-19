<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('roles', 'groups')->get();
        return view('master.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('master.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
        ]);

        Permission::create($request->only(['name', 'slug', 'description']));
        return redirect()->route('master.permissions.index')->with('success', 'Permission berhasil ditambahkan.');
    }

    public function edit(Permission $permission)
    {
        return view('master.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'description' => 'nullable|string',
        ]);

        $permission->update($request->only(['name', 'slug', 'description']));
        return redirect()->route('master.permissions.index')->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('master.permissions.index')->with('success', 'Permission berhasil dihapus.');
    }
}
