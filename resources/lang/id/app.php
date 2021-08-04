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
    'payment_methods' => [
        'title' => 'Metode Pembayaran',
        'column' => [
            'name' => 'Nama',
            'code' => 'Kode',
            'visible_in' => 'Terlihat Di',
            'can_delete' => 'Dapat Dihapus'
        ],
        'placeholder' => [
            'name' => 'Nama',
            'code' => 'Kode',
            'visible_in' => 'Terlihat Di',
            'can_delete' => 'Dapat Dihapus'
        ],
        'info' => [
            'visible_in' => [
                'empty' => 'Kosong'
            ]
        ],
        'create' => [
            'title' => 'Tambah Metode Pembayaran'
        ],
        'edit' => [
            'title' => 'Edit Metode Pembayaran'
        ]
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
        'activity' => 'Kegiatan',
        'company' => 'Perusahaan'
    ],
    'settings' => [
        'general' => [
            'company' => [
                'title' => 'Perusahaan',
                'description' => 'Ubah nama, email, alamat dan NPWP perusahaan.'
            ]
        ],
    ],
    'auth' => [
        'placeholder' => [
            'email' => 'Masukkan Email Akun Anda',
            'identity' => 'Masukkan email atau username anda',
            'username' => 'Masukkan Username Akun Anda',
            'password' => 'Masukkan passwords anda'
        ],
        'label' => [
            'remember' => 'Ingat Aku'
        ],
    ],
    'items' => [
        'title' => 'Data Item',
        'title_dashboard' => 'Produk',
        'placeholder' => [
            'name' => 'Nama',
            'images' => 'Gambar',
            'internal_production' => 'Produksi Sendiri',
            'category' => [
                'name' => 'Pilih satu dari kategori',
            ],
            'unit' => [
                'name' => 'Pilih satu dari satuan'
            ],
            'price' => [
                'selling_price' => 'Harga Jual',
                'initial_price' => 'Harga Beli'
            ],
            'stock' => [
                'amount' => 'Jumlah',
                'stock' => 'Stok',
                'last_stock' => 'Sisa Stok'
            ]
        ],
        'column' => [
            'name' => 'Nama',
            'images' => 'Gambar',
            'sales' => 'Terjual',
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
                'stock' => 'Stok',
                'empty' => 'Kosong',
                'last_stock' => 'Sisa Stok'
            ]
        ],
        'export' => [
            'name' => 'Nama',
            'images' => 'Gambar',
            'internal_production' => 'Produksi Sendiri (Ya / Tidak)',
            'category' => [
                'name' => 'Nama Kategori ( Kosongi untuk memilih umum )',
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
                'last_stock' => 'Sisa Stok ( Jika Kosong tidak perlu di isi )'
            ]
        ],
        'edit' => [
            'title' => 'Ubah Item'
        ],
        'create' => [
            'title' => 'Tambah Items'
        ],
    ],
    'sellings' => [
        'title' => [
            'cashier' => 'Kasir',
            'index' => 'Daftar Penjualan',
            'name' => 'Penjualan',
            'submit' => 'Submit Pesanan',
            'detail' => 'Detil Penjualan'
        ],
        'placeholder' => [
            'search_item' => 'Cari Item'
        ],
        'column' => [
            'payment_method' => 'Metode Pembayaran',
            'transaction_number' => 'Nomor Transaksi',
            'date' => 'Tanggal',
            'user' => 'Kasir',
            'customer' => 'Pelanggan',
            'money' => 'Uang',
            'total_price' => 'Total Harga',
            'total_qty' => 'Total Jumlah',
            'total_profit' => 'Total Profit',
            'refund' => 'Kembalian',
            'detail' => [
                'item_name' => 'Nama Item',
                'qty' => 'Qty',
                'price' => 'Harga',
                'profit' => 'Keuntungan'
            ]
        ],
        'menu' => [
            'activity' => 'Aktifitas',
            'sell' => 'Jual',
            'profile' => 'Profil'
        ],
        'validation' => [
            'less_price' => 'Uang kurang dari :money'
        ],
        'total_price' => 'Total Harga',
        'carts' => 'Keranjang',
        'submit_order' => 'Simpan Pesanan'
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
            'user' => 'User',
            'items' => [
                'header' => 'Item',
                'name' => 'Nama Item',
                'qty' => 'Kuantitas',
                'initial_price' => 'Harga Beli',
                'selling_price' => 'Harga Jual',
                'price' => 'Harga',
                'total' => 'Total'
            ],
            'validation' => [
                'item_doesnot_have_price' => 'the :item is didnot has price, you must assign the price'
            ]
        ],
        'placeholder' => [
            'supplier' => 'Pilih Satu Dari Pemasok',
            'date' => 'Tanggal',
            'payment_method' => 'Pilih Satu Dari Metode Pembayaran',
            'invoice_number' => 'Nomor Pembayaran',
            'total_initial_price' => 'Total Harga Beli',
            'total_selling_price' => 'Total Harga Jual',
            'total_qty' => 'Total Jumlah',
            'note' => 'Catatan',
            'paid' => 'Lunas',
            'user' => 'User',
            'items' => [
                'header' => 'Item',
                'name' => 'Pilih satu dari item',
                'qty' => 'Kuantitas',
                'initial_price' => 'Harga Beli',
                'selling_price' => 'Harga Jual',
                'price' => 'Harga',
                'total' => 'Total'
            ]
        ],
        'paid' => [
            'true' => 'Sudah Terbayarkan',
            'false' => 'Belum di Bayarkan'
        ],
        'note' => [
            'nothing_note' => 'Tidak Ada Catatan'
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
        'question_paid' => 'Yakin sudah di lunasi?'
    ],
    'customer_types' => [
        'title' => 'Tipe Pelanggan',
        'column' => [
            'customer_types_name' => 'Nama',
            'name' => 'Nama',
            'default_point' => 'Poin Bawaan'
        ],
        'placeholder' => [
            'customer_types_name' => 'Nama Tipe Pelanggan',
            'name' => 'Nama',
            'default_point' => 'Poin Bawaan'
        ],
        'edit' => [
            'title' => 'Ubah Tipe Pelanggan'
        ],
        'create' => [
            'title' => 'Tambah Tipe Pelanggan'
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
        'export' => [
            'name' => 'Nama Supplier ( wajib )',
            'code' => 'Kode ( Kosongi untuk kode ototmatis )',
            'shop_name' => 'Nama Toko',
            'phone' => 'No. Hp',
            'address' => 'Alamat'
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
        ],
        'message' => [
            'error' => [
                'delete_user' => [
                    'owner' => 'Kamu tidak dapat menghapus owner user',
                    'has_purchasing' => 'Kamu tidak dapat menghapus user yang mempunyai transaksi Pembelian'
                ]
            ]
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
        ],
        'message' => [
            'error' => [
                'delete_owner' => 'Kamu tidak dapat menghapus Owner'
            ]
        ]
    ],
    'companies' => [
        'title' => 'Perusahaan',
        'column' => [
            'name' => 'Nama',
            'description' => 'Deskripsi',
            'business_type' => 'Jenis Usaha',
            'address' => 'Alamat',
            'default_currency' => 'Mata Uang Bawaan',
            'expected_employee' => 'Eskpektasi Karyawan',
            'reg_number' => 'Nomor Registrasi'
        ],
        'placeholder' => [
            'name' => 'Masukkan Nama Perusahaan Anda',
            'description' => 'Masukkan Deskripsi Perusahaan Anda',
            'business_type' => 'Jenis Usaha',
            'address' => 'Input your Company Address',
            'default_currency' => 'Mata Uang Bawaan',
            'expected_employee' => 'Eskpektasi Karyawan',
            'reg_number' => 'Nomor Registrasi'
        ],
        'info' => [
            'reg_number' => 'Leave it Empty for use default generate registration Number'
        ]
    ],
    'global' => [
        'reload' => 'Muat Ulang',
        'submit' => 'Simpan',
        'action' => 'Tindakan',
        'bulk-action' => 'Tindakan massal',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
        'create' => 'Tambah :title',
        'created_at' => 'Dibuat Pada',
        'suredelete' => 'Apa Anda Yakin?',
        'error_old_password' => 'Password Kamu tidak valid',
        'cancel' => 'Batal',
        'total' => 'Total',
        'download' => 'Unduh :title',
        'import' => 'Import :title',
        'message' => [
            'create' => 'Menambahkan',
            'update' => 'Memeperbarui',
            'delete' => 'Menghapus',
            'success' => 'Sukses',
            'error' => 'Gagal',
        ],
        'yes' => 'Ya',
        'no' => 'Tidak',
        'login_cashier' => 'Login Sebagai Kasir',
        'payit' => 'Bayar',
        'more_info' => 'Info Selanjutnya',
        'checkAll' => 'Pilih Semua',
    ],
    'dashboard' => [
        'total_profit' => 'Total Keuntungan',
        'total_income' => 'Total Pendapatan',
        'total_spending' => 'Total Pengeluaran',
        'new_orders' => 'Pesanan Baru',
        'sales_overview' => 'Statistik Penjualan',
        'since_last_month' => 'Sejak Bulan Terakhir',
        'this_year' => 'Tahun ini',
        'last_year' => 'Tahun Terakhir'
    ]
];
