<?php

return [
    'completed' => [
        'message' => 'Selamat, sekarang anda dapat menggunakan Lakasir untuk membantu penjualan anda',
        'link' => 'Masuk Sekarang!!!'
    ],
    'install' => [
        'database' => 'Daftarkan Database Anda',
        'next' => 'Lanjut',
        'submit' => 'Submit',
        'tab' => [
            'user' => 'Penguguna',
            'company' => 'Perusahaan',
            'database' => 'Database'
        ],
        'placeholder' => [
            'database' => [
                'name' => 'Masukkan Database Anda',
                'username' => 'Masukkan Username Anda',
                'password' => 'Masukkan Password Anda',
            ],
            'user' => [
                'email' => 'Masukkan Email Akun Anda',
                'username' => 'Masukkan Username Akun Anda',
                'password' => 'Masukkan Password Akun Anda',
                'password_confirmation' => 'Konfirmasi Password yang Telah Anda Masukkan'
            ],
            'company' => [
                'business_type' => 'Pilih Tipe Bisnis Perusahaan',
                'business_description' => 'Masukkan Deskripsi Bisnis Perusahaan'
            ]
        ],
    ],
    'profiles' => [
        'column' => [
            'bio' => 'Bio',
            'phone' => 'No. Hp',
            'address' => 'Alamat',
            'photo_profile' => 'Foto Profil',
            'lang' => 'Bahasa'
        ],
        'placeholder' => [
            'bio' => 'Bio',
            'phone' => 'No. Hp',
            'address' => 'Alamat',
            'photo_profile' => 'Foto Profil',
            'lang' => 'Bahasa'
        ],
        'about_me' => 'Tentang',
        'settings' => 'Pengaturan',
        'timeline' => 'Beranda',
        'activity' => 'Kegiatan'
    ],
    'auth' => [
        'placeholder' => [
            'email' => 'Masukkan Email Akun Anda',
            'username' => 'Masukkan Username Akun Anda',
        ],
        'label' => [
            'remember' => 'Ingat Aku'
        ],
    ],
    'items' => [
        'title' => 'Data Item',
        'placeholder' => [
            'name' => 'Nama',
            'images' => 'Gambar',
            'internal_production' => 'Produksi Sendiri',
            'category' => [
                'name' => 'Nama Kategori',
            ],
            'unit' => [
                'name' => 'Nama Satuan'
            ],
            'price' => [
                'selling_price' => 'Harga Jual',
                'initial_price' => 'Harga Beli'
            ],
            'stock' => [
                'amount' => 'Jumlah',
                'stock' => 'Stok'
            ]
        ],
        'column' => [
            'name' => 'Nama',
            'images' => 'Gambar',
            'internal_production' => 'Produksi Sendiri',
            'category' => [
                'name' => 'Nama Kategori',
            ],
            'unit' => [
                'name' => 'Nama Satuan'
            ],
            'price' => [
                'selling_price' => 'Harga Jual',
                'initial_price' => 'Harga Beli'
            ],
            'stock' => [
                'amount' => 'Jumlah',
                'stock' => 'Stok'
            ]
        ],
        'edit' => [
            'title' => 'Ubah Item'
        ],
        'create' => [
            'title' => 'Tambah Items'
        ],
    ],
    'purchasings' => [
        'title' => 'Pembelian',
        'column' => [
            'supplier' => 'Pemasok',
            'date' => 'Tanggal',
            'payment_method' => 'Metode Pembayaran',
            'invoice_number' => 'Nomor Pembayaran',
            'total_initial_price' => 'Total Harga Beli',
            'total_selling_price' => 'Total Harga Jual',
            'total_qty' => 'Total Jumlah',
            'note' => 'Catatan',
            'paid' => 'Lunas',
            'items' => [
                'header' => 'Item',
                'name' => 'Nama Item',
                'qty' => 'Kuantitas',
                'price' => 'Harga',
                'total' => 'Total'
            ]
        ],
        'placeholder' => [
            'supplier' => 'Pemasok',
            'date' => 'Tanggal',
            'payment_method' => 'Metode Pembayaran',
            'invoice_number' => 'Nomor Pembayaran',
            'total_initial_price' => 'Total Harga Beli',
            'total_selling_price' => 'Total Harga Jual',
            'total_qty' => 'Total Jumlah',
            'note' => 'Catatan',
            'paid' => 'Lunas'
        ],
        'info' => [
            'invoice_number' => 'Biarkan kosong untuk menggunakan generate nomor pembayaran otomatis',
            'date' => 'Biarkan kosong untuk menggunakan tanggal hari ini'
        ],
        'edit' => [
            'title' => 'Ubah Pembelian'
        ],
        'create' => [
            'title' => 'Tambah Pembelian'
        ],
    ],
    'customers' => [
        'title' => 'Pelanggan',
        'column' => [
            'name' => 'Nama',
            'email' => 'Email',
            'code' => 'Kode',
            'total_point' => 'Total Point'
        ],
        'placeholder' => [
            'name' => 'Nama',
            'email' => 'Email',
            'code' => 'Kode',
            'total_point' => 'Total Point'
        ],
        'info' => [
            'code' => 'Biarkan kosong untuk menggunakan generate kode otomatis'
        ],
        'edit' => [
            'title' => 'Ubah Pelanggan'
        ],
        'create' => [
            'title' => 'Tambah Pelanggan'
        ],
    ],
    'groups' => [
        'title' => 'Kelompok',
        'column' => [
            'name' => 'Nama',
            'total_user' => 'Total Penguguna',
            'customer' => 'Pelanggan',
            'total_customer' => 'Total Pelanggan'
        ],
        'placeholder' => [
            'name' => 'Nama',
            'customer' => 'Pilih Pelanggan'
        ],
        'edit' => [
            'title' => 'Ubah Kelompok'
        ],
        'create' => [
            'title' => 'Tambah Kelompok'
        ],
    ],
    'units' => [
        'title' => 'Satuan',
        'column' => [
            'name' => 'Nama',
        ],
        'placeholder' => [
            'name' => 'Nama',
        ],
        'edit' => [
            'title' => 'Ubah Satuan'
        ],
        'create' => [
            'title' => 'Tambah Satuan'
        ],
    ],
    'categories' => [
        'title' => 'Kategori',
        'column' => [
            'name' => 'Nama',
        ],
        'placeholder' => [
            'name' => 'Nama',
        ],
        'edit' => [
            'title' => 'Ubah Kategori'
        ],
        'create' => [
            'title' => 'Tambah Kategori'
        ],
    ],
    'suppliers' => [
        'title' => 'Pemasok',
        'column' => [
            'name' => 'Nama',
            'shop_name' => 'Nama Toko',
            'code' => 'Kode',
            'phone' => 'No. HP',
            'address' => 'Alamat'
        ],
        'placeholder' => [
            'name' => 'Nama',
            'shop_name' => 'Nama Toko',
            'code' => 'Kode',
            'phone' => 'No. HP',
            'address' => 'Alamat'
        ],
        'edit' => [
            'title' => 'Ubah Pemasok'
        ],
        'create' => [
            'title' => 'Tambah Pemasok'
        ],
    ],
    'user' => [
        'title' => 'Pengguna',
        'column' => [
            'username' => 'Username',
            'email' => 'Email',
            'role' => 'Jabatan',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password'
        ],
        'placeholder' => [
            'username' => 'Masukkan Username Anda',
            'email' => 'Masukkan Email Anda',
            'role' => 'Masukkan Jabatan',
            'password' => 'Masukkan Password',
            'password_confirmation' => 'Masukkan Konfirmasi Password'
        ],
        'change_password' => [
            'update' => 'Ganti Password',
            'column' => [
                'old_password' => 'Password Lama',
                'new_password' => 'Password Baru',
                'new_password_confirmation' => 'Konfirmasi Password Baru',
            ],
            'placeholder' => [
                'old_password' => 'Masukkan Password Lama',
                'new_password' => 'Masukkan Password Baru',
                'new_password_confirmation' => 'Masukkan Konfirmasi Password Baru',
            ]
        ],
        'create' => [
            'title' => 'Tambah Pengguna',
        ],
        'edit' => [
            'title' => 'Ubah Pengguna',
        ]
    ],
    'role' => [
        'title' => 'Jabatan',
        'column' => [
            'name' => 'Nama Jabatan',
            'guard_name' => 'Nama Guard',
            'permission_name' => 'Nama Permisi',
            'permission' => 'Permisi'
        ],
        'placeholder' => [
            'name' => 'Masukkan Nama Jabatan',
            'guard_name' => 'Masukkan Nama Guard',
            'permission_name' => 'Masukkan Nama Permisi',
            'permission' => 'Masukkan Permisi'
        ],
        'create' => [
            'title' => 'Tambah Jabatan',
        ],
        'edit' => [
            'title' => 'Ubah Jabatan',
        ]
    ],
    'global' => [
        'submit' => 'Simpan',
        'action' => 'Tindakan',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
        'create' => 'Tambah',
        'created_at' => 'Dibuat Pada',
        'suredelete' => 'Apa Anda Yakin?',
        'error_old_password' => 'Password Kamu tidak valid',
        'cancel' => 'Batal',
        'total' => 'Total',
        'message' => [
            'create' => 'Menambahkan',
            'update' => 'Memeperbarui',
            'delete' => 'Menghapus',
            'success' => 'Sukses',
        ]
    ]
];
