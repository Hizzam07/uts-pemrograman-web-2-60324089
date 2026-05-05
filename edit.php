<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    require_once 'config/database.php';

    // Ambil dan validasi ID dari parameter GET
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        header("Location: index.php?pesan=ID+tidak+valid&tipe=danger");
        exit;
    }

    // Cek apakah data dengan ID tersebut ada di database
    $cek_ada = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
    $cek_ada->bind_param("i", $id);
    $cek_ada->execute();
    $data_lama = $cek_ada->get_result()->fetch_assoc();

    if (!$data_lama) {
        header("Location: index.php?pesan=Data+tidak+ditemukan&tipe=danger");
        exit;
    }

    // Pre-fill nilai form dengan data yang sudah ada
    $errors  = [];
    $kode    = $data_lama['kode_kategori'];
    $nama    = $data_lama['nama_kategori'];
    $deskripsi = $data_lama['deskripsi'];
    $status  = $data_lama['status'];

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
            $errors[] = "Kode kategori harus diawali dengan 'KAT-'.";
        } else {
            // Cek duplikasi kode, tapi exclude record yang sedang diedit (by id)
            $cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ? AND id_kategori != ?");
            $cek->bind_param("si", $kode, $id);
            $cek->execute();
            $cek->store_result();
            if ($cek->num_rows > 0) {
                $errors[] = "Kode kategori sudah digunakan oleh data lain.";
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

        // Kalau tidak ada error, jalankan UPDATE
        if (empty($errors)) {
            $update = $conn->prepare("UPDATE kategori SET kode_kategori = ?, nama_kategori = ?, deskripsi = ?, status = ? WHERE id_kategori = ?");
            $update->bind_param("ssssi", $kode, $nama, $deskripsi, $status, $id);

            if ($update->execute()) {
                header("Location: index.php?pesan=Kategori+berhasil+diperbarui&tipe=success");
                exit;
            } else {
                $errors[] = "Gagal memperbarui data. Silakan coba lagi.";
            }
            $update->close();
        }
    }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Edit Kategori</h4>
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
                                    value="<?= htmlspecialchars($kode) ?>" required>
                            </div>

                            <!-- Nama Kategori -->
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                                    value="<?= htmlspecialchars($nama) ?>" required>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= htmlspecialchars($deskripsi ?? '') ?></textarea>
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
                                <button type="submit" class="btn btn-warning">Update</button>
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
