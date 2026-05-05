# UTS Pemrograman Web 2

## Identitas
- **Nama:** [Fawwaz Hizzam Saputra]
- **NIM:** [60324089]
- **Mata Kuliah:** Pemrograman Website 2
- **Semester:** Genap 2025/2026

## Deskripsi Aplikasi
Aplikasi web sederhana untuk mengelola **Kategori Buku** di perpustakaan. Dibuat menggunakan PHP native dan MySQL, dilengkapi fitur CRUD lengkap (Create, Read, Update, Delete).

## Fitur
- Menampilkan daftar kategori buku dengan badge status berwarna
- Menambah kategori baru dengan validasi input lengkap
- Mengubah data kategori yang sudah ada
- Menghapus kategori dengan konfirmasi JavaScript
- Semua query menggunakan prepared statement untuk keamanan

## Cara Instalasi
1. Clone atau download repository ini
2. Taruh folder di dalam `htdocs` (XAMPP) atau `www` (WAMP)
3. Buka phpMyAdmin, jalankan file `database_backup.sql`
4. Sesuaikan konfigurasi di `config/database.php` jika diperlukan
5. Akses lewat browser: `http://localhost/uts_perpustakaan_NIM/`

## Struktur Folder
```
uts_perpustakaan_NIM/
├── config/
│   └── database.php      # Konfigurasi koneksi database
├── index.php             # Halaman utama - daftar kategori (READ)
├── create.php            # Form tambah kategori (CREATE)
├── edit.php              # Form edit kategori (UPDATE)
├── delete.php            # Proses hapus kategori (DELETE)
├── database_backup.sql   # File SQL struktur dan data
└── README.md
```

## Teknologi
- PHP Native
- MySQL / MariaDB
- Bootstrap 5.3
- MySQLi Prepared Statements

## link repository 
https://github.com/Hizzam07/uts-pemrograman-web-2-60324089.git
