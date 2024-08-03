<?php

return [
    'titles' => [
        'users' => 'Usuarios',
        'dashboard' => 'Tablero',
        'roles' => 'Roles',
        'permissions' => 'Permisos',
        'microsites' => 'Micrositios',
        'userediting' => 'Edición de usuarios',
        'paymentHistory' => 'Histórico de pagos',
    ],
    'strings' => [
        'updateUser' => 'Actualiza el usuario',
        'updateUserDesc' => 'Actualiza el usuario seleccionado',
        'createUser' => 'Crea un nuevo usuario',
        'createUserDesc' => 'Crea un nuevo usuario como invitado',
        'EmptyUsers' => 'No encontramos usuarios',
        'updateRol' => 'Actualiza el rol',
        'updateRolDesc' => 'Actualiza el rol seleccionado',
        'createRol' => 'Crea un nuevo rol',
        'createRolDesc' => 'Crea un nuevo rol y asigna los permisos requeridos',
        'updatePermission' => 'Actualiza el permiso',
        'updatePermissionDesc' => 'Actualiza el permiso seleccionado',
        'createPermission' => 'Crea un nuevo permiso',
        'createPermissionDesc' => 'Crea un nuevo permiso para luego asignarlo a un rol',
        'updateMicrosite' => 'Actualiza el micrositio',
        'updateMicrositeDesc' => 'Actualiza el micrositio seleccionado',
        'createMicrosite' => 'Crea un nuevo micrositio',
        'createMicrositeDesc' => 'Crea un nuevo Micrositio',
    ],
    'buttons' => [
        'createB' => 'Crear',
        'updateB' => 'Actualizar',
        'payB' => 'Pagar',
        'backToCommerce' => 'Volver al comercio',
    ],
    'labels' => [
        'micrositesLabel' => [
            'selectType' => 'Selecciona un tipo de sitio',
            'selectCategory' => 'Selecciona una categoría',
            'selectExpiration' => 'Selecciona el tiempo de expiración',
            'selectCurrency' => 'Selecciona una moneda',
            'logoMicrosite' => 'Logo del micrositio',
            'teenMicrositeExp' => '10 minutos',
            'fifteenMicrositeExp' => '15 minutos',
            'twentyMicrositeExp' => '20 minutos',
            'thirtyMicrositeExp' => '30 minutos',
        ],
        'paymentsLabel' => [
            'status' => 'Estado',
            'reference' => 'Referencia',
            'cus' => 'CUS',
            'amount' => 'Total',
            'currency' => 'Moneda',
            'date' => 'Fecha',
            'paymentMethod' => 'Método de pago',
        ],
    ],
    'actions' => [
        'users' => [
            'create' => 'Crear Usuario',
            'delete' => 'Eliminar Usuario',
            'edit' => 'Editar Usuario',
            'update' => 'Actualizar Usuario',
        ],
        'permissions' => [
            'create' => 'Crear Permiso',
            'delete' => 'Eliminar Permiso',
            'edit' => 'Editar Permiso',
            'update' => 'Actualizar Permiso',
        ],
        'roles' => [
            'create' => 'Crear Rol',
            'delete' => 'Eliminar Rol',
            'edit' => 'Editar Rol',
            'update' => 'Actualizar Rol',
        ],
        'microsites' => [
            'create' => 'Crear Micrositio',
            'delete' => 'Eliminar Micrositio',
            'edit' => 'Editar Micrositio',
            'update' => 'Actualizar Micrositio',
            'show' => 'Visualizar Micrositio',
        ],
    ],
    'fields' => [
        'id' => 'ID',
        'name' => 'Nombre',
        'type' => 'Tipo',
        'email' => 'Correo electrónico',
        'password' => 'Contraseña',
        'roles' => 'Roles',
        'category' => 'Categoría',
        'expiration' => 'Expiración',
        'description' => 'Descripción',
        'currency' => 'Moneda',
        'buyerName' => 'Nombres o razón social',
        'buyerLastName' => 'Apellidos',
        'phone' => 'Teléfono',
        'amount' => 'Valor total',
        'paymentMethod' => 'Método de pago',
        'users' => [
            'email' => 'Correo electrónico',
        ],
        'lang' => [
            'es' => 'Español',
            'en' => 'Inglés',
        ],
    ],
    'actionsLabel' => 'Acciones',
];
