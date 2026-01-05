<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos
        Permission::create(['name' => 'tasks.create']);
        Permission::create(['name' => 'tasks.update']);
        Permission::create(['name' => 'tasks.delete']);
        Permission::create(['name' => 'tasks.view']);
        Permission::create(['name' => 'tasks.assign']);

        // Roles
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $user = Role::create(['name' => 'user']);

        // Asignar permisos a roles
        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo(['tasks.view', 'tasks.update', 'tasks.assign']);
        $user->givePermissionTo(['tasks.view', 'tasks.create']);
    }
}
