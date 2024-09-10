<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(['lihat-user', 'tambah-user', 'edit-user', 'hapus-user']);
    }
}
