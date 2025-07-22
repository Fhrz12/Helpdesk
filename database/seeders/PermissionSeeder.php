<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // // 1. Reset & Hapus Semua Data Lama
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Permission::truncate();
        // Role::truncate();
        // DB::table('model_has_permissions')->truncate();
        // DB::table('model_has_roles')->truncate();
        // DB::table('role_has_permissions')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Buat Permissions Baru yang Terkelompok
        // TICKET
        Permission::create(['name' => 'view tickets', 'group_name' => 'Ticket']);
        Permission::create(['name' => 'assign tickets', 'group_name' => 'Ticket']);
        Permission::create(['name' => 'update tickets', 'group_name' => 'Ticket']);
        Permission::create(['name' => 'delete tickets', 'group_name' => 'Ticket']);

        // USER AND LOG MANAGEMENT
        Permission::create(['name' => 'manage users', 'group_name' => 'User Management']);
        Permission::create(['name' => 'manage roles', 'group_name' => 'User Management']);
        Permission::create(['name' => 'view user logs', 'group_name' => 'User Management']);

        // CONTENT
        Permission::create(['name' => 'manage news', 'group_name' => 'Content']);
        Permission::create(['name' => 'manage projects', 'group_name' => 'Content']);
        Permission::create(['name' => 'manage slas', 'group_name' => 'Content']);
        Permission::create(['name' => 'manage customers', 'group_name' => 'Content']);

        // 3. Buat Role Default
        $adminRole = Role::create(['name' => 'Admin']);
        $technicianRole = Role::create(['name' => 'Teknisi']);

        // 4. Berikan Permission ke Role
        $adminRole->givePermissionTo(Permission::all());
        $technicianRole->givePermissionTo(['view tickets', 'update tickets']);
    }
}
