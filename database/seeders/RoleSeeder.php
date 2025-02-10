<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $salesManager = Role::create(['name' => 'sales_manager']);
        $worker = Role::create(['name' => 'worker']);

        // Define permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage inventory']);
        Permission::create(['name' => 'manage expenses']);
        Permission::create(['name' => 'manage sales']);
        Permission::create(['name' => 'view reports']);

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo(['manage inventory', 'manage expenses', 'manage sales']);
        $salesManager->givePermissionTo(['manage sales']);
        $worker->givePermissionTo(['manage inventory']);

        // Assign a role to a sample user (Modify this based on your logic)
        $user = \App\Models\User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
