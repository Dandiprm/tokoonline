<?php
require "session.php";
require "../koneksi.php";

// Proses tambah kategori
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama_kategori']) && !isset($_POST['edit_id'])) {
    $nama_kategori_baru = $_POST['nama_kategori'];

    // Cek apakah kategori sudah ada
    $cek_kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE nama = '$nama_kategori_baru'");
    if (mysqli_num_rows($cek_kategori) == 0) {
        $query_tambah = "INSERT INTO kategori (nama) VALUES ('$nama_kategori_baru')";
        mysqli_query($conn, $query_tambah);
        header("Location: kategori.php?pesan=tambah_sukses");
        exit();
    } else {
        $pesan = "Kategori sudah ada!";
    }
}

// Proses update kategori
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $id_kategori = $_POST['edit_id'];
    $nama_kategori_baru = $_POST['nama_kategori'];

    $query_update = "UPDATE kategori SET nama = '$nama_kategori_baru' WHERE id = '$id_kategori'";
    mysqli_query($conn, $query_update);
    header("Location: kategori.php?pesan=edit_sukses");
    exit();
}

// Proses hapus kategori
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus_id'])) {
    $id_kategori = $_POST['hapus_id'];

    $query_hapus = "DELETE FROM kategori WHERE id = '$id_kategori'";
    mysqli_query($conn, $query_hapus);
    header("Location: kategori.php?pesan=hapus_sukses");
    exit();
}

// Ambil data kategori
$querykategori = mysqli_query($conn, "SELECT * FROM kategori");
$jumlahkategori = mysqli_num_rows($querykategori);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Produk</title>
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
        .breadcrumb-item a {
            color: #6c757d;
        }
        .breadcrumb-item.active {
            color: #343a40;
        }
        .alert {
            display: none;
        }
        .alert.show {
            display: block;
        }
    </style>
</head>
<body>

    <?php require "navbar.php"; ?>
    
    <div class="container mt-4">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../adminpanel" class="text-muted text-decoration-none">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li class="breadcrumb-item text-muted">
            <i class="fas fa-align-justify"></i> Kategori
        </li>
        
    </ol>
</nav>

        <h2 class="mt-3">Kategori Produk</h2>

        <!-- Tampilkan Pesan Hasil Proses -->
        <?php if (isset($pesan)): ?>
            <div class="alert alert-danger show" role="alert">
                <?php echo $pesan; ?>
            </div>
        <?php endif; ?>

        <!-- Form Tambah Kategori -->
        <div class="card mt-3 col-md-4">
            <div class="card-header text-center ">
                <h3>Tambah Kategori Produk</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST" class="form-inline" novalidate>
                    <div class="form-group me-2">
                        <label for="nama_kategori" class="mb-1">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Masukkan nama kategori" required>
                    </div>
                    <button type="submit" class="btn btn-success mt-1">Simpan</button>
                </form>
            </div>
        </div>

        <!-- List Kategori -->
        <div class="mt-4 col-md-5">
            <h2>List Kategori</h2>
            <div class="table-responsive mt-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($jumlahkategori == 0) {
                                echo "<tr><td colspan='3' class='text-center'>Data kategori tidak tersedia</td></tr>";
                            } else {
                                $jumlah = 1;
                                while ($data = mysqli_fetch_array($querykategori)) {
                                    echo "<tr>
                                            <td>{$jumlah}</td>
                                            <td>{$data['nama']}</td>
                                            <td>
                                                <form action='' method='POST' style='display:inline;'>
                                                    <input type='hidden' name='hapus_id' value='{$data['id']}'>
                                                    <button type='submit' class='btn btn-danger btn-sm'>Hapus</button>
                                                </form>
                                                <button class='btn btn-warning btn-sm' onclick='editCategory({$data['id']}, \"{$data['nama']}\")'>Edit</button>
                                            </td>
                                          </tr>
                                          <tr id='edit-row-{$data['id']}' style='display:none;'>
                                            <td colspan='3'>
                                                <form action='' method='POST'>
                                                    <input type='hidden' name='edit_id' value='{$data['id']}'>
                                                    <div class='input-group'>
                                                        <input type='text' class='form-control' name='nama_kategori' value='{$data['nama']}' required>
                                                        <div class='input-group-append'>
                                                            <button type='submit' class='btn btn-success'>Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                          </tr>";
                                    $jumlah++;
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
    <script>
        function editCategory(id, name) {
            const editRow = document.getElementById(`edit-row-${id}`);
            if (editRow.style.display === "none") {
                editRow.style.display = "table-row"; // Show the edit form
            } else {
                editRow.style.display = "none"; // Hide the edit form
            }
        }
    </script>
</body>
</html>
