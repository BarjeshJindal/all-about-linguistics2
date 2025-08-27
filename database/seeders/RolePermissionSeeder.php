<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Define your permissions
        $permissions = [
            'create-role',
            'show-role',
            'edit-role',
            'delete-role',
            'create-user',
        ];

        // 2. Create each permission for "admin" guard
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin',
            ]);
        }

        // 3. Ensure the admin role exists
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'admin']
        );

        // 4. Assign ALL permissions in DB to admin role
        $allPermissions = Permission::where('guard_name', 'admin')->pluck('id')->toArray();
        $adminRole->syncPermissions($allPermissions);

        // 5. (Optional) create instructor role
        $instructorRole = Role::firstOrCreate(
            ['name' => 'instructor', 'guard_name' => 'admin']
        );

        // (Optional) give only specific permissions to instructor
        // $instructorRole->syncPermissions(['show-role']);


           // 6. Assign "admin" role to the first Admin user
        $firstAdmin = Admin::first(); // get first user from admins table
        if ($firstAdmin && !$firstAdmin->hasRole('admin')) {
            $firstAdmin->assignRole($adminRole);
        }
    }
}
