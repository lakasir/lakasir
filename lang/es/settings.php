<?php

return [
    'save' => 'Guardar Cambios',
    'saving' => 'Guardando...',
    'open_advanced' => 'Abrir Configuracion Avanzada',

    'messages' => [
        'saved' => 'La configuracion se guardo correctamente.',
        'save_failed' => 'No se pudo guardar la configuracion. Intentalo de nuevo.',
    ],

    'groups' => [
        'general' => 'General',
        'system' => 'Sistema',
    ],

    'nav' => [
        'general' => 'General',
        'users' => 'Usuarios',
        'roles' => 'Roles',
        'printer' => 'Impresora',
        'about' => 'Acerca de',
        'profile' => 'Perfil',
    ],

    'landing' => [
        'title' => 'Setting',
        'sections' => [
            'general' => 'General',
            'system' => 'Sistema',
        ],
        'items' => [
            'category' => 'Categoria',
            'currency' => 'Moneda',
            'language' => 'Idioma',
            'display' => 'Pantalla',
        ],
        'actions' => [
            'cancel' => 'Cancelar',
            'set' => 'Aplicar',
        ],
        'currencies' => [
            'idr' => 'IDR',
            'usd' => 'USD',
        ],
        'languages' => [
            'id' => 'Bahasa Indonesia',
            'en' => 'Ingles',
            'es' => 'Espanol',
        ],
        'displays' => [
            '58mm' => 'Papel 58mm',
            '80mm' => 'Papel 80mm',
        ],
    ],

    'category' => [
        'title' => 'Categoria',
        'search' => 'Buscar',
        'add_button' => 'Agregar Categoria',
        'add_title' => 'Agregar Categoria',
        'edit_title' => 'Editar Categoria',
        'input_placeholder' => 'Ingresa el nombre de la categoria',
        'save' => 'Guardar',
        'empty' => 'No se encontraron categorias.',
        'delete_confirm' => 'Eliminar esta categoria?',
        'messages' => [
            'deleted' => 'La categoria se elimino correctamente.',
            'delete_blocked' => 'La categoria no se puede eliminar porque esta usada por productos.',
        ],
    ],

    'general' => [
        'subtitle' => 'Configura informacion de tienda, recibos, impuestos y transacciones.',
        'sections' => [
            'store_information' => 'Informacion de la Tienda',
            'receipt_settings' => 'Configuracion del Recibo',
            'currency_settings' => 'Configuracion de Moneda',
            'tax_settings' => 'Configuracion de Impuestos',
            'transaction_settings' => 'Configuracion de Transacciones',
        ],
        'fields' => [
            'store_name' => 'Nombre de la Tienda',
            'store_address' => 'Direccion de la Tienda',
            'store_phone' => 'Telefono de la Tienda',
            'store_email' => 'Correo de la Tienda',
            'receipt_header' => 'Encabezado del Recibo',
            'receipt_footer' => 'Pie del Recibo',
            'show_logo_on_receipt' => 'Mostrar logo en el recibo',
            'paper_size' => 'Tamano del Papel',
            'currency_symbol' => 'Simbolo de Moneda',
            'currency_position' => 'Posicion de Moneda',
            'decimal_places' => 'Decimales',
            'enable_tax' => 'Habilitar impuesto',
            'tax_rate' => 'Tasa de Impuesto (%)',
            'tax_included' => 'Impuesto incluido en el precio',
            'default_payment_method' => 'Metodo de Pago Predeterminado',
            'require_customer_info' => 'Requerir informacion del cliente',
            'receipt_auto_print' => 'Imprimir recibo automaticamente',
        ],
        'options' => [
            'before' => 'Antes del monto',
            'after' => 'Despues del monto',
            'cash' => 'Efectivo',
            'transfer' => 'Transferencia Bancaria',
            'card' => 'Tarjeta',
        ],
    ],

    'users' => [
        'title' => 'Usuarios',
        'subtitle' => 'Revisa usuarios actuales y abre la gestion avanzada de usuarios.',
        'add_user' => 'Agregar Usuario',
        'manage_all' => 'Gestionar Todos los Usuarios',
        'empty_title' => 'No hay usuarios',
        'empty_description' => 'Crea tu primer usuario para delegar accesos.',
        'active' => 'Activo',
        'inactive' => 'Inactivo',
        'table' => [
            'name' => 'Nombre',
            'email' => 'Correo',
            'roles' => 'Roles',
            'status' => 'Estado',
        ],
    ],

    'roles' => [
        'title' => 'Roles y Permisos',
        'subtitle' => 'Revisa la configuracion de roles y permisos.',
        'manage_all' => 'Gestionar Roles',
        'empty_title' => 'No hay roles',
        'empty_description' => 'Crea un rol para definir grupos de permisos.',
        'matrix_title' => 'Matriz de Permisos',
        'matrix_subtitle' => 'Resumen de permisos comunes por modulo.',
        'preview_for' => 'Vista previa de permisos para :role',
        'permission_group' => 'Grupo de Permisos',
        'permissions' => 'Permisos',
        'table' => [
            'role' => 'Rol',
            'permissions_count' => 'Permisos',
        ],
        'groups' => [
            'transactions' => 'Transacciones',
            'products' => 'Productos',
            'members' => 'Miembros',
            'reports' => 'Reportes',
            'settings' => 'Configuracion',
            'users' => 'Usuarios',
        ],
    ],

    'printer' => [
        'title' => 'Configuracion de Impresora',
        'subtitle' => 'Configura la conexion de impresora y el papel del recibo.',
        'open_advanced' => 'Abrir Herramientas de Impresora',
        'fields' => [
            'name' => 'Nombre de Impresora',
            'driver' => 'Controlador',
            'port' => 'Puerto',
            'ip_address' => 'Direccion IP',
            'paper_size' => 'Tamano del Papel',
        ],
    ],

    'about' => [
        'title' => 'Acerca de',
        'subtitle' => 'Informacion basica de la aplicacion y la tienda.',
        'support_title' => 'Necesitas ayuda?',
        'support_description' => 'Visita el centro de soporte para guias y solucion de problemas.',
        'fields' => [
            'application_name' => 'Nombre de la Aplicacion',
            'version' => 'Version',
            'license' => 'Licencia',
            'store_name' => 'Nombre de la Tienda',
            'store_address' => 'Direccion de la Tienda',
        ],
    ],

    'profile' => [
        'title' => 'Perfil',
        'subtitle' => 'Actualiza tu cuenta, idioma, zona horaria y seguridad.',
        'sections' => [
            'account' => 'Cuenta',
            'contact' => 'Contacto',
            'localization' => 'Localizacion',
            'security' => 'Seguridad',
        ],
        'fields' => [
            'name' => 'Nombre',
            'email' => 'Correo',
            'phone' => 'Telefono',
            'address' => 'Direccion',
            'language' => 'Idioma',
            'timezone' => 'Zona Horaria',
            'new_password' => 'Nueva Contrasena',
            'confirm_password' => 'Confirmar Contrasena',
        ],
    ],
];
