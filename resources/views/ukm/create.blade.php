@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl p-6 bg-gray-100 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New UKM</h2>

    @if(session('error'))
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif


    <form action="{{ route('ukm.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Nama UKM -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama UKM</label>
            <input type="text" id="nama" name="nama"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('nama') border-red-500 @enderror"
                value="{{ old('nama') }}" required>
            @error('nama')
            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror"
                value="{{ old('email') }}" required>
            @error('email')
            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" id="username" name="username"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('username') border-red-500 @enderror"
                value="{{ old('username') }}" required>
            @error('username')
            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" name="password"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                required>
            @error('password')
            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
            <textarea id="description" name="description"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror"
                rows="4">{{ old('description') }}</textarea>
            @error('description')
            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <a href="{{ route('ukm.index') }}" class=" px-4 py-2 text-sm text-gray-600 hover:text-gray-800 mr-4">Back</a>
            <button type="submit"
                class="px-4 py-2 bg-indigo-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-600">
                Create UKM
            </button>
        </div>
    </form>
</div>
@endsection
