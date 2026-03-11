<?php

return [
    'save' => 'Simpan Perubahan',
    'saving' => 'Menyimpan...',
    'open_advanced' => 'Buka Pengaturan Lanjutan',

    'messages' => [
        'saved' => 'Pengaturan berhasil disimpan.',
        'save_failed' => 'Pengaturan gagal disimpan. Silakan coba lagi.',
    ],

    'groups' => [
        'general' => 'Umum',
        'system' => 'Sistem',
    ],

    'nav' => [
        'general' => 'Umum',
        'users' => 'Pengguna',
        'roles' => 'Peran',
        'printer' => 'Printer',
        'about' => 'Tentang',
        'profile' => 'Profil',
    ],

    'landing' => [
        'title' => 'Setting',
        'sections' => [
            'general' => 'General',
            'system' => 'System',
        ],
        'items' => [
            'category' => 'Category',
            'currency' => 'Currency',
            'language' => 'Language',
            'display' => 'Display',
        ],
        'actions' => [
            'cancel' => 'Cancel',
            'set' => 'Set',
        ],
        'currencies' => [
            'idr' => 'IDR',
            'usd' => 'USD',
        ],
        'languages' => [
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
            'es' => 'Spanish',
        ],
        'displays' => [
            '58mm' => 'Kertas 58mm',
            '80mm' => 'Kertas 80mm',
        ],
    ],

    'category' => [
        'title' => 'Category',
        'search' => 'Search',
        'add_button' => 'Add Category',
        'add_title' => 'Add Category',
        'edit_title' => 'Edit Category',
        'input_placeholder' => 'Enter category name',
        'save' => 'Save',
        'empty' => 'Kategori tidak ditemukan.',
        'delete_confirm' => 'Hapus kategori ini?',
        'messages' => [
            'deleted' => 'Kategori berhasil dihapus.',
            'delete_blocked' => 'Kategori tidak bisa dihapus karena masih digunakan produk.',
        ],
    ],

    'general' => [
        'subtitle' => 'Atur informasi toko, struk, pajak, dan pengaturan transaksi.',
        'sections' => [
            'store_information' => 'Informasi Toko',
            'receipt_settings' => 'Pengaturan Struk',
            'currency_settings' => 'Pengaturan Mata Uang',
            'tax_settings' => 'Pengaturan Pajak',
            'transaction_settings' => 'Pengaturan Transaksi',
        ],
        'fields' => [
            'store_name' => 'Nama Toko',
            'store_address' => 'Alamat Toko',
            'store_phone' => 'Telepon Toko',
            'store_email' => 'Email Toko',
            'receipt_header' => 'Header Struk',
            'receipt_footer' => 'Footer Struk',
            'show_logo_on_receipt' => 'Tampilkan logo di struk',
            'paper_size' => 'Ukuran Kertas',
            'currency_symbol' => 'Simbol Mata Uang',
            'currency_position' => 'Posisi Mata Uang',
            'decimal_places' => 'Jumlah Desimal',
            'enable_tax' => 'Aktifkan pajak',
            'tax_rate' => 'Tarif Pajak (%)',
            'tax_included' => 'Pajak termasuk harga',
            'default_payment_method' => 'Metode Pembayaran Default',
            'require_customer_info' => 'Wajibkan informasi pelanggan',
            'receipt_auto_print' => 'Cetak struk otomatis',
        ],
        'options' => [
            'before' => 'Sebelum nominal',
            'after' => 'Sesudah nominal',
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'card' => 'Kartu',
        ],
    ],

    'users' => [
        'title' => 'Pengguna',
        'subtitle' => 'Lihat daftar pengguna aktif dan lanjutkan ke manajemen pengguna lanjutan.',
        'add_user' => 'Tambah Pengguna',
        'manage_all' => 'Kelola Semua Pengguna',
        'empty_title' => 'Belum ada pengguna',
        'empty_description' => 'Buat pengguna pertama untuk mulai membagi akses.',
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'table' => [
            'name' => 'Nama',
            'email' => 'Email',
            'roles' => 'Peran',
            'status' => 'Status',
        ],
    ],

    'roles' => [
        'title' => 'Peran & Hak Akses',
        'subtitle' => 'Tinjau konfigurasi peran dan cakupan hak akses.',
        'manage_all' => 'Kelola Peran',
        'empty_title' => 'Belum ada peran',
        'empty_description' => 'Buat peran untuk mengelompokkan hak akses.',
        'matrix_title' => 'Matriks Hak Akses',
        'matrix_subtitle' => 'Ringkasan hak akses umum per modul.',
        'preview_for' => 'Menampilkan hak akses untuk :role',
        'permission_group' => 'Grup Hak Akses',
        'permissions' => 'Hak Akses',
        'table' => [
            'role' => 'Peran',
            'permissions_count' => 'Jumlah Hak Akses',
        ],
        'groups' => [
            'transactions' => 'Transaksi',
            'products' => 'Produk',
            'members' => 'Member',
            'reports' => 'Laporan',
            'settings' => 'Pengaturan',
            'users' => 'Pengguna',
        ],
    ],

    'printer' => [
        'title' => 'Pengaturan Printer',
        'subtitle' => 'Atur koneksi printer struk dan keluaran kertas.',
        'open_advanced' => 'Buka Alat Printer',
        'fields' => [
            'name' => 'Nama Printer',
            'driver' => 'Driver',
            'port' => 'Port',
            'ip_address' => 'Alamat IP',
            'paper_size' => 'Ukuran Kertas',
        ],
    ],

    'about' => [
        'title' => 'Tentang',
        'subtitle' => 'Informasi dasar aplikasi dan toko untuk tenant ini.',
        'support_title' => 'Butuh bantuan?',
        'support_description' => 'Kunjungi pusat bantuan untuk panduan setup dan troubleshooting.',
        'fields' => [
            'application_name' => 'Nama Aplikasi',
            'version' => 'Versi',
            'license' => 'Lisensi',
            'store_name' => 'Nama Toko',
            'store_address' => 'Alamat Toko',
        ],
    ],

    'profile' => [
        'title' => 'Profil',
        'subtitle' => 'Perbarui akun, bahasa, zona waktu, dan keamanan Anda.',
        'sections' => [
            'account' => 'Akun',
            'contact' => 'Kontak',
            'localization' => 'Lokalisasi',
            'security' => 'Keamanan',
        ],
        'fields' => [
            'name' => 'Nama',
            'email' => 'Email',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'language' => 'Bahasa',
            'timezone' => 'Zona Waktu',
            'new_password' => 'Kata Sandi Baru',
            'confirm_password' => 'Konfirmasi Kata Sandi',
        ],
    ],
];
