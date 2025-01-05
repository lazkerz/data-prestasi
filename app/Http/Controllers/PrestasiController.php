<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $rows = $request->input('rows', 10);
        $search = $request->input('search');

        $query = Mahasiswa::with('prestasi');

        if ($user->hasRole('admin')) {
            if ($search) {
                $query->where('nama', 'like', "%{$search}%");
            }
        } else {
            $prodi = $user->getRoleNames()->first();
            $query->where('prodi', $prodi);

            if ($search) {
                $query->where('nama', 'like', "%{$search}%");
            }
        }
        $isPaginated = $rows !== 'all';

        if ($isPaginated) {
            $mahasiswas = $query->paginate($rows);
        } else {
            $mahasiswas = $query->get();
        }

        return view('prestasi.index', compact('mahasiswas', 'search', 'rows', 'isPaginated'));
    }

    public function create(Mahasiswa $mahasiswa)
    {
        return view('prestasi.create', compact('mahasiswa'));
    }

    public function store(Request $request, Mahasiswa $mahasiswa)
    {
        $validatedData = $request->validate([
            'nama_prestasi.*' => 'required|string|max:255',
            'deskripsi_prestasi.*' => 'required|string',
            'jenis_prestasi.*' => 'required|in:Akademik,Non-Akademik',
            'tingkatan_prestasi.*' => 'required|in:Lokal,Nasional,Internasional',
            'file_prestasi.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if (!is_array($request->nama_prestasi) || empty($request->nama_prestasi)) {
            return redirect()->back()->withErrors('Data prestasi tidak ditemukan. Silakan isi setidaknya satu prestasi.');
        }

        $storagePath = 'public/prestasi_files/' . $mahasiswa->prodi . '/' . $mahasiswa->nama;

        if (!Storage::exists($storagePath)) {
            Storage::makeDirectory($storagePath);
        }

        foreach ($request->nama_prestasi as $index => $namaPrestasi) {
            $file = null;

            if (isset($request->file_prestasi[$index]) && $request->file_prestasi[$index]->isValid()) {
                $file = $request->file_prestasi[$index]->storeAs(
                    $storagePath,
                    $request->file_prestasi[$index]->getClientOriginalName()
                );
            }

            Prestasi::create([
                'mahasiswa_id' => $mahasiswa->id,
                'nama_prestasi' => $namaPrestasi,
                'deskripsi_prestasi' => $request->deskripsi_prestasi[$index],
                'jenis_prestasi' => $request->jenis_prestasi[$index],
                'tingkatan_prestasi' => $request->tingkatan_prestasi[$index],
                'file_prestasi' => $file ? str_replace('public/', '', $file) : null,
            ]);
        }

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil ditambahkan');
    }

    public function edit(Prestasi $prestasi)
    {
        return view('prestasi.edit', compact('prestasi'));
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $validatedData = $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'deskripsi_prestasi' => 'required|string',
            'jenis_prestasi' => 'required|in:Akademik,Non-Akademik',
            'tingkatan_prestasi' => 'required|in:Lokal,Nasional,Internasional',
            'file_prestasi' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $folderPath = 'public/prestasi_files/' . $prestasi->mahasiswa->prodi . '/' . $prestasi->mahasiswa->nama;

        if ($request->hasFile('file_prestasi') && $request->file('file_prestasi')->isValid()) {
            $file = $request->file('file_prestasi')->store($folderPath);

            // Hapus file lama jika ada
            if ($prestasi->file_prestasi) {
                Storage::delete('public/' . $prestasi->file_prestasi);
            }

            $prestasi->update(['file_prestasi' => str_replace('public/', '', $file)]);
        }

        $prestasi->update($request->only(['nama_prestasi', 'deskripsi_prestasi', 'jenis_prestasi', 'tingkatan_prestasi']));

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diupdate');
    }

    public function destroy(Prestasi $prestasi)
    {
        if ($prestasi->file_prestasi) {
            Storage::delete('public/' . $prestasi->file_prestasi);
        }

        $prestasi->delete();

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil dihapus');
    }
}
