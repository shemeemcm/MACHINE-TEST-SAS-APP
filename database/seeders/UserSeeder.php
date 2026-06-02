<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an organization
        $org = Organization::firstOrCreate(
            ['email' => 'info@demo-org.com'],
            [
                'name' => 'Demo Organization',
                'phone' => '1234567890',
                'address' => '123 Demo St',
            ]
        );

        // Create Super Admin (System-wide admin)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'organization_id' => $org->id, 
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Create Organization Admin
        $orgAdmin = User::firstOrCreate(
            ['email' => 'admin@demo-org.com'],
            [
                'name' => 'Organization Admin',
                'password' => Hash::make('password'),
                'organization_id' => $org->id,
            ]
        );
        $orgAdmin->assignRole('organization-admin');

        // Create Support Agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@demo-org.com'],
            [
                'name' => 'Support Agent',
                'password' => Hash::make('password'),
                'organization_id' => $org->id,
            ]
        );
        $agent->assignRole('support-agent');

        // Create Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@demo-org.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'organization_id' => $org->id,
            ]
        );
        $customer->assignRole('customer');
    }
}
