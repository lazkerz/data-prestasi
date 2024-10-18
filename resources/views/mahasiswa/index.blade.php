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

        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-white uppercase bg-purple-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">NIM</th>
                        <th scope="col" class="px-6 py-3">Jenis Kelamin</th>
                        <th scope="col" class="px-6 py-3">Prodi</th>
                        <th scope="col" class="px-6 py-3">Jenjang</th>
                        <th scope="col" class="px-6 py-3">Agama</th>
                        <th scope="col" class="px-6 py-3">Angkatan</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswa as $mhs)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $mhs->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $mhs->nim }}</td>
                        <td class="px-6 py-4">{{ $mhs->jenis_kelamin }}</td>
                        <td class="px-6 py-4">{{ $mhs->prodi }}</td>
                        <td class="px-6 py-4">{{ $mhs->jenjang }}</td>
                        <td class="px-6 py-4">{{ $mhs->agama }}</td>
                        <td class="px-6 py-4">{{ $mhs->angkatan }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-row gap-2">
                                <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="font-medium text-white bg-purple-400 hover:bg-purple-500 px-3 py-1.5 rounded text-xs text-center">Edit</a>
                                <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded text-xs w-full" onclick="return confirm('Apakah Anda yakin?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b">
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data tersedia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($isPaginated)
        <div class="mt-4">
            {{ $mahasiswa->appends(request()->query())->links() }}
        </div>
        @else
        <div class="mt-4">
            Showing all {{ $mahasiswa->total() }} entries
        </div>
        @endif

    </div>
</div>
@endsection