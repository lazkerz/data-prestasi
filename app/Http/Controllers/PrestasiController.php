<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PrestasiController extends Controller
{

    public function date()
    {
        return date('d-m-y');
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $rows = $request->input('rows', 10);
        $search = $request->input('search');


        $query = Mahasiswa::with(['prestasi' => function ($query) use ($request) {
            // Apply date filter if dates are provided
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
        }]);

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
            'tahun_prestasi' => 'required|date|before_or_equal:today',
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
                'tahun_prestasi' => $request->tahun_prestasi,
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
            'nama_prestasi.*' => 'required|string|max:255',
            'deskripsi_prestasi.*' => 'required|string',
            'jenis_prestasi.*' => 'required|in:Akademik,Non-Akademik',
            'tingkatan_prestasi.*' => 'required|in:Lokal,Nasional,Internasional',
            'file_prestasi.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'tahun_prestasi' => 'required|date|before_or_equal:today',
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

        $prestasi->update($request->only([
            'nama_prestasi',
            'deskripsi_prestasi',
            'jenis_prestasi',
            'tingkatan_prestasi',
            'tahun_prestasi',
        ]));

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diupdate');
    }


    public function exportPDF()
    {
        $mahasiswas = Mahasiswa::has('prestasi')->with('prestasi')->get();

        if ($mahasiswas->isEmpty()) {
            return back()->with('error', 'Tidak ada data prestasi untuk di-export');
        }

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #a855f7;
                    color: white;
                    font-size: 12px;
                    text-transform: uppercase;
                }
                td {
                    font-size: 14px;
                }
                h2 {
                    color: #a855f7;
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 16px;
                }
                .prestasi-list {
                    margin: 0;
                    padding: 0;
                }
                .prestasi-item {
                    margin-bottom: 4px;
                }
            </style>
        </head>
        <body>
            <h2>Prestasi Mahasiswa - Mahasiswi Politeknik Negeri Medan</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Nama Prestasi</th>
                        <th>Jenis Prestasi</th>
                        <th>Tingkatan Prestasi</th>
                        <th>Tahun Prestasi</th>
                    </tr>
                </thead>
                <tbody>';

        $no = 1;
        foreach ($mahasiswas as $mahasiswa) {
            if ($mahasiswa->prestasi->isNotEmpty()) {
                $prestasiList = '';
                foreach ($mahasiswa->prestasi as $index => $prestasi) {
                    $prestasiList .= "<div class='prestasi-item'>" . $prestasi->nama_prestasi . "</div>";
                }

                $html .= '
                    <tr>
                        <td>' . $no++ . '</td>
                        <td>' . $mahasiswa->nama . '</td>
                        <td>' . $mahasiswa->prodi . '</td>
                        <td class="prestasi-list">' . $prestasiList . '</td>
                        <td>' . $prestasi->jenis_prestasi . '</td>
                        <td>' . $prestasi->tingkatan_prestasi . '</td>
                         <td>' . $prestasi->tahun_prestasi . '</td>
                    </tr>';
            }
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        $pdf = PDF::loadHTML($html);
        return $pdf->download('daftar-prestasi-mahasiswa.pdf');
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
