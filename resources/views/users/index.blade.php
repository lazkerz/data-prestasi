@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-purple-400 mb-2">User Management</h2>
        <a href="{{ route('users.create') }}" class="bg-purple-400 rounded rounded-lg px-3 py-2 text-sm font-medium text-white">Create New User</a>

        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="my-4 flex justify-between gap-2">
            <div>
                <label for="rows" class="mr-2 text-sm">Show rows:</label>
                <select id="rows" name="rows" class="border rounded px-4 py-2 bg-white appearance-none pr-8"
                    onchange="window.location.href='{{ route('users.index') }}?rows=' + this.value + '&search={{ request('search') }}'">
                    <option value="10" {{ request('rows', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ request('rows', 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('rows', 10) == 100 ? 'selected' : '' }}>100</option>
                    <option value="all" {{ request('rows') == 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            <form action="{{ route('users.index') }}" method="GET" class="flex items-center">
                <input type="hidden" name="rows" value="{{ request('rows', 10) }}">
                <input type="text" name="search" placeholder="Search users..." class="border rounded px-2 py-1 mr-2" value="{{ request('search') }}">
                <button type="submit" class="bg-purple-400 text-white px-3 py-1 rounded">Search</button>
            </form>
        </div>

        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-2 py-2 bg-purple-400 border border-white text-white text-left">No</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Name</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Email</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Role</th>
                    <th class="px-4 py-2 bg-purple-400 border border-white text-white text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="px-2 py-2 bg-gray-100 text-center">{{ $loop->index + 1 }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $user->name }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $user->email }}</td>
                    <td class="px-4 py-2 bg-gray-100">{{ $user->getRoleNames()->first() }}</td>
                    <td class="px-4 py-2 bg-gray-100">
                        <a href="{{ route('users.edit', $user) }}" class="text-white px-3 py-1 text-sm rounded rounded-lg bg-purple-400 hover:bg-purple-500">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white px-3 py-1 text-sm rounded rounded-lg bg-red-500 hover:bg-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(method_exists($users, 'links'))
        <div class="mt-4">
            {{ $users->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>
</div>
@endsection