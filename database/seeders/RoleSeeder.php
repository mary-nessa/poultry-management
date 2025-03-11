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
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        //Define your permissions
        $permissions = [
            'manage role',
            'manage user',
            'manage branch',
            'manage transfer',
            'manage expense',
            'manage feed',
            'manage medicine',
            'manage equipment',
            'manage product',
            'manage sale',
            'manage breed',
            'manage egg-collection',
            'manage chick-purchase',
            'manage bird-immunization',
            'manage feeding-log',
            'manage health-check',
            'manage supplier',
            'manage feed-type',
            'manage bird',
            'manage buyer',
            'manage alert',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        /// Create the admin role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);


    }
}
