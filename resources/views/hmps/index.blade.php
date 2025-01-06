@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-purple-400 mb-4">Daftar HMPS</h2>

        <!-- Button to Add HMPS -->
        <!-- Button to Add HMPS -->
        @php
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $isHmps = $user->hasRole('hmps');

        // Get user's study program role if they're not admin or HMPS
        $studyProgramRole = null;
        if (!$isAdmin && !$isHmps) {
        $studyProgramRole = $user->roles
        ->whereNotIn('name', ['admin', 'ukm', 'hmps'])
        ->first();
        }

        // Check if study program already has HMPS
        $hasHmps = false;
        if ($studyProgramRole) {
        $prodi = App\Models\Prodi::where('nama_prodi', $studyProgramRole->name)->first();
        $hasHmps = $prodi ? $prodi->hmps()->exists() : false;
        }
        @endphp

        @if($isAdmin || (!$isHmps && $studyProgramRole && !$hasHmps))
        <a href="{{ route('hmps.create') }}"
            class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 mb-4">
            Tambah HMPS
        </a>
        @endif

        <!-- Search and Rows Selection -->
        <div class="my-4 flex justify-between gap-2">
            <div>
                <label for="rows" class="mr-2 text-sm">Show rows:</label>
                <select id="rows" name="rows" class="border rounded px-4 py-2 bg-white appearance-none pr-8"
                    onchange="window.location.href='{{ route('hmps.index') }}?rows=' + this.value + '&search={{ request('search') }}'">
                    <option value="10" {{ request('rows', 10)==10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('rows', 10)==50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('rows', 10)==100 ? 'selected' : '' }}>100</option>
                    <option value="all" {{ request('rows')=='all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            <!-- Search Box -->
            <form action="{{ route('hmps.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" placeholder="Cari HMPS..." class="border rounded px-2 py-1 mr-2"
                    value="{{ request('search') }}">
                <button type="submit" class="bg-purple-400 text-white px-3 py-1 rounded">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-white uppercase bg-purple-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Program Studi</th>
                        <th scope="col" class="px-6 py-3">Nama HMPS</th>
                        <th scope="col" class="px-6 py-3">Username Akun</th>
                        <th scope="col" class="px-6 py-3">Jumlah Anggota</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hmps as $himpunan)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $himpunan->prodi->nama_prodi }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $himpunan->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $himpunan->user->username }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $himpunan->members->count() }} Anggota
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <!-- Show Manage Members button for both admin and HMPS owner -->
                            <a href="{{ route('hmps.members', $himpunan->id) }}"
                                class="font-medium bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600">
                                Kelola Anggota
                            </a>

                            <!-- Show Edit and Delete buttons only for roles other than HMPS -->
                            @if(!auth()->user()->hasRole('hmps'))
                            <a href="{{ route('hmps.edit', $himpunan->id) }}"
                                class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600">
                                Edit
                            </a>

                            <form action="{{ route('hmps.destroy', $himpunan->id) }}" method="POST"
                                onsubmit="return confirm('Anda yakin ingin menghapus HMPS ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada HMPS yang tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($isPaginated)
        <div class="mt-4">
            {{ $hmps->appends(request()->query())->links() }}
        </div>
        @else
        <div class="mt-4">
            Showing all {{ $hmps->count() }} entries
        </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
        <div class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <path
                        d="M14.348 5.652a1 1 0 00-1.414-1.414L10 7.586 7.066 4.652a1 1 0 00-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z" />
                </svg>
            </span>
        </div>
        @endif
    </div>
</div>
@endsection