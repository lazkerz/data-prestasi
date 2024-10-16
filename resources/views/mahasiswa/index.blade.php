@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-purple-400 mb-2">Daftar Mahasiswa</h2>
        <a href="{{ route('mahasiswa.create') }}" class="bg-purple-400 rounded-lg px-3 py-2 text-sm font-medium text-white">Tambah Mahasiswa</a>

        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="my-4 flex justify-between gap-2">
            <div>
                <label for="rows" class="mr-2 text-sm">Show rows:</label>
                <select id="rows" name="rows" class="border rounded px-4 py-2 bg-white appearance-none pr-8"
                    onchange="window.location.href='{{ route('mahasiswa.index') }}?rows=' + this.value + '&search={{ request('search') }}'">
                    <option value="10" {{ request('rows', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('rows', 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('rows', 10) == 100 ? 'selected' : '' }}>100</option>
                    <option value="all" {{ request('rows') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            <form action="{{ route('mahasiswa.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" placeholder="Cari mahasiswa..." class="border rounded px-2 py-1 mr-2" value="{{ request('search') }}">
                <button type="submit" class="bg-purple-400 text-white px-3 py-1 rounded">Cari</button>
            </form>
        </div>

        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Nama</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">NIM</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Jenis Kelamin</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Prodi</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Jenjang</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Agama</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Angkatan</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswa as $mhs)
                <tr>
                    <td class="px-4 py-2 bg-gray-100">{{ $mhs->nama }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $mhs->nim }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $mhs->jenis_kelamin }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $mhs->prodi }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $mhs->jenjang }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $mhs->agama }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $mhs->angkatan }}</td>
                    <td class="px-4 py-2 bg-gray-100 flex flex-col gap-2">
                        <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="text-white px-3 py-1 text-sm rounded-lg bg-purple-400 hover:bg-purple-500">Edit</a>
                        <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white px-3 py-1 text-sm rounded-lg bg-red-500 hover:bg-red-600" onclick="return confirm('Apakah Anda yakin?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data tersedia</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($mahasiswa, 'links'))
        <div class="mt-4">
            {{ $mahasiswa->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection