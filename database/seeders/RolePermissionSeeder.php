<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Prodi;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission umum untuk admin
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(['lihat-user', 'tambah-user', 'edit-user', 'hapus-user']);

        // Buat CRUD permission untuk setiap prodi
        $prodis = Prodi::all();
        foreach ($prodis as $prodi) {
            // Format permission untuk setiap Prodi
            $permissions = [
                "lihat-mahasiswa-{$prodi->nama_prodi}",
                "tambah-mahasiswa-{$prodi->nama_prodi}",
                "edit-mahasiswa-{$prodi->nama_prodi}",
                "hapus-mahasiswa-{$prodi->nama_prodi}",
            ];

            // Buat permission jika belum ada
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            // Cari role sesuai nama Prodi
            $roleName = $prodi->nama_prodi;
            $role = Role::findByName($roleName);

            // Beri permission CRUD mahasiswa ke role berdasarkan Prodi
            $role->givePermissionTo($permissions);
        }
    }
}
