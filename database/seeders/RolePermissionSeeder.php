<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $orgAdminRole = Role::firstOrCreate(['name' => 'organization-admin']);
        $supportAgentRole = Role::firstOrCreate(['name' => 'support-agent']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Define permissions
        $permissions = [
            'manage organizations',
            'manage users',
            'manage tickets',
            'manage comments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());
    }
}
