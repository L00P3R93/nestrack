<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles/permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            'manage users',
            'manage roles',
            'manage properties',
            'view properties',
            'manage tenants',
            'view tenants',
            'manage leases',
            'view leases',
            'manage payments',
            'view payments',
            'manage maintenance',
            'view maintenance',
            'generate reports',
        ];

        // Define permissions
        $permissions = [
            // User
            'create users', 'edit users', 'delete users', 'assign roles', 'manage permissions',

            // Properties
            'create properties', 'edit properties', 'delete properties', 'view properties',

            // Tenants & Leases
            'create tenants', 'edit tenants', 'delete tenants', 'view tenants',
            'create leases', 'edit leases', 'terminate leases',

            // Financials
            'record payments', 'issue invoices', 'view financial reports', 'manage expenses',

            // Maintenance
            'create maintenance requests', 'approve maintenance requests',
            'update maintenance status', 'view maintenance',

            // Reports
            'view occupancy report', 'view income report', 'view expense report',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'create properties', 'edit properties', 'view properties',
            'create tenants', 'edit tenants', 'view tenants',
            'create leases', 'edit leases', 'terminate leases',
            'approve maintenance requests', 'update maintenance status', 'view maintenance',
            'view occupancy report', 'view income report',
        ]);

        $accountant = Role::firstOrCreate(['name' => 'Accountant']);
        $accountant->givePermissionTo([
            'record payments', 'issue invoices', 'manage expenses', 'view financial reports',
            'view income report', 'view expense report',
        ]);

        $agent = Role::firstOrCreate(['name' => 'Agent']);
        $agent->givePermissionTo([
            'create tenants', 'view tenants', 'view properties', 'create maintenance requests',
        ]);

        $tenant = Role::firstOrCreate(['name' => 'Tenant']);
        $tenant->givePermissionTo([
            'view tenants', 'view leases', 'view invoices', 'create maintenance requests',
        ]);
    }
}
