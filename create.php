<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    require_once 'config/database.php';

    // Inisialisasi variabel agar form tidak kosong kalau ada error
    $errors = [];
    $kode = '';
    $nama = '';
    $deskripsi = '';
    $status = 'Aktif';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil dan bersihkan input dari form
        $kode      = trim(htmlspecialchars($_POST['kode_kategori'] ?? ''));
        $nama      = trim(htmlspecialchars($_POST['nama_kategori'] ?? ''));
        $deskripsi = trim(htmlspecialchars($_POST['deskripsi'] ?? ''));
        $status    = trim($_POST['status'] ?? 'Aktif');

        // --- VALIDASI KODE KATEGORI ---
        if (empty($kode)) {
            $errors[] = "Kode kategori wajib diisi.";
        } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
            $errors[] = "Kode kategori harus antara 4 sampai 10 karakter.";
        } elseif (substr($kode, 0, 4) !== 'KAT-') {
            $errors[] = "Kode kategori harus diawali dengan 'KAT-' (contoh: KAT-001).";
        } else {
            // Cek duplikasi kode di database
            $cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ?");
            $cek->bind_param("s", $kode);
            $cek->execute();
            $cek->store_result();
            if ($cek->num_rows > 0) {
                $errors[] = "Kode kategori sudah digunakan, silakan pilih kode lain.";
            }
            $cek->close();
        }

        // --- VALIDASI NAMA KATEGORI ---
        if (empty($nama)) {
            $errors[] = "Nama kategori wajib diisi.";
        } elseif (strlen($nama) < 3) {
            $errors[] = "Nama kategori minimal 3 karakter.";
        } elseif (strlen($nama) > 50) {
            $errors[] = "Nama kategori maksimal 50 karakter.";
        }

        // --- VALIDASI DESKRIPSI (opsional) ---
        if (!empty($deskripsi) && strlen($deskripsi) > 200) {
            $errors[] = "Deskripsi maksimal 200 karakter.";
        }

        // --- VALIDASI STATUS ---
        if (!in_array($status, ['Aktif', 'Nonaktif'])) {
            $errors[] = "Status tidak valid.";
        }

        // Kalau tidak ada error, simpan ke database
        if (empty($errors)) {
            $insert = $conn->prepare("INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $kode, $nama, $deskripsi, $status);

            if ($insert->execute()) {
                // Redirect ke halaman utama dengan pesan sukses
                header("Location: index.php?pesan=Kategori+berhasil+ditambahkan&tipe=success");
                exit;
            } else {
                $errors[] = "Gagal menyimpan data. Silakan coba lagi.";
            }
            $insert->close();
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah Kategori Baru</h4>
                    </div>
                    <div class="card-body">

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= $err ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <!-- Kode Kategori -->
                            <div class="mb-3">
                                <label for="kode_kategori" class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_kategori" name="kode_kategori"
                                    value="<?= htmlspecialchars($kode) ?>" placeholder="Contoh: KAT-004" required>
                                <div class="form-text">Format: KAT-XXX, panjang 4–10 karakter</div>
                            </div>

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                    value="<?= htmlspecialchars($nama) ?>" placeholder="Masukkan nama kategori" required>
                                <div class="form-text">Minimal 3 karakter, maksimal 50 karakter</div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                    placeholder="Deskripsi kategori (opsional, maks 200 karakter)"><?= htmlspecialchars($deskripsi) ?></textarea>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="aktif" value="Aktif"
                                            <?= ($status == 'Aktif') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="aktif">Aktif</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="nonaktif" value="Nonaktif"
                                            <?= ($status == 'Nonaktif') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="nonaktif">Nonaktif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="index.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
