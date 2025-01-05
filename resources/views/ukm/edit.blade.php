<!-- resources/views/ukm/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="bg-white p-6 md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
    <h2 class="text-xl font-bold text-purple-400 mb-4">Edit UKM</h2>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('ukm.update', $ukm->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama UKM</label>
            <input type="text" id="nama" name="nama"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                   value="{{ old('nama', $ukm->nama) }}" required>
            @error('nama')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea id="description" name="description"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                      rows="4">{{ old('description', $ukm->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="border-t pt-4 mt-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                       value="{{ old('username', $ukm->user->username) }}">
                @error('username')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                       value="{{ old('email', $ukm->user->email) }}">
                @error('email')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                @error('password')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('ukm.index') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</a>
            <button type="submit"
                    class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">Update</button>
        </div>
    </form>
</div>
@endsection
