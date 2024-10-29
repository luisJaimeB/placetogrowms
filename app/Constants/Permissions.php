<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum Permissions: string implements Arrayable
{
    use EnumArrayable;

    case USERS_INDEX = 'users.index';
    case USERS_CREATE = 'users.create';
    case USERS_UPDATE = 'users.update';
    case USERS_DELETE = 'users.delete';
    case ROLES_INDEX = 'roles.index';
    case ROLES_CREATE = 'roles.create';
    case ROLES_UPDATE = 'roles.update';
    case ROLES_DELETE = 'roles.delete';
    case PERMISSIONS_INDEX = 'permissions.index';
    case PERMISSIONS_CREATE = 'permissions.create';
    case PERMISSIONS_UPDATE = 'permissions.update';
    case PERMISSIONS_DELETE = 'permissions.delete';
    case MICROSITES_INDEX = 'microsites.index';
    case MICROSITES_CREATE = 'microsites.create';
    case MICROSITES_UPDATE = 'microsites.update';
    case MICROSITES_DELETE = 'microsites.delete';
    case MICROSITES_SHOW = 'microsites.show';
    case PLANES_INDEX = 'planes.index';
    case PLANES_CREATE = 'planes.create';
    case PLANES_UPDATE = 'planes.update';
    case PLANES_DELETE = 'planes.delete';
    case SUBSCRIPTIONS_INDEX = 'subscriptions.index';
    case SUBSCRIPTIONS_SHOW = 'subscriptions.show';
    case SUBSCRIPTIONS_DELETE = 'subscriptions.delete';
    case INVOICES_INDEX = 'invoices.index';
    case INVOICES_CREATE = 'invoices.create';
    case INVOICES_SHOW = 'invoices.show';
    case INVOICES_UPDATE = 'invoices.update';
    case INVOICES_DELETE = 'invoices.delete';
    case IMPORTS_INDEX = 'imports.index';
    case IMPORTS_CREATE = 'imports.create';
    case IMPORTS_IMPORT = 'imports.import';
    case ACLS_INDEX = 'acls.index';
    case ACLS_CREATE = 'acls.create';
    case ACLS_UPDATE = 'acls.update';
    case ACLS_DELETE = 'acls.delete';
}
