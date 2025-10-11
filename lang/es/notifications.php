<?php

declare(strict_types=1);

return [
    'stocks' => [
        'single-runs-out'     => 'El stock de :product se agotará',
        'single-out-of-stock' => 'El stock de :product está agotado',
        'multiple-runs-out'   => 'Hay :count productos que están a punto de agotarse',
        'field_stock'         => 'El stock restante es :stock',
        'title'               => 'Tu stock está a punto de agotarse',
    ],
    'qris' => [
        'payment_not_configured' => 'El pago QRIS no está configurado',
        'payment_not_configured_body' => 'Por favor, contacte al administrador para configurar los ajustes de pago QRIS.',
        'cart_empty' => 'El carrito está vacío',
        'cart_empty_body' => 'Por favor, agregue artículos al carrito antes de proceder con el pago.',
        'failed_to_generate' => 'Error al generar el código QRIS',
        'failed_to_generate_body' => 'No se puede conectar al servicio QRIS. Por favor, inténtelo de nuevo o contacte al soporte.',
        'invalid_response' => 'Respuesta QRIS inválida',
        'invalid_response_body' => 'Se recibió datos QRIS inválidos. Por favor, inténtelo de nuevo.',
        'failed_to_create_session' => 'Error al crear la sesión de pago',
        'failed_to_create_session_body' => 'Por favor, inténtelo de nuevo o contacte al soporte.',
        'payment_expired' => 'Pago expirado',
        'payment_expired_body' => 'La sesión de pago QRIS ha expirado. Por favor, inténtelo de nuevo.',
        'status_check_failed' => 'Error al verificar el estado del pago',
        'status_check_failed_body' => 'No se puede verificar el estado del pago. Por favor, verifique manualmente.',
        'payment_successful' => '¡Pago exitoso!',
        'scan_qris_code' => 'Escanear Código QRIS',
        'scan_with_wallet' => 'Escanea con tu aplicación de billetera electrónica',
        'amount' => 'Monto:',
        'status' => 'Estado:',
        'expires_in' => 'Expira en:',
        'checking_payment_status' => 'Verificando estado del pago...',
        'payment_expired_message' => 'Pago expirado. Por favor, inténtelo de nuevo.',
        'waiting_for_scan' => 'Esperando escaneo...',
        'payment_completed' => '¡Pago completado!',
        'checking' => 'Verificando...',
        'expired' => 'Expirado',
    ],
];
