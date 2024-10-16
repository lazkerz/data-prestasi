@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-purple-400 mb-4">Daftar Prestasi Mahasiswa</h2>

        <!-- Dropdown untuk memilih jumlah baris yang ditampilkan -->
        <div class="my-4 flex justify-between gap-2">
            <div>
                <label for="rows" class="mr-2 text-sm">Show rows:</label>
                <select id="rows" name="rows" class="border rounded px-4 py-2 bg-white appearance-none pr-8"
                    onchange="window.location.href='{{ route('prestasi.index') }}?rows=' + this.value + '&search={{ request('search') }}'">
                    <option value="10" {{ request('rows', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('rows', 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('rows', 10) == 100 ? 'selected' : '' }}>100</option>
                    <option value="all" {{ request('rows') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            <!-- Search Box -->
            <form action="{{ route('prestasi.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" placeholder="Cari Mahasiswa..." class="border rounded px-2 py-1 mr-2" value="{{ request('search') }}">
                <button type="submit" class="bg-purple-400 text-white px-3 py-1 rounded">Cari</button>
            </form>
        </div>

        <table class="min-w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-purple-400 text-white">
                    <th class="border border-gray-200 px-4 py-2">Nama Mahasiswa</th>
                    <th class="border border-gray-200 px-4 py-2">NIM</th>
                    <th class="border border-gray-200 px-4 py-2">Prodi</th>
                    <th class="border border-gray-200 px-4 py-2">Prestasi</th>
                    <th class="border border-gray-200 px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="mahasiswaTable">
                @forelse ($mahasiswas as $mahasiswa)
                <tr class="bg-gray-100 hover:bg-gray-200">
                    <td class="border border-gray-200 px-4 py-2">{{ $mahasiswa->nama }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $mahasiswa->nim }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $mahasiswa->prodi }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        @foreach ($mahasiswa->prestasi as $prestasi)
                        <div class="mb-2">
                            {{ $prestasi->nama_prestasi }} ({{ $prestasi->tingkatan_prestasi }})
                            <br>
                            <!-- Tautan Edit dan Delete untuk setiap prestasi -->
                            <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="text-blue-500 hover:underline">Edit</a> |
                            <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Apakah Anda yakin ingin menghapus prestasi ini?')">Delete</button>
                            </form>
                        </div>
                        @endforeach
                    </td>
                    <td class="border border-gray-200 px-4 py-2">
                        <a href="{{ route('prestasi.create', $mahasiswa->id) }}" class="bg-purple-400 text-white px-2 py-1 rounded hover:bg-purple-500">Tambah Prestasi</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Link Pagination -->
        <div class="mt-4">
            {{ $mahasiswas->links() }}
        </div>

    </div>
</div>
@endsection