<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersRolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        User::create([
            'name' => 'Orestis Rodriguez',
            'email' => 'orestis@orestisrodriguez.com',
            'password' => bcrypt('orestis'),
        ]);


        // Create roles
        Role::create([
            'name' => 'admin',
            'label' => 'Administrator',
        ]);

        Role::create([
            'name' => 'user',
            'label' => 'User',
        ]);

        // Create permissions
        Permission::create([
            'name' => 'all_permissions',
            'label' => 'All Permissions',
        ]);

        $admin = User::whereEmail('admin@admin.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        $user = User::whereEmail('orestis@orestisrodriguez.com')->first();
        if ($user) {
            $user->assignRole('user');
        }

        // Give permission to admin role
        $permission = Permission::whereName('all_permissions')->first();
        $role = Role::whereName('admin')->first();
        if ($role && $permission) {
            $role->givePermissionTo($permission);
        }
    }
}
