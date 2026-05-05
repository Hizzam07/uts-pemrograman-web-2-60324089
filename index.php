<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
    require_once 'config/database.php';

    // Tampilkan pesan sukses atau error dari session (redirect sebelumnya)
    $pesan = '';
    $tipe_pesan = '';
    if (isset($_GET['pesan'])) {
        $pesan = htmlspecialchars($_GET['pesan']);
        $tipe_pesan = isset($_GET['tipe']) ? $_GET['tipe'] : 'success';
    }

    // Query semua data kategori, diurutkan dari yang terbaru
    $sql = "SELECT * FROM kategori ORDER BY id_kategori DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Kategori Buku</h2>
            <a href="create.php" class="btn btn-primary">+ Tambah Kategori</a>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-<?= $tipe_pesan ?> alert-dismissible fade show" role="alert">
                <?= $pesan ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Kode</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Tentukan warna badge berdasarkan status
                                $badge = ($row['status'] == 'Aktif') ? 'bg-success' : 'bg-danger';
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['kode_kategori']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama_kategori']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['deskripsi'] ?? '-') . "</td>";
                                echo "<td><span class='badge {$badge}'>" . $row['status'] . "</span></td>";
                                echo "<td>
                                        <a href='edit.php?id=" . $row['id_kategori'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                        <button onclick='confirmDelete(" . $row['id_kategori'] . ")' class='btn btn-danger btn-sm'>Hapus</button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted'>Belum ada data kategori</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function confirmDelete(id) {
        if (confirm('Yakin ingin menghapus kategori ini?')) {
            window.location.href = 'delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>
