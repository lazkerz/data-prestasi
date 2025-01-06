<?php

namespace App\Http\Controllers;

use App\Models\Hmps;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HmpsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang sedang login

        // Awal query dengan relasi
        $query = Hmps::with(['user', 'members', 'prodi']);

        if ($user->hasRole('admin')) {
            // Admin dapat melihat semua data HMPS
            $hmps = $query;
        } elseif ($user->hasRole('hmps')) {
            // Pengguna role "hmps" hanya bisa melihat data HMPS yang dia kelola
            $hmps = $query->where('user_id', $user->id);
        } else {
            // Ambil nama prodi dari role pengguna
            $prodi = $user->getRoleNames()->first();

            // Validasi keberadaan prodi
            if (!$prodi) {
                // Jika role pengguna tidak memiliki nama prodi, kembalikan pesan error
                return redirect()->back()->withErrors(['error' => 'Role pengguna tidak valid atau tidak memiliki prodi terkait.']);
            }

            // Cari ID prodi berdasarkan nama
            $prodiRecord = Prodi::where('nama_prodi', $prodi)->first();

            if (!$prodiRecord) {
                // Jika Prodi tidak ditemukan, kembalikan pesan error
                return redirect()->back()->withErrors(['error' => 'Prodi "' . $prodi . '" tidak ditemukan.']);
            }

            $prodiId = $prodiRecord->id;

            // Role lain hanya bisa melihat data berdasarkan prodi_id
            $hmps = $query->where('prodi_id', $prodiId);
        }

        // Pagination atau semua data
        $perPage = $request->get('rows', 10); // Jumlah baris per halaman
        $isPaginated = $perPage !== 'all';

        $hmps = $isPaginated
            ? $hmps->paginate($perPage)
            : $hmps->get();

        return view('hmps.index', compact('hmps', 'isPaginated'));
    }





    public function create()
    {
        $user = Auth::user();

        // Check if user is admin
        if ($user->hasRole('admin')) {
            // Get all study programs without an HMPS
            $prodisWithoutHmps = Prodi::whereDoesntHave('hmps')->get();
            return view('hmps.create', compact('prodisWithoutHmps'));
        }

        // Check if user has any study program role
        $studyProgramRole = $user->roles
            ->whereNotIn('name', ['admin', 'ukm', 'hmps'])
            ->first();

        if (!$studyProgramRole) {
            abort(403, 'Only study program accounts can create HMPS.');
        }

        // Check if user already has an HMPS
        $existingHmps = Hmps::where('user_id', $user->id)->first();
        if ($existingHmps) {
            return redirect()->route('hmps.index')
                ->with('error', 'You already have an HMPS organization.');
        }

        // Get the corresponding prodi
        $prodi = Prodi::where('nama_prodi', $studyProgramRole->name)->first();
        if (!$prodi) {
            abort(404, 'Study program not found.');
        }

        return view('hmps.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Validate admin request
            $request->validate([
                'prodi_id' => 'required|exists:prodis,id', // Change prodi to prodis
                'description' => 'required|string'
            ]);

            $prodi = Prodi::findOrFail($request->prodi_id);

            // Validate no existing HMPS for this prodi
            if (Hmps::where('prodi_id', $prodi->id)->exists()) {
                return redirect()->back()
                    ->with('error', 'An HMPS already exists for this study program.');
            }

            try {
                DB::transaction(function () use ($request, $prodi) {
                    // Create a standardized username and email
                    $standardizedName = strtolower(str_replace(' ', '_', 'hmps_' . $prodi->nama_prodi));
                    $standardizedEmail = $standardizedName . '@hmps.polmed.com';

                    // Create a new user for HMPS
                    $newUser = User::create([
                        'name' => ucfirst($standardizedName),
                        'username' => $standardizedName,
                        'email' => $standardizedEmail,
                        'password' => Hash::make('12345678'), // Default password
                    ]);

                    // Assign role "hmps" to the new user
                    $newUser->assignRole('hmps');

                    // Create HMPS with prefix "HMPS"
                    Hmps::create([
                        'nama' => 'HMPS ' . $prodi->nama_prodi,
                        'user_id' => $newUser->id,
                        'prodi_id' => $prodi->id,
                        'description' => $request->input('description'),
                    ]);
                });

                return redirect()->route('hmps.index')
                    ->with('success', 'HMPS created successfully with a new account.');
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Failed to create HMPS: ' . $e->getMessage())
                    ->withInput();
            }
        } else {
            // Get user's study program role
            $studyProgramRole = $user->roles
                ->whereNotIn('name', ['admin', 'ukm', 'hmps'])
                ->first();

            if (!$studyProgramRole) {
                abort(403, 'Only study program accounts can create HMPS.');
            }

            $prodi = Prodi::where('nama_prodi', $studyProgramRole->name)->firstOrFail();

            // Validate no existing HMPS for this prodi
            if (Hmps::where('prodi_id', $prodi->id)->exists()) {
                return redirect()->back()
                    ->with('error', 'An HMPS already exists for this study program.');
            }

            try {
                DB::transaction(function () use ($request, $prodi, $studyProgramRole) {
                    // Create a standardized username and email
                    $standardizedName = strtolower(str_replace(' ', '_', 'hmps_' . $studyProgramRole->name));
                    $standardizedEmail = $standardizedName . '@hmps.polmed.com';

                    // Create a new user for HMPS
                    $newUser = User::create([
                        'name' => ucfirst($standardizedName),
                        'username' => $standardizedName,
                        'email' => $standardizedEmail,
                        'password' => Hash::make('12345678'), // Default password
                    ]);

                    // Assign role "hmps" to the new user
                    $newUser->assignRole('hmps');

                    // Create HMPS with prefix "HMPS"
                    Hmps::create([
                        'nama' => 'HMPS ' . $studyProgramRole->name,
                        'user_id' => $newUser->id,
                        'prodi_id' => $prodi->id,
                        'description' => $request->input('description'),
                    ]);
                });

                return redirect()->route('hmps.index')
                    ->with('success', 'HMPS created successfully with a new account.');
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Failed to create HMPS: ' . $e->getMessage())
                    ->withInput();
            }
        }
    }

    public function edit(Hmps $hmps)
    {
        $user = Auth::user();

        // Only allow admin or the HMPS's own user to edit
        

        return view('hmps.edit', compact('hmps'));
    }

    public function update(Request $request, Hmps $hmps)
    {
        $user = Auth::user();

       
        $request->validate([
            'description' => 'required|string',
            'username' => [
                'required',
                'string',
                Rule::unique('users')->ignore($hmps->user_id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($hmps->user_id),
            ],
            'new_password' => 'nullable|min:8',
        ]);

        try {
            DB::transaction(function () use ($request, $hmps) {
                // Update HMPS details
                $hmps->update([
                    'description' => $request->description,
                ]);

                // Update user account details
                $userData = [
                    'username' => $request->username,
                    'email' => $request->email,
                ];

                // Only update password if a new one is provided
                if ($request->filled('new_password')) {
                    $userData['password'] = Hash::make($request->new_password);
                }

                $hmps->user->update($userData);
            });

            return redirect()->route('hmps.index')
                ->with('success', 'HMPS updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update HMPS: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Hmps $hmps)
    {
        $user = Auth::user();

        // Only allow admin to delete HMPS
        
        try {
            DB::transaction(function () use ($hmps) {
                // Delete associated members first
                $hmps->members()->detach();

                // Get the user ID before deleting HMPS
                $userId = $hmps->user_id;

                // Delete the HMPS record
                $hmps->delete();

                // Delete the associated user account
                User::destroy($userId);
            });

            return redirect()->route('hmps.index')
                ->with('success', 'HMPS deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete HMPS: ' . $e->getMessage());
        }
    }



}
