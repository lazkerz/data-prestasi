@extends('layouts.app')

@section('content')
<div class="bg-gray-50 p-6">
    <div class="h-fit md:ml-40 md:justify-center md:items-center mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-center mx-auto text-purple-400 mb-5">Edit User</h2>
        <div class="flex justify-center mx-auto w-[450px]">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="w-full gap-3 p-5 bg-white rounded rounded-lg shadow-lg">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Leave blank to keep current password"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Role Selection Field -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role:</label>
                    <select name="role" id="role"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-3">
                        @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-purple-400 text-white py-2 px-4 rounded-md hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection