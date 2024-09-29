<?php
// File: navbar.php
require "koneksi.php"; // Koneksi ke database

// Query untuk mengambil kategori dari database
$query_kategori = mysqli_query($conn, "SELECT id, nama FROM kategori");
$kategori_list = [];
if ($query_kategori) {
    while ($row = mysqli_fetch_assoc($query_kategori)) {
        $kategori_list[] = $row;
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#" style="font-family: pacifico; font-size: 2rem; color: #333;">Twinstar.Id</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="produk.php">Produk</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Kategori
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php foreach ($kategori_list as $kategori): ?>
                            <a class="dropdown-item" href="kategori.php?id=<?php echo $kategori['id']; ?>"><?php echo htmlspecialchars($kategori['nama']); ?></a>
                        <?php endforeach; ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="semua-kategori.php">Lihat Semua Kategori</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#kontak">Kontak</a>
                </li>
            </ul>
            <div class="navbar-nav ml-auto">
                <form class="form-inline my-2 my-lg-0 mr-3" action="cari.php" method="GET">
                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Cari" aria-label="Search" required>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cari</button>
                </form>
                <a class="nav-link" href="https://shopee.co.id/twinstar_id" target="_blank">
                    <img src="image/shopee.png" alt="Shopee" style="width: 24px;">
                </a>
                <a class="nav-link" href="https://www.tiktok.com/shop" target="_blank">
                    <img src="image/tiktokshop.png" alt="TikTok Shop" style="width: 24px;">
                </a>
                <a class="nav-link" href="https://www.tokopedia.com" target="_blank">
                    <img src="image/tokopedia.png" alt="Tokopedia" style="width: 24px;">
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- CSS tambahan untuk styling -->
<style>
    .navbar {
        border-radius: 0; /* Membuat sudut tajam */
    }

    .navbar-brand {
        font-size: 2rem; /* Ukuran font nama brand */
        color: #333;
    }

    .navbar .nav-link {
        font-size: 1.1rem; /* Ukuran font link */
    }

    .dropdown-menu {
        border-radius: 5px; /* Membuat sudut membulat pada dropdown */
    }

    .dropdown-item:hover {
        background-color: #f8f9fa; /* Warna latar belakang saat hover */
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }

    .form-inline .form-control {
        margin-right: 5px;
    }

    .navbar-nav .nav-link img {
        width: 48px;
        height: 24px;
        display: inline-block;
    }

    .navbar-nav .nav-link img:hover {
        opacity: 0.8; /* Efek hover untuk ikon marketplace */
    }
</style>
