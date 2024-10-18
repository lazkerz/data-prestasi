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

        // Ambil nilai dari parameter rows atau default ke 10
        $rows = $request->input('rows', 10);
        $search = $request->input('search');

        // Mulai dengan query dasar
        $query = Mahasiswa::with('prestasi');

        // Jika admin, tampilkan semua data prestasi
        if ($user->hasRole('admin')) {
            // Jika ada input pencarian, tambahkan kondisi pencarian
            if ($search) {
                $query->where('nama', 'like', "%{$search}%");
            }
        } else {
            // Tampilkan data sesuai dengan prodi user
            $prodi = $user->getRoleNames()->first();
            $query->where('prodi', $prodi);

            // Jika ada input pencarian, tambahkan kondisi pencarian
            if ($search) {
                $query->where('nama', 'like', "%{$search}%");
            }
        }

        // Lakukan paginasi pada query
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
        // Validasi input
        $request->validate([
            'nama_prestasi.*' => 'required|string|max:255',
            'deskripsi_prestasi.*' => 'required|string',
            'jenis_prestasi.*' => 'required|in:Akademik,Non-Akademik',
            'tingkatan_prestasi.*' => 'required|in:Lokal,Nasional,Internasional',
            'file_prestasi.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Cek apakah input prestasi ada
        if (!is_array($request->nama_prestasi) || empty($request->nama_prestasi)) {
            return redirect()->back()->withErrors('Data prestasi tidak ditemukan. Silakan isi setidaknya satu prestasi.');
        }

        // Tentukan path penyimpanan
        $storagePath = 'prestasi_files/' . $mahasiswa->prodi . '/' . $mahasiswa->nama;

        // Buat direktori jika belum ada
        if (!Storage::exists($storagePath)) {
            Storage::makeDirectory($storagePath, 0755, true); // Buat direktori dengan hak akses 755
        }

        foreach ($request->nama_prestasi as $index => $namaPrestasi) {
            $file = null;

            // Cek jika file ada
            if (isset($request->file_prestasi[$index]) && $request->file_prestasi[$index]->isValid()) {
                // Simpan file prestasi
                $file = $request->file_prestasi[$index]->storeAs(
                    $storagePath,
                    $request->file_prestasi[$index]->getClientOriginalName(),
                    'public'
                );
            }

            // Buat prestasi baru untuk setiap input
            Prestasi::create([
                'mahasiswa_id' => $mahasiswa->id,
                'nama_prestasi' => $namaPrestasi,
                'deskripsi_prestasi' => $request->deskripsi_prestasi[$index],
                'jenis_prestasi' => $request->jenis_prestasi[$index],
                'tingkatan_prestasi' => $request->tingkatan_prestasi[$index],
                'file_prestasi' => $file,
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
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'deskripsi_prestasi' => 'required|string',
            'jenis_prestasi' => 'required|in:Akademik,Non Akademik',
            'tingkatan_prestasi' => 'required|in:Lokal,Nasional,Internasional',
            'file_prestasi' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Update file jika ada
        if ($request->hasFile('file_prestasi')) {
            // Buat folder jika belum ada
            $folderPath = 'prestasi_files/' . $prestasi->mahasiswa->prodi . '/' . $prestasi->mahasiswa->nama;
            Storage::makeDirectory($folderPath);

            // Simpan file di folder tersebut
            $file = $request->file('file_prestasi')->store($folderPath, 'public');

            // Update file_prestasi di database
            $prestasi->update(['file_prestasi' => $file]);
        }

        $prestasi->update($request->except('file_prestasi'));

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diupdate');
    }

    public function destroy(Prestasi $prestasi)
    {
        // Hapus file prestasi dari storage jika ada
        if ($prestasi->file_prestasi) {
            Storage::delete('public/' . $prestasi->file_prestasi);
        }

        $prestasi->delete();
        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil dihapus');
    }
}
