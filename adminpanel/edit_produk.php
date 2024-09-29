<?php
require "session.php";
require "../koneksi.php";

// Ambil ID produk dari query string
$id = $_GET['id'];

// Ambil data produk berdasarkan ID
$queryproduk = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id'");
$data = mysqli_fetch_assoc($queryproduk);

// Proses update produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $kategori_id = $_POST['kategori_id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $detail = $_POST['detail'];
    $ketersediaan_stok = $_POST['ketersediaan_stok'];

    // Update produk
    $query_update = "UPDATE produk SET kategori_id = '$kategori_id', nama = '$nama', harga = '$harga', detail = '$detail', ketersediaan_stok = '$ketersediaan_stok' WHERE id = '$id'";

    if (mysqli_query($conn, $query_update)) {
        // Proses upload foto baru jika ada
        if (isset($_FILES['foto'])) {
            foreach ($_FILES['foto']['name'] as $key => $foto_name) {
                $foto_tmp = $_FILES['foto']['tmp_name'][$key];
                $foto_path = "../uploads/" . basename($foto_name);

                // Upload foto
                if (move_uploaded_file($foto_tmp, $foto_path)) {
                    $query_foto = "INSERT INTO produk_foto (produk_id, foto) VALUES ('$id', '$foto_name')";
                    mysqli_query($conn, $query_foto);
                }
            }
        }
        // Redirect ke halaman tambah produk setelah sukses
        header("Location: produk.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating product: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        textarea {
            resize: none;
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
                <li class="breadcrumb-item">
                    <a href="produk.php" class="text-decoration-none text-muted"><i class="fas fa-box"></i> Produk</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit Produk</li>
            </ol>
        </nav>

        <h2 class="mb-4">Edit Produk</h2>
        <div class="card mb-4">
            <div class="card-header">
                Form Edit Produk
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit">
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                            $querykategori = mysqli_query($conn, "SELECT * FROM kategori");
                            while ($kategori = mysqli_fetch_assoc($querykategori)) {
                                $selected = ($kategori['id'] == $data['kategori_id']) ? 'selected' : '';
                                echo "<option value='{$kategori['id']}' $selected>{$kategori['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Produk</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($data['harga']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Baru (Opsional)</label>
                        <input type="file" class="form-control" id="foto" name="foto[]" multiple>
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail Produk</label>
                        <textarea class="form-control" id="detail" name="detail" rows="5" required><?= htmlspecialchars($data['detail']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ketersediaan_stok">Ketersediaan Stok</label>
                        <input type="number" class="form-control" id="ketersediaan_stok" name="ketersediaan_stok" value="<?= htmlspecialchars($data['ketersediaan_stok']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>
