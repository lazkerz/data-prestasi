<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    // Display the list of Mahasiswa
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil nilai dari parameter rows atau default ke 10
        $rows = $request->input('rows', 10);
        $search = $request->input('search');

        // Mulai dengan query dasar
        $query = Mahasiswa::query();

        // Jika user adalah admin, ambil semua data
        if ($user->hasRole('admin')) {
            // Jika ada input pencarian, tambahkan kondisi pencarian
            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }
            $mahasiswa = $query->paginate($rows);
        } else {
            // Jika peran pengguna cocok dengan Prodi tertentu, filter data Mahasiswa
            $prodi = $user->getRoleNames()->first();

            // Jika ada input pencarian, tambahkan kondisi pencarian
            if ($search) {
                $query->where('prodi', $prodi)
                    ->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            } else {
                $query->where('prodi', $prodi);
            }

            $mahasiswa = $query->paginate($rows);
        }

        return view('mahasiswa.index', compact('mahasiswa', 'search'));
    }


    public function create()
    {
        $user = Auth::user();

        // Jika user adalah admin, tampilkan semua prodi
        if ($user->hasRole('admin')) {
            $prodiList = Prodi::all();
        } else {
            // Tampilkan hanya prodi yang sesuai dengan role user
            $roleProdi = $user->getRoleNames()->first();
            $prodiList = Prodi::where('nama_prodi', $roleProdi)->get();
        }

        return view('mahasiswa.create', compact('prodiList'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $user = Auth::user();

        // Jika user adalah admin, tampilkan semua prodi
        if ($user->hasRole('admin')) {
            $prodiList = Prodi::all();
        } else {
            // Tampilkan hanya prodi yang sesuai dengan role user
            $roleProdi = $user->getRoleNames()->first();
            $prodiList = Prodi::where('nama_prodi', $roleProdi)->get();
        }

        return view('mahasiswa.edit', compact('mahasiswa', 'prodiList'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
            'jenis_kelamin' => 'required',
            'prodi' => 'required',
            'jenjang' => 'required',
            'agama' => 'required',
            'angkatan' => 'required',
        ]);

        $mahasiswa->update($validatedData);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa updated successfully.');
    }

    // Remove the specified Mahasiswa from storage
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa deleted successfully.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'nim' => 'required|unique:mahasiswa,nim',
            'jenis_kelamin' => 'required',
            'prodi' => 'required',
            'jenjang' => 'required',
            'agama' => 'required',
            'angkatan' => 'required',
        ]);

        Mahasiswa::create($validatedData);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa created successfully.');
    }

    public function show($nim)
    {
        // Ambil data mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::with('prestasi')->where('nim', $nim)->first();

        // Jika mahasiswa tidak ditemukan, tampilkan pesan error
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.index')->withErrors('Mahasiswa tidak ditemukan.');
        }

        return view('mahasiswa.show', compact('mahasiswa'));
    }
}
