@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <h2>Dashboard</h2>
        <p>Welcome, {{ $user->name }}!</p>
        <p>Your role: {{ $role }}</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
@endsection