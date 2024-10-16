<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar user
    public function index(Request $request)
    {
        // Ambil nilai dari parameter rows atau default ke 10
        $rows = $request->input('rows', 10);
        $search = $request->input('search');

        // Mulai dengan query dasar
        $query = User::query();

        // Jika ada input pencarian, tambahkan kondisi pencarian
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // Tambahkan pagination berdasarkan nilai rows
        if ($rows === 'all') {
            $users = $query->get(); // Ambil semua data jika 'all' dipilih
        } else {
            $users = $query->paginate($rows); // Paginate sesuai jumlah rows
        }

        // Kembalikan view dengan data users dan parameter untuk pencarian
        return view('users.index', compact('users'))->with('search', $search);
    }


    // Form untuk menambahkan user baru
    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all(); // Fetch all roles
        return view('users.create', compact('roles'));
    }


    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username, // Include the username
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }



    // Form untuk edit user
    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all(); // Fetch all roles
        return view('users.edit', compact('user', 'roles'));
    }


    // Update data user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
