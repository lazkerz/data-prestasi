<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'tambah-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'hapus-user']);
        Permission::create(['name' => 'lihat-user']);

        // Permission::create(['name' => 'lihat-honor-detail-semua']);
        // Permission::create(['name' => 'lihat-honor-dasar-semua']);
        // Permission::create(['name' => 'print-honor']);

        // Permission::create(['name' => 'lihat-honor-detail-pribadi']);
        // Permission::create(['name' => 'lihat-honor-dasar-pribadi']);

        // Permission::create(['name' => 'tambah-rapat']);
        // Permission::create(['name' => 'edit-rapat']);
        // Permission::create(['name' => 'lihat-rapat']);

        // Permission::create(['name' => 'absen']);
        // Permission::create(['name' => 'ganti-password']);
    }
}
