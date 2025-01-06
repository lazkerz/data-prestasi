@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl p-6 bg-white rounded-lg shadow-lg mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Buat HMPS Baru</h2>

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('hmps.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Study Program -->
        @if(auth()->user()->hasRole('admin'))
        <div>
            <label for="study_program" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
            <select
                name="prodi_id"
                id="study_program"
                class="block w-full border border-gray-300 rounded-lg bg-white text-gray-800 px-4 py-2 focus:ring-purple-500 focus:border-purple-500">
                <option value="">Pilih Program Studi</option>
                @foreach($prodisWithoutHmps as $prodi)
                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div>
            <label for="study_program" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
            <input
                type="text"
                id="study_program"
                class="block w-full border border-gray-300 rounded-lg bg-gray-100 text-gray-800 px-4 py-2"
                value="{{ $prodi->nama_prodi }}"
                readonly>
            <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">
        </div>
        @endif

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea
                name="description"
                id="description"
                rows="4"
                class="block w-full border border-gray-300 rounded-lg bg-white text-gray-800 px-4 py-2 focus:ring-purple-500 focus:border-purple-500"
                placeholder="Tuliskan deskripsi singkat HMPS">{{ old('description') }}</textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 mt-2">
            <a
                href="{{ route('hmps.index') }}"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                Batal
            </a>
            <button
                type="submit"
                class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                Buat HMPS
            </button>
        </div>
    </form>
</div>
@endsection
