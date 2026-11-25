<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::whereHas('role', function ($query) {
            $query->where('role_name', '!=', 'customer');
        })->orderBy('fullname')->get();

        return view('admin.user.index', compact('users'));
    }

    public function create() {
        $roles = Role::where('role_name', '!=', 'customer')->get();

        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ],
        [
            'username.required' => 'Nama pengguna wajib diisi.',
            'username.unique' => 'Nama pengguna sudah digunakan.',
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Alamat email harus valid.',
            'email.unique' => 'Alamat email sudah digunakan.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'role_id.required' => 'Peran wajib dipilih.',
            'role_id.exists' => 'Peran yang dipilih tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi harus terdiri dari minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        
        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    public function edit(string $id) {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user) {

        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value !== null && $value !== '' && Hash::check($value, $user->password)) {
                        $fail('Password baru tidak boleh sama dengan password lama.');
                    }
                },
            ],
        ],
        [
            'username.required' => 'Nama pengguna wajib diisi.',
            'username.unique' => 'Nama pengguna sudah digunakan.',
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Alamat email harus valid.',
            'email.unique' => 'Alamat email sudah digunakan.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'role_id.required' => 'Peran wajib dipilih.',
            'role_id.exists' => 'Peran yang dipilih tidak valid.',
            'password.min' => 'Kata sandi harus terdiri dari minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(string $id) {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil dihapus.');
    }
}
