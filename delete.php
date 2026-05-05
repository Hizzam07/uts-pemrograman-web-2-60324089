<?php
require_once 'config/database.php';

// Validasi ID dari parameter GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?pesan=ID+tidak+valid&tipe=danger");
    exit;
}

// Cek apakah data dengan ID tersebut benar-benar ada sebelum dihapus
$cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE id_kategori = ?");
$cek->bind_param("i", $id);
$cek->execute();
$cek->store_result();

if ($cek->num_rows == 0) {
    // Data tidak ditemukan
    header("Location: index.php?pesan=Data+tidak+ditemukan&tipe=danger");
    exit;
}
$cek->close();

// Jalankan DELETE menggunakan prepared statement
$hapus = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$hapus->bind_param("i", $id);
$hapus->execute();

// Cek apakah benar-benar terhapus lewat affected_rows
if ($hapus->affected_rows > 0) {
    header("Location: index.php?pesan=Kategori+berhasil+dihapus&tipe=success");
} else {
    header("Location: index.php?pesan=Gagal+menghapus+data&tipe=danger");
}

$hapus->close();
$conn->close();
exit;
?>
