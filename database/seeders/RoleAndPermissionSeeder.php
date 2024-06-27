<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $basicRolesPermission = [
            [
                'name' => 'Admin',
                'permissions' => [
                    Permissions::USERS_INDEX,
                    Permissions::USERS_CREATE,
                    Permissions::USERS_UPDATE,
                    Permissions::USERS_DELETE,

                    Permissions::ROLES_INDEX,
                    Permissions::ROLES_CREATE,
                    Permissions::ROLES_UPDATE,
                    Permissions::ROLES_DELETE,
                ],
            ],
            [
                'name' => 'Customer',
                'permissions' => [

                ],
            ],
        ];

        foreach ($basicRolesPermission as $role) {
            $rol = Role::query()->updateOrCreate([
                'name' => $role['name'],
            ]);


            foreach ($role['permissions'] as $permission) {
                Permission::query()->create([
                    'name' => $permission,
                ]);
            }

            $rol->syncPermissions($role['permissions']);
        }
    }
}
