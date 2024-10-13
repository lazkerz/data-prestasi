<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prodi;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Membuat user admin dengan email yang valid
        $admin = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('prestasi01'),
            'email' => 'admin@example.com', // Ganti dengan email yang valid
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $admin->assignRole('admin');

        // Membuat user untuk setiap Prodi
        $prodis = Prodi::all();
        foreach ($prodis as $prodi) {
            $user = User::create([
                'name' => 'user_' . strtolower(str_replace(' ', '_', $prodi->nama_prodi)),
                'username' => strtolower(str_replace(' ', '_', $prodi->nama_prodi)),
                'password' => bcrypt('password123'),
                'email' => strtolower(str_replace(' ', '_', $prodi->nama_prodi)) . '@gmail.com',
                'email_verified_at' => now(),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Menetapkan role berdasarkan nama prodi
            $roleName = $prodi->nama_prodi;
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            // Assign role to the user
            $user->assignRole($role);
        }
    }
}
