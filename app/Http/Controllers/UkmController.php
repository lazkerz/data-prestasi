<?php

namespace App\Http\Controllers;

use App\Models\UKM;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UKMController extends Controller
{
    // Tampilkan daftar UKM dengan pagination
    public function index(Request $request)
{
    $user = Auth::user();
    $ukms = [];
    $canCreateUKM = true; // Default assumption that the user can create a UKM

    // Jika admin, tampilkan semua UKM dengan pagination
    if ($user->hasRole('admin')) {
        $ukms = UKM::paginate(10); // Menambahkan pagination
    } else {
        // Jika prodi user, tampilkan hanya HMPS yang sesuai dengan prodi user
        $prodi = $user->getRoleNames()->first();
        $ukms = UKM::where('nama', 'like', 'HMPS%')
            ->where('nama', 'like', "%$prodi%")
            ->paginate(10);

        // Cek jika prodi user sudah memiliki UKM dengan nama prodi
        if (UKM::where('nama', 'HMPS ' . $prodi)->exists()) {
            $canCreateUKM = false; // Jika ada, user tidak bisa menambah UKM baru
        }
    }

    return view('ukm.index', [
        'ukms' => $ukms,
        'isPaginated' => true, // Menandakan bahwa pagination aktif
        'canCreateUKM' => $canCreateUKM // Mengirimkan status untuk tombol tambah UKM
    ]);
}


    // Halaman untuk membuat UKM baru
    public function create()
    {
        $user = Auth::user();

        // Jika user adalah prodi user, nama UKM langsung "HMPS Prodi"
        $ukmName = null;
        if ($user->hasRole('prodi') && $user->prodi) {
            $ukmName = 'HMPS ' . $user->prodi->nama_prodi; // Mengambil nama prodi dari relasi
        }

        // Ambil mahasiswa sesuai prodi user
        if ($user->hasRole('admin')) {
            $mahasiswas = Mahasiswa::limit(10)->get(); // Ambil 10 mahasiswa untuk admin
        } else {
            // Jika bukan admin, ambil mahasiswa sesuai prodi
            $prodi = $user->getRoleNames()->first(); // Mengambil nama prodi dari role
            $mahasiswas = Mahasiswa::where('prodi', $prodi)->limit(10)->get(); // Ambil 10 mahasiswa berdasarkan prodi
        }

        return view('ukm.create', compact('ukmName', 'mahasiswas'));
    }


    public function searchMahasiswa(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        // Query dasar untuk mahasiswa
        $query = Mahasiswa::query();

        // Jika user adalah admin, tampilkan semua mahasiswa
        if ($user->hasRole('admin')) {
            if ($search) {
                $query->where('nama', 'LIKE', "%$search%")
                    ->orWhere('nim', 'LIKE', "%$search%");
            }
        } else {
            // Tampilkan mahasiswa sesuai prodi user
            $prodi = $user->getRoleNames()->first(); // Mengambil nama prodi dari role
            $query->where('prodi', $prodi);

            // Jika ada input pencarian, tambahkan kondisi pencarian
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'LIKE', "%$search%")
                        ->orWhere('nim', 'LIKE', "%$search%");
                });
            }
        }

        $mahasiswas = $query->limit(10)->get(); // Batasi 10 hasil

        return response()->json($mahasiswas);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'mahasiswa_ids' => 'required|array', // Ensure mahasiswa_ids is received as an array
            'jabatan' => 'required|array', // Ensure position is set for each mahasiswa
            'file_prestasi.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validate multiple files
        ]);

        if (!$user->hasRole('admin')) {
            $prodi = $user->getRoleNames()->first();
            $request->merge(['nama' => 'HMPS ' . $prodi]);
        }

        // Save UKM
        $ukm = UKM::create([
            'nama' => $request->nama,
        ]);

        // Attach selected mahasiswa with their respective jabatan
        foreach ($request->mahasiswa_ids as $index => $mahasiswaId) {
            $ukm->mahasiswas()->attach($mahasiswaId, ['jabatan' => $request->jabatan[$index]]);
        }

        // Handle file uploads
        $filePaths = []; // Array to hold file paths
        if ($request->hasFile('file_prestasi')) {
            foreach ($request->file('file_prestasi') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = "prestasi/ukm/{$ukm->nama}";
                $file->storeAs($filePath, $fileName, 'public'); // Save in 'storage/app/public'
                $filePaths[] = $filePath . '/' . $fileName; // Add to array
            }
        }

        // Save file paths as JSON
        $ukm->file_prestasi = json_encode($filePaths);
        $ukm->save(); // Save changes including file paths

        return redirect()->route('ukm.index')->with('success', 'UKM dan anggota berhasil dibuat.');
    }



    // Menambahkan anggota UKM dengan jabatan
    public function addMember(Request $request, UKM $ukm)
    {
        $user = Auth::user();

        // Jika user dari prodi, pastikan mereka hanya bisa menambah anggota dari prodi mereka
        if (!$user->hasRole('admin')) {
            $prodi = $user->getRoleNames()->first();

            // Validasi bahwa mahasiswa yang akan ditambahkan berasal dari prodi yang sesuai
            $request->validate([
                'mahasiswa_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($prodi) {
                        $mahasiswa = Mahasiswa::find($value);
                        if ($mahasiswa && $mahasiswa->prodi !== $prodi) {
                            $fail('Mahasiswa yang dipilih harus berasal dari prodi ' . $prodi);
                        }
                    }
                ]
            ]);
        }

        // Tambahkan anggota ke UKM dengan jabatan
        $ukm->mahasiswas()->attach($request->mahasiswa_id, ['jabatan' => $request->jabatan]);

        return redirect()->route('ukm.show', $ukm)->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Menampilkan detail UKM beserta anggota-anggotanya
    public function show(UKM $ukm)
    {
        return view('ukm.show', compact('ukm'));
    }

    // Menghapus UKM
    public function destroy(UKM $ukm)
    {
        // Hapus relasi mahasiswa dengan UKM
        $ukm->mahasiswas()->detach();

        // Hapus UKM itu sendiri
        $ukm->delete();

        return redirect()->route('ukm.index')->with('success', 'UKM berhasil dihapus.');
    }

    // Assuming you have a method to get the UKM data
    public function edit($id)
    {
        $ukm = UKM::findOrFail($id);

        // Decode file_prestasi if it's stored as JSON
        if (is_string($ukm->file_prestasi)) {
            $ukm->file_prestasi = json_decode($ukm->file_prestasi, true); // Convert JSON string to an array
        }

        // Get the logged-in user
        $user = Auth::user();

        // Fetch students based on user role
        if ($user->hasRole('admin')) {
            $mahasiswas = Mahasiswa::limit(10)->get();
        } else {
            $prodi = $user->getRoleNames()->first();
            $mahasiswas = Mahasiswa::where('prodi', $prodi)->limit(10)->get();
        }

        return view('ukm.edit', compact('ukm', 'mahasiswas'));
    }



    public function update(Request $request, $id)
    {
        $ukm = Ukm::findOrFail($id);

        // Validate the incoming data
        $request->validate([
            'nama' => 'required|string|max:255',
            'mahasiswa_ids' => 'array',
            'jabatan' => 'array',
            'file_prestasi.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'removed_files' => 'array',
        ]);

        // Sync mahasiswa with jabatan
        $mahasiswaData = [];
        if ($request->has('mahasiswa_ids') && $request->has('jabatan')) {
            foreach ($request->mahasiswa_ids as $index => $mahasiswaId) {
                $mahasiswaData[$mahasiswaId] = ['jabatan' => $request->jabatan[$index]];
            }
            $ukm->mahasiswas()->sync($mahasiswaData);
        }

        // Handle file uploads
        $filePaths = $ukm->file_prestasi ? json_decode($ukm->file_prestasi, true) : [];

        // Remove files marked for deletion
        if ($request->has('removed_files')) {
            foreach ($request->removed_files as $fileToRemove) {
                if (($key = array_search($fileToRemove, $filePaths)) !== false) {
                    Storage::delete('public/' . $fileToRemove); // Delete the file from storage
                    unset($filePaths[$key]); // Remove the file from the list
                }
            }
        }

        // Add new files
        if ($request->hasFile('file_prestasi')) {
            foreach ($request->file('file_prestasi') as $file) {
                $path = $file->store('prestasi', 'public');
                $filePaths[] = $path;
            }
        }

        // Update the UKM's file_prestasi field with the merged array of old and new files
        $ukm->file_prestasi = json_encode($filePaths);

        // Save the UKM model
        $ukm->save();

        return redirect()->route('ukm.index')->with('success', 'UKM/HMPS successfully updated.');
    }

}
