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

        $rows = $request->input('rows', 10);
        $search = $request->input('search');

        $query = Mahasiswa::query();

        if ($user->hasRole('admin')) {
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('prodi', 'like', "%{$search}%");
                });
            }
        } else {
            $prodi = $user->getRoleNames()->first();
            $query->where('prodi', $prodi);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            }
        }

        $isPaginated = $rows !== 'all';

        if ($isPaginated) {
            $mahasiswa = $query->paginate((int)$rows);
        } else {
            $total = $query->count();
            $mahasiswa = $query->paginate($total);
        }

        return view('mahasiswa.index', compact('mahasiswa', 'search', 'rows', 'isPaginated'));
    }

    // Jika menggunakan barryvdh/laravel-dompdf

    public function exportPDF()
    {
        $mahasiswas = Mahasiswa::with('prestasi')->get();

        $pdf = PDF::loadView('prestasi.export-pdf', [
            'mahasiswas' => $mahasiswas
        ]);

        return $pdf->download('daftar-prestasi-mahasiswa.pdf');
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
        // Initialize mahasiswa as null
        $mahasiswa = null;

        // Only try to find mahasiswa if NIM is numeric
        if (is_numeric($nim)) {
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        }

        // Return view - it will show the message if $mahasiswa is null
        return view('mahasiswa.show', compact('mahasiswa'));
    }
}
