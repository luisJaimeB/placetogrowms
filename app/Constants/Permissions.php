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

    public static function toArray(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
