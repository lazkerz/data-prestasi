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

        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-white uppercase bg-purple-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Mahasiswa</th>
                        <th scope="col" class="px-6 py-3">NIM</th>
                        <th scope="col" class="px-6 py-3">Prodi</th>
                        <th scope="col" class="px-6 py-3">Prestasi</th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswas as $mahasiswa)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $mahasiswa->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $mahasiswa->nim }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $mahasiswa->prodi }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($mahasiswa->prestasi as $prestasi)
                            <div class="mb-2">
                                {{ $prestasi->nama_prestasi }} ({{ $prestasi->tingkatan_prestasi }})
                                <div class="mt-1 space-x-2">
                                    <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="font-medium text-purple-600 hover:text-purple-900">Edit</a>
                                    <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus prestasi ini?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('prestasi.create', $mahasiswa->id) }}" class="font-medium text-purple-600 hover:text-purple-900">Tambah Prestasi</a>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data tersedia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Link Pagination -->
        @if ($isPaginated)
        <div class="mt-4">
            {{ $mahasiswas->appends(request()->query())->links() }}
        </div>
        @else
        <div class="mt-4">
            Showing all {{ $mahasiswas->count() }} entries
        </div>
        @endif

    </div>
</div>
@endsection