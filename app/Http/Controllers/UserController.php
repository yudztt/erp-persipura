<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('nama')->get();
        $roles = Role::orderBy('nama_role')->get();

        return view('system.users', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_role'  => 'required|exists:roles,id_role',
            'nama'     => 'required|string|max:150',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'id_role.required'   => 'Role wajib dipilih.',
            'nama.required'      => 'Nama pengguna wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'id_role' => 'required|exists:roles,id_role',
            'nama'    => 'required|string|max:150',
            'email'   => 'required|email|max:100|unique:users,email,' . $user->id_user . ',id_user',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'id_role.required' => 'Role wajib dipilih.',
            'nama.required'    => 'Nama pengguna wajib diisi.',
            'email.required'   => 'Email wajib diisi.',
            'email.unique'     => 'Email sudah digunakan.',
            'password.min'     => 'Password minimal 8 karakter.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Cegah hapus diri sendiri
        if ($user->id_user === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
