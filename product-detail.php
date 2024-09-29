<?php
require "koneksi.php"; // Koneksi ke database

// Mendapatkan ID produk dari parameter URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Query untuk mendapatkan detail produk
    $query_produk = mysqli_query($conn, "SELECT produk.id, produk.nama, produk.harga, produk.foto, produk.detail, produk.ketersediaan_stok, produk.kategori_id, kategori.nama AS kategori_nama 
                                         FROM produk 
                                         JOIN kategori ON produk.kategori_id = kategori.id 
                                         WHERE produk.id = $product_id");

    // Cek apakah query berhasil dijalankan
    if (!$query_produk) {
        die("Query produk gagal: " . mysqli_error($conn)); // Menampilkan error jika query gagal
    }

    if (mysqli_num_rows($query_produk) > 0) {
        $produk = mysqli_fetch_assoc($query_produk);
    } else {
        echo "Produk tidak ditemukan.";
        exit;
    }

    // Query untuk mendapatkan produk terkait berdasarkan kategori yang sama
    $kategori_id = $produk['kategori_id'];
    $query_produk_terkait = mysqli_query($conn, "SELECT id, nama, harga, foto FROM produk WHERE kategori_id = $kategori_id AND id != $product_id LIMIT 4");

    // Cek apakah query terkait berhasil dijalankan
    if (!$query_produk_terkait) {
        die("Query produk terkait gagal: " . mysqli_error($conn)); // Menampilkan error jika query gagal
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?php echo htmlspecialchars($produk['nama']); ?></title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #343a40;
        }

        .breadcrumb .active {
            color: #6c757d;
        }

        .breadcrumb i {
            margin-right: 5px;
        }

        .product-detail {
            margin-top: 30px;
        }

        .product-detail img {
            max-width: 100%;
            border-radius: 5px;
        }

        .related-products img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .btn-whatsapp {
            background-color: #25D366;
            color: white;
        }

        .btn-shopee {
            background-color: #FF4500;
            color: white;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5rem;
            }

            h2 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="produk.php"><i class="fas fa-box"></i> Produk</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-info-circle"></i> Detail Produk
            </li>
        </ol>
    </nav>

    <!-- Detail Produk -->
    <div class="container product-detail">
        <div class="row">
            <div class="col-md-6">
                <img src="image/<?php echo htmlspecialchars($produk['foto']); ?>" alt="<?php echo htmlspecialchars($produk['nama']); ?>">
            </div>
            <div class="col-md-6 mt-3">
            <div style="background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); font-family: 'Arial', sans-serif;">
    <h1 style="margin: 0; font-size: 28px; color: #333;"><?php echo htmlspecialchars($produk['nama']); ?></h1>
    <a href="produk.php?kategori_id=<?php echo urlencode($produk['kategori_id']); ?>" style="color: #007bff; text-decoration: none; font-weight: bold;">
        <span style="background-color: #e7f0ff; padding: 5px 10px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <?php echo htmlspecialchars($produk['kategori_nama']); ?>
        </span>
    </a>
    
    <h2 style="margin: 10px 0; font-size: 20px; color: #333;">Rp <?php echo number_format($produk['harga'], 2, ',', '.'); ?></h2> <!-- Warna harga diubah menjadi #333 -->
    <p style="margin: 0; font-size: 16px; color: #666;">
        <?php if ($produk['ketersediaan_stok'] > 0): ?>
            <span style="color: green; font-weight: bold;">Tersedia</span>
        <?php else: ?>
            <span style="color: red; font-weight: bold;">Stok Habis</span>
        <?php endif; ?>
    </p>
</div>

                <div class="mt-4">
                    <a href="#" class="btn btn-primary btn-lg mr-2 mb-2">Beli Sekarang</a>
                    <a href="https://wa.me/6281460813605?text=Halo,%20saya%20ingin%20membeli%20produk%20<?php echo urlencode($produk['nama']); ?>" class="btn btn-whatsapp btn-lg mr-2 mb-2">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>

                    <a href="https://shopee.co.id/twinstar_id" target="_blank" class="btn btn-shopee btn-lg mb-2">
    <i class="fas fa-shopping-bag"></i> shopee
</a>

    </a>


                </div>

            </div>
        </div>

        <!-- Detail Produk Di Kolom Terpisah -->
        <div class="row mt-5">
            <div class="col-12 ">
                <h3>Detail Produk</h3>
                <p class= "mt-4"><?php echo nl2br(htmlspecialchars($produk['detail'])); ?></p>
            </div>
        </div>
    </div>

    <!-- Produk Terkait -->
    <div class="container mt-5 related-products">
        <h3>Produk Terkait</h3>
        <div class="row">
            <?php while ($produk_terkait = mysqli_fetch_assoc($query_produk_terkait)): ?>
                <div class="col-md-3 mb-4 mt-4">
                    <div class="card">
                        <a href="product-detail.php?id=<?php echo $produk_terkait['id']; ?>">
                            <img src="image/<?php echo htmlspecialchars($produk_terkait['foto']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produk_terkait['nama']); ?>">
                        </a>
                        <div class="card-body">
                            <h6 class="card-title"><?php echo htmlspecialchars($produk_terkait['nama']); ?></h6>
                            <p class="card-text">Rp <?php echo number_format($produk_terkait['harga'], 2, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
