CREATE DATABASE IF NOT EXISTS uts_perpustakaan_60324089
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE uts_perpustakaan_NIM;

-- Buat tabel kategori
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    kode_kategori VARCHAR(10) UNIQUE NOT NULL,
    nama_kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data minimal 3 baris
INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES
('KAT-001', 'Pemrograman', 'Buku-buku tentang bahasa pemrograman', 'Aktif'),
('KAT-002', 'Database', 'Buku-buku tentang sistem basis data', 'Aktif'),
('KAT-003', 'Jaringan', 'Buku-buku tentang jaringan komputer', 'Aktif');
