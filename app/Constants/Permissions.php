<?php

namespace App\Constants;

use ReflectionClass;

final class Permissions
{
    public const string USERS_INDEX = 'users.index';

    public const string USERS_CREATE = 'users.create';

    public const string USERS_UPDATE = 'users.update';

    public const string USERS_DELETE = 'users.delete';

    public const string ROLES_INDEX = 'roles.index';

    public const string ROLES_CREATE = 'roles.create';

    public const string ROLES_UPDATE = 'roles.update';

    public const string ROLES_DELETE = 'roles.delete';

    public const string PERMISSIONS_INDEX = 'permissions.index';

    public const string PERMISSIONS_CREATE = 'permissions.create';

    public const string PERMISSIONS_UPDATE = 'permissions.update';

    public const string PERMISSIONS_DELETE = 'permissions.delete';

    public const string MICROSITES_INDEX = 'microsites.index';

    public const string MICROSITES_CREATE = 'microsites.create';

    public const string MICROSITES_UPDATE = 'microsites.update';

    public const string MICROSITES_DELETE = 'microsites.delete';

    public const string MICROSITES_SHOW = 'microsites.show';

    public const string PLANES_INDEX = 'planes.index';

    public const string PLANES_CREATE = 'planes.create';

    public const string PLANES_UPDATE = 'planes.update';

    public const string PLANES_DELETE = 'planes.delete';

    public const string SUBSCRIPTIONS_INDEX = 'subscriptions.index';

    public const string SUBSCRIPTIONS_DELETE = 'subscriptions.delete';

    public const string INVOICES_INDEX = 'invoices.index';

    public const string INVOICES_CREATE = 'invoices.create';

    public const string INVOICES_SHOW = 'invoices.show';

    public const string INVOICES_UPDATE = 'invoices.update';

    public const string INVOICES_DELETE = 'invoices.delete';

    public const string IMPORTS_INDEX = 'imports.index';

    public const string IMPORTS_CREATE = 'imports.create';

    public const string IMPORTS_IMPORT = 'imports.import';

    public const string ACLS_INDEX = 'acls.index';

    public const string ACLS_CREATE = 'acls.create';

    public const string ACLS_UPDATE = 'acls.update';

    public const string ACLS_DELETE = 'acls.delete';

    public static function toArray(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
