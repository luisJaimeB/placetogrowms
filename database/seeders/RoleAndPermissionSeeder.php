<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Constants\Roles;
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
                'name' => Roles::ADMIN,
                'permissions' => [
                    Permissions::USERS_INDEX,
                    Permissions::USERS_CREATE,
                    Permissions::USERS_UPDATE,
                    Permissions::USERS_DELETE,

                    Permissions::ROLES_INDEX,
                    Permissions::ROLES_CREATE,
                    Permissions::ROLES_UPDATE,
                    Permissions::ROLES_DELETE,

                    Permissions::PERMISSIONS_INDEX,
                    Permissions::PERMISSIONS_CREATE,
                    Permissions::PERMISSIONS_UPDATE,
                    Permissions::PERMISSIONS_DELETE,

                    Permissions::MICROSITES_INDEX,
                    Permissions::MICROSITES_CREATE,
                    Permissions::MICROSITES_UPDATE,
                    Permissions::MICROSITES_DELETE,
                    Permissions::MICROSITES_SHOW,

                    Permissions::PLANES_INDEX,
                    Permissions::PLANES_CREATE,
                    Permissions::PLANES_UPDATE,
                    Permissions::PLANES_DELETE,

                    Permissions::SUBSCRIPTIONS_INDEX,
                    Permissions::SUBSCRIPTIONS_DELETE,

                    Permissions::INVOICES_INDEX,
                    Permissions::INVOICES_CREATE,
                    Permissions::INVOICES_SHOW,
                    Permissions::INVOICES_UPDATE,
                    Permissions::INVOICES_DELETE,

                    Permissions::IMPORTS_INDEX,
                    Permissions::IMPORTS_CREATE,
                    Permissions::IMPORTS_IMPORT,

                    Permissions::ACLS_INDEX,
                    Permissions::ACLS_CREATE,
                    Permissions::ACLS_UPDATE,
                    Permissions::ACLS_DELETE,
                ],
            ],
            [
                'name' => Roles::CUSTOMER,
                'permissions' => [
                    Permissions::MICROSITES_INDEX,
                    Permissions::MICROSITES_CREATE,
                    Permissions::MICROSITES_UPDATE,
                    Permissions::MICROSITES_DELETE,
                    Permissions::MICROSITES_SHOW,

                    Permissions::PLANES_INDEX,
                    Permissions::PLANES_CREATE,
                    Permissions::PLANES_UPDATE,
                    Permissions::PLANES_DELETE,

                    Permissions::INVOICES_INDEX,
                    Permissions::INVOICES_CREATE,
                    Permissions::INVOICES_SHOW,
                    Permissions::INVOICES_UPDATE,
                    Permissions::INVOICES_DELETE,
                ],
            ],
            [
                'name' => Roles::SUBSCRIBER,
                'permissions' => [
                    Permissions::SUBSCRIPTIONS_INDEX,
                    Permissions::SUBSCRIPTIONS_DELETE,
                ],
            ],
        ];

        foreach ($basicRolesPermission as $role) {
            $rol = Role::query()->updateOrCreate([
                'name' => $role['name'],
            ]);

            foreach ($role['permissions'] as $permission) {
                Permission::query()->updateOrCreate([
                    'name' => $permission,
                ]);
            }

            $rol->syncPermissions($role['permissions']);
        }
    }
}
