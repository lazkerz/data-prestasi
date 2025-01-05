<?php

namespace App\Http\Controllers;

use App\Models\UKM;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UKMController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Only admin can see all UKMs
        $query = UKM::with(['user', 'members']);

        if (!$user->hasRole('admin')) {
            // If UKM role, only show their own UKM
            $query->where('user_id', $user->id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%");
        }

        $perPage = $request->get('rows', 10);
        $isPaginated = $perPage !== 'all';

        $ukms = $isPaginated
            ? $query->paginate($perPage)
            : $query->get();

        return view('ukm.index', compact('ukms', 'isPaginated'));
    }

    public function create()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        return view('ukm.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:8',
            'description' => 'nullable|string'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role' => 'ukm'
                ]);

                UKM::create([
                    'nama' => $request->nama,
                    'user_id' => $user->id,
                    'description' => $request->description
                ]);
            });

            return redirect()->route('ukm.index')->with('success', 'UKM created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating UKM: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create UKM')->withInput();
        }
    }

    public function edit($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        $ukm = UKM::findOrFail($id);
        return view('ukm.edit', compact('ukm'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $ukm = UKM::with('user')->findOrFail($id);

        $rules = [
            'nama' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($ukm->user->id)],
            'username' => ['required', Rule::unique('users')->ignore($ukm->user->id)],
            'password' => 'nullable|min:8|confirmed'
        ];

        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($ukm, $validated, $request) {
                // Update UKM information
                $ukm->update([
                    'nama' => $validated['nama'],
                    'description' => $validated['description']
                ]);

                // Update user information
                $userData = [
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'username' => $validated['username']
                ];

                // Only update password if provided
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $ukm->user->update($userData);
            });

            return redirect()->route('ukm.index')->with('success', 'UKM and account information updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating UKM: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update UKM and account information')->withInput();
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $ukm = UKM::findOrFail($id);
        try {
            DB::transaction(function () use ($ukm) {
                $user = $ukm->user;
                $ukm->delete();
                $user->delete();
            });
            return redirect()->route('ukm.index')->with('success', 'UKM deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete UKM');
        }
    }


    public function showMembers(UKM $ukm)
    {
        $user = Auth::user();

        // Allow access if admin or if UKM user viewing their own UKM
        // if (!$user->hasRole('admin') && ($user->role !== 'ukm' || $user->id !== $ukm->user_id)) {
        //     abort(403, 'Unauthorized action.');
        // }

        // Fetch paginated mahasiswa not already members
        $mahasiswas = Mahasiswa::whereNotIn('id', $ukm->members->pluck('id'))
            ->paginate(10);

        return view('ukm.members', [
            'ukm' => $ukm->load('members'),
            'mahasiswas' => $mahasiswas
        ]);
    }

    public function searchMahasiswa(Request $request)
    {
        $user = Auth::user();

        // Validasi input pencarian
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $search = $request->input('search');
        $query = Mahasiswa::query();

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%$search%")
                    ->orWhere('nim', 'LIKE', "%$search%");
            });
        }

        // Batasan data berdasarkan peran pengguna
        // if (!$user->hasRole('admin')) {
        //     return response()->json([], 403); // Hanya admin yang bisa melihat semua mahasiswa
        // }

        $mahasiswas = $query->limit(10)->get(['id', 'nama', 'nim', 'prodi']); // Batasi hasil dan pilih kolom spesifik

        return response()->json($mahasiswas);
    }



    public function addMembers(Request $request, $ukmId)
    {
        $ukm = Ukm::findOrFail($ukmId);

        try {
            // Debug data sebelum validasi
            Log::info('Data yang diterima:', $request->all());

            $request->validate([
                'members' => 'required|array',
                'members.*.id' => 'required|exists:mahasiswa,id',
                'members.*.position' => 'required|string|max:255',
            ]);

            foreach ($request->members as $mahasiswaId => $memberData) {
                DB::table('ukm_members')->updateOrInsert(
                    ['ukm_id' => $ukmId, 'mahasiswa_id' => $memberData['id']],
                    ['position' => $memberData['position'], 'created_at' => now(), 'updated_at' => now()]
                );
            }

            return redirect()->route('ukm.members', $ukmId)->with('success', 'Anggota berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Error adding members: " . $e->getMessage());
            return redirect()->route('ukm.members', $ukmId)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function updateMember(Request $request, $ukmId, $memberId)
    {
        $request->validate([
            'position' => 'required|string|max:255',
        ]);

        $ukm = Ukm::findOrFail($ukmId);
        $member = $ukm->members()->findOrFail($memberId);
        $ukm->members()->updateExistingPivot($memberId, ['position' => $request->position]);

        return redirect()->route('ukm.members', $ukmId)->with('success', 'Anggota berhasil diperbarui.');
    }





    public function removeMember(UKM $ukm, Mahasiswa $member)
    {
        $user = Auth::user();

        // if (!$user->hasRole('admin') && ($user->role !== 'ukm' || $user->id !== $ukm->user_id)) {
        //     abort(403, 'Unauthorized action.');
        // }

        try {
            $ukm->members()->detach($member->id);
            return redirect()->back()->with('success', 'Member removed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove member');
        }
    }
}
