<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Dapatkan user yang sedang login
        $user = Auth::user();

        // Ambil role user
        $role = $user->getRoleNames()->first();

        // Kirim data ke view
        return view('dashboard', compact('user', 'role'));
    }
}
