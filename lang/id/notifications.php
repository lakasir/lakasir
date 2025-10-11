<?php

declare(strict_types=1);

return [
    'stocks' => [
        'single-runs-out' => 'Stok :product mau habis',
        'single-out-of-stock' => 'Stok :product sudah habis',
        'multiple-runs-out' => 'Ada :count produk yang mau habis',
        'field_stock' => 'Stok tinggal :stock',
        'title' => 'Stokmu mau habis!',
    ],
    'qris' => [
        'payment_not_configured' => 'Pembayaran QRIS belum dikonfigurasi',
        'payment_not_configured_body' => 'Silakan hubungi administrator untuk mengkonfigurasi pengaturan pembayaran QRIS.',
        'cart_empty' => 'Keranjang kosong',
        'cart_empty_body' => 'Silakan tambahkan item ke keranjang sebelum melanjutkan pembayaran.',
        'failed_to_generate' => 'Gagal membuat kode QRIS',
        'failed_to_generate_body' => 'Tidak dapat terhubung ke layanan QRIS. Silakan coba lagi atau hubungi dukungan.',
        'invalid_response' => 'Respons QRIS tidak valid',
        'invalid_response_body' => 'Menerima data QRIS yang tidak valid. Silakan coba lagi.',
        'failed_to_create_session' => 'Gagal membuat sesi pembayaran',
        'failed_to_create_session_body' => 'Silakan coba lagi atau hubungi dukungan.',
        'payment_expired' => 'Pembayaran kedaluwarsa',
        'payment_expired_body' => 'Sesi pembayaran QRIS telah kedaluwarsa. Silakan coba lagi.',
        'status_check_failed' => 'Pemeriksaan status pembayaran gagal',
        'status_check_failed_body' => 'Tidak dapat memverifikasi status pembayaran. Silakan periksa secara manual.',
        'payment_successful' => 'Pembayaran berhasil!',
        'scan_qris_code' => 'Pindai Kode QRIS',
        'scan_with_wallet' => 'Pindai dengan aplikasi e-wallet Anda',
        'amount' => 'Jumlah:',
        'status' => 'Status:',
        'expires_in' => 'Kedaluwarsa dalam:',
        'checking_payment_status' => 'Memeriksa status pembayaran...',
        'payment_expired_message' => 'Pembayaran kedaluwarsa. Silakan coba lagi.',
        'waiting_for_scan' => 'Menunggu pemindaian...',
        'payment_completed' => 'Pembayaran selesai!',
        'checking' => 'Memeriksa...',
        'expired' => 'Kedaluwarsa',
    ],
];
