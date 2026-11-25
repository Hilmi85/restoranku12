<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::all();

        return view('admin.role.index', compact('roles'));
    }

    public function create() {
        return view('admin.role.create');
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        Role::create($validatedData);

        return redirect()->route('roles.index')->with('success', 'Peran berhasil ditambahkan.');
    }

    public function edit(string $id) {
        $role = Role::findOrFail($id);

        return view('admin.role.edit', compact('role'));
    }

    public function update(Request $request, string $id) {
        $validatedData = $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $role = Role::findOrFail($id);
        $role->update($validatedData);

        return redirect()->route('roles.index')->with('success', 'Peran berhasil diperbarui.');
    }

    public function destroy(string $id) {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Peran berhasil dihapus.');
    }
}
