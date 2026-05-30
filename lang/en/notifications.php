<?php

declare(strict_types=1);

return [
    'stocks' => [
        'single-runs-out' => 'Stock for :product will runs out',
        'single-out-of-stock' => 'Stock for :product out of stock',
        'multiple-runs-out' => 'There is :count products that will runs of out of stock',
        'field_stock' => 'The rest stock is :stock',
        'title' => 'Your stock will runs out',
    ],
    'qris' => [
        'payment_not_configured' => 'QRIS payment is not configured',
        'payment_not_configured_body' => 'Please contact administrator to configure QRIS payment settings.',
        'cart_empty' => 'Cart is empty',
        'cart_empty_body' => 'Please add items to cart before proceeding with payment.',
        'failed_to_generate' => 'Failed to generate QRIS code',
        'failed_to_generate_body' => 'Unable to connect to QRIS service. Please try again or contact support.',
        'invalid_response' => 'Invalid QRIS response',
        'invalid_response_body' => 'Received invalid QRIS data. Please try again.',
        'failed_to_create_session' => 'Failed to create payment session',
        'failed_to_create_session_body' => 'Please try again or contact support.',
        'payment_expired' => 'Payment expired',
        'payment_expired_body' => 'The QRIS payment session has expired. Please try again.',
        'status_check_failed' => 'Payment status check failed',
        'status_check_failed_body' => 'Unable to verify payment status. Please check manually.',
        'payment_successful' => 'Payment successful!',
        'scan_qris_code' => 'Scan QRIS Code',
        'scan_with_wallet' => 'Scan with your e-wallet app',
        'amount' => 'Amount:',
        'status' => 'Status:',
        'expires_in' => 'Expires in:',
        'checking_payment_status' => 'Checking payment status...',
        'payment_expired_message' => 'Payment expired. Please try again.',
        'waiting_for_scan' => 'Waiting for scan...',
        'payment_completed' => 'Payment completed!',
        'checking' => 'Checking...',
        'expired' => 'Expired',
    ],
];
