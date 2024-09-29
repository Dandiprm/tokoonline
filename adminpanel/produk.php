<?php
require "session.php";
require "../koneksi.php";

// Ambil data kategori
$querykategori = mysqli_query($conn, "SELECT * FROM kategori");

$message = ""; // Variabel untuk menyimpan pesan
$error_message = ""; // Variabel untuk menyimpan pesan kesalahan

// Proses tambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Proses menambahkan produk
    if ($action == 'add') {
        $kategori_id = $_POST['kategori_id'];
        $nama = $_POST['nama'];
        $harga = $_POST['harga'];
        $detail = $_POST['detail'];
        $ketersediaan_stok = $_POST['ketersediaan_stok'];

        // Cek apakah nama produk sudah ada
        $query_check = mysqli_query($conn, "SELECT * FROM produk WHERE nama = '$nama'");
        if (mysqli_num_rows($query_check) > 0) {
            $error_message = "<div class='alert alert-warning'>Produk dengan nama '$nama' sudah ada.</div>";
        } else {
            // Cek apakah file foto diupload
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $foto = $_FILES['foto']['name'];
                $temp_file = $_FILES['foto']['tmp_name'];

                // Validasi tipe dan ukuran file
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($_FILES['foto']['type'], $allowed_types) && $_FILES['foto']['size'] < 2000000) { // Maksimal 2 MB
                    // Pindahkan file ke direktori yang diinginkan
                    if (move_uploaded_file($temp_file, "../uploads/" . $foto)) {
                        // Jika berhasil, lanjutkan dengan query INSERT
                        $query = "INSERT INTO produk (kategori_id, nama, harga, foto, detail, ketersediaan_stok) VALUES ('$kategori_id', '$nama', '$harga', '$foto', '$detail', '$ketersediaan_stok')";
                        if (mysqli_query($conn, $query)) {
                            $message = "<div class='alert alert-success'>Produk berhasil ditambahkan.</div>";
                        } else {
                            $message = "<div class='alert alert-danger'>Gagal menambahkan produk: " . mysqli_error($conn) . "</div>";
                        }
                    } else {
                        $message = "<div class='alert alert-danger'>Error: Gagal mengupload foto.</div>";
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Error: Tipe file tidak didukung atau ukuran file terlalu besar.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger'>Error: Tidak ada file yang diupload atau terjadi kesalahan pada upload.</div>";
            }
        }
    }

    // Proses menghapus produk
    if ($action == 'delete') {
        $id = $_POST['id'];
        $query = "DELETE FROM produk WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            $message = "<div class='alert alert-success'>Produk berhasil dihapus.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Gagal menghapus produk: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Ambil data produk
$queryproduk = mysqli_query($conn, "SELECT p.*, k.nama AS kategori_nama FROM produk p JOIN kategori k ON p.kategori_id = k.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .available {
            color: green;
            font-weight: bold;
        }
        .out-of-stock {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="../adminpanel" class="text-decoration-none text-muted"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="breadcrumb-item text-muted" aria-current="page">
                    <i class="fas fa-box"></i> Produk
                </li>
            </ol>
        </nav>

        <!-- Menampilkan pesan -->
        <?= $message; ?>
        <?= $error_message; ?> <!-- Menampilkan pesan kesalahan -->

        <h2 class="mb-4">Tambah Produk</h2>
        <div class="card mb-4">
            <div class="card-header">
                Form Tambah Produk
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php while ($kategori = mysqli_fetch_assoc($querykategori)) { ?>
                                <option value="<?= $kategori['id']; ?>"><?= $kategori['nama']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Produk</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail</label>
                        <textarea class="form-control" id="detail" name="detail" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ketersediaan_stok">Ketersediaan Stok</label>
                        <input type="number" class="form-control" id="ketersediaan_stok" name="ketersediaan_stok" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        <h2 class="mb-4">List Produk</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Foto</th>
                        <th>Ketersediaan Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $jumlah = 1;
                    while ($data = mysqli_fetch_array($queryproduk)) {
                    ?>
                        <tr>
                            <td><?= $jumlah; ?></td>
                            <td><?= htmlspecialchars($data['nama']); ?></td>
                            <td><?= htmlspecialchars($data['kategori_nama']); ?></td>
                            <td>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
                            <td><img src="../uploads/<?= htmlspecialchars($data['foto']); ?>" alt="Foto" width="100"></td>
                            <td class="<?= ($data['ketersediaan_stok'] > 0) ? 'available' : 'out-of-stock'; ?>">
                                <?= ($data['ketersediaan_stok'] > 0) ? 'Tersedia' : 'Habis'; ?>
                            </td>
                            <td>
                                <a href="edit_produk.php?id=<?= $data['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                <form action="" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $data['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                        $jumlah++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>
