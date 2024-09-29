<?php
require "koneksi.php"; // Menghubungkan ke database
$queryproduk = mysqli_query($conn, "SELECT id, nama, harga, foto FROM produk LIMIT 8");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products - Toko Online</title>

    <!-- Link Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Include Banner -->
    <div class="container-fluid p-0">
        <?php include 'banner.php'; ?>
    </div>

    <!-- Welcome and Keunggulan Section -->
    <div class="welcome-section text-center my-2">
        <div class="container"> 
            <h1 class="display-5">Welcome to Twinstar Id</h1>
            <p class="lead">Temukan produk terbaik dan layanan luar biasa di toko kami!</p>
            <a href="produk.php" class="btn btn-primary btn-lg">Jelajahi Produk</a>

            <!-- Keunggulan Section -->
            <div class="row text-center my-3">
                <!-- Keunggulan 1 -->
                <div class="col-md-4">
                    <div class="icon-box">
                        <i class="fas fa-thumbs-up fa-3x"></i>
                        <h3>Produk Berkualitas</h3>
                        <p>Kami menyediakan produk dengan kualitas terbaik yang sudah teruji.</p>
                    </div>
                </div>

                <!-- Keunggulan 2 -->
                <div class="col-md-4">
                    <div class="icon-box">
                        <i class="fas fa-shipping-fast fa-3x"></i>
                        <h3>Pengiriman Cepat</h3>
                        <p>Pengiriman cepat dan tepat waktu untuk kepuasan Anda.</p>
                    </div>
                </div>

                <!-- Keunggulan 3 -->
                <div class="col-md-4">
                    <div class="icon-box">
                        <i class="fas fa-headset fa-3x"></i>
                        <h3>Layanan Pelanggan</h3>
                        <p>Tim kami siap membantu Anda dengan layanan terbaik.</p>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <!-- Produk Section -->
    <div class="mt-5 container">
        <h2 class="text-center mb-5">Our Products</h2>
        <div class="row text-center">
            <?php while ($product = mysqli_fetch_assoc($queryproduk)): ?>
                <div class="col-md-3 mb-4">
                    <div class="card product-card">
                        <a href="product-detail.php?id=<?php echo $product['id']; ?>">
                            <img src="image/<?php echo htmlspecialchars($product['foto']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['nama']); ?>">
                        </a>
                        <div class="card-body">
                            <h6 class="card-title"><?php echo htmlspecialchars($product['nama']); ?></h6>
                            <p class="card-text"> Rp <?php echo number_format($product['harga'], 2, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Tombol See More -->
        <div class="text-center mb-4">
            <a href="produk.php" class="btn-lg btn btn-outline-success">See More</a>
        </div>
    </div>




    <!-- Link Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<?php include 'footer.php'; ?>


</html>
