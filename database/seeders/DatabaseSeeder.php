<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::deleteDirectory('public');

        // Create Roles and Permissions
        $this->seedRoles();

        // Create Admin User
        $this->command->warn(PHP_EOL . 'Creating Admin User...');
        $admin = $this->withProgressBar(1, fn () => User::factory(1)->create([
            'name' => 'Sntaks Admin',
            'email' => 'sntaksolutionsltd@gmail.com',
            'phone' => '254727796831',
            'status' => 'active',
            'is_admin' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('Asdf@1234'),
            'remember_token' => Str::random(10),
        ]))->first();
        $admin->assignRole('Admin');
        $this->command->info("Admin {$admin->name} created and assigned to 'Admin' role.");

        // Create Non-Admin Users
        $this->command->warn(PHP_EOL . 'Creating Non-Admin Users...');
        $users = $this->withProgressBar(10, fn () => User::factory(1)->create());
        $users->each(function (User $user) {
            $roles = ['Manager', 'Landlord', 'Tenant'];
            $user->assignRole($roles[array_rand($roles)]);
        });
        $this->command->info('Non-Admin users ' . $users->count() . ' created and assigned roles.');
    }

    private function seedRoles() {
        $this->command->warn(PHP_EOL . 'Creating Roles and Permissions...');
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

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define Roles with Permissions
        $roles = [
            'Admin' => $permissions,
            'Manager' => [
                'manage properties',
                'manage tenants',
                'manage leases',
                'manage payments',
                'manage maintenance',
                'generate reports',
            ],
            'Landlord' => [
                'view properties',
                'view tenants',
                'view leases',
                'view payments',
                'generate reports',
            ],
            'Tenant' => [
                'view properties',
                'view leases',
                'view payments',
            ],
            'Maintenance' => [
                'view maintenance',
                'manage maintenance',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }

        $this->command->info('Roles and permissions seeded successfully.');
    }

    protected function withProgressBar(int $amount, \Closure $createCollectionOfOne): Collection {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);
        $progressBar->start();

        $items = new Collection;

        foreach (range(1, $amount) as $index) {
            $items = $items->merge($createCollectionOfOne());
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->getOutput()->writeln('');

        return $items;
    }
}
