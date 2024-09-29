<?php
require "koneksi.php"; // Menghubungkan ke database

// Ambil kategori dari database
$querykategori = mysqli_query($conn, "SELECT * FROM kategori");
if (!$querykategori) {
    die("Query kategori gagal: " . mysqli_error($conn));
}

// Mendapatkan nilai kategori dan sort dari query string
$kategori_id = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Menyusun query produk berdasarkan kategori dan sort
$queryproduk = "SELECT id, nama, harga, foto FROM produk";
if ($kategori_id > 0) {
    $queryproduk .= " WHERE kategori_id = $kategori_id";
}

// Menentukan urutan
switch ($sort_by) {
    case 'price_asc':
        $queryproduk .= " ORDER BY harga ASC";
        break;
    case 'price_desc':
        $queryproduk .= " ORDER BY harga DESC";
        break;
    case 'newest':
        $queryproduk .= " ORDER BY id DESC"; // Misalkan id terbaru adalah yang tertinggi
        break;
    default:
        break;
}

// Batasi hasil produk ke 9 per halaman
$limit = 9;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$queryproduk .= " LIMIT $offset, $limit";

$resultproduk = mysqli_query($conn, $queryproduk);
if (!$resultproduk) {
    die("Query produk gagal: " . mysqli_error($conn));
}

// Hitung total produk untuk pagination
$total_produk_query = "SELECT COUNT(*) as total FROM produk";
if ($kategori_id > 0) {
    $total_produk_query .= " WHERE kategori_id = $kategori_id";
}
$total_result = mysqli_query($conn, $total_produk_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_produk = $total_row['total'];
$total_halaman = ceil($total_produk / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Toko Online</title>

    <!-- Link Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        /* CSS tambahan untuk tampilan yang lebih baik */
        .breadcrumb a {
        color: inherit; /* Inherit color from parent (default text color) */
        text-decoration: none; /* Remove underline */
    }
    
    .breadcrumb a:hover {
        color: #343a40; /* Optional: color on hover, you can adjust as needed */
    }
    
    .breadcrumb i {
        margin-right: 5px; /* Add some space between icon and text */
    }
        body {
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            background-color: #f8f9fa;
            padding: 15px;
            border-right: 1px solid #dee2e6;
            height: 100%;
        }
        .product-card {
            transition: transform 0.2s;
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .sort-by {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Breadcrumb Section -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.php" class="text-dark">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-box"></i> Produk
        </li>
        <?php if ($kategori_id > 0): ?>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-tag"></i> 
                <?php
                // Mengambil nama kategori dari database untuk ditampilkan
                $kategori_query = mysqli_query($conn, "SELECT nama FROM kategori WHERE id = $kategori_id");
                if ($kategori_query) {
                    $kategori = mysqli_fetch_assoc($kategori_query);
                    echo htmlspecialchars($kategori['nama']);
                }
                ?>
            </li>
        <?php endif; ?>
    </ol>
</nav>


    <!-- Main Content Section -->
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar Kategori -->
            <div class="col-md-3 sidebar">
                <h4>Kategori</h4>
                <ul class="list-group">
                    <li class="list-group-item"><a href="produk.php" class="text-dark">Semua Kategori</a></li>
                    <?php while ($kategori = mysqli_fetch_assoc($querykategori)): ?>
                        <li class="list-group-item">
                            <a href="produk.php?kategori=<?php echo $kategori['id']; ?>" class="text-dark">
                                <?php echo htmlspecialchars($kategori['nama']); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Daftar Produk -->
            <div class="col-md-9">
                <div class="sort-by mb-4">
                    <label for="sort" class="mr-2">Sort By:</label>
                    <select id="sort" class="form-control d-inline-block" style="width: auto;" onchange="sortProducts(this.value)">
                        <option value="default" <?php if ($sort_by === 'default') echo 'selected'; ?>>Default</option>
                        <option value="price_asc" <?php if ($sort_by === 'price_asc') echo 'selected'; ?>>Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" <?php if ($sort_by === 'price_desc') echo 'selected'; ?>>Harga: Tinggi ke Rendah</option>
                        <option value="newest" <?php if ($sort_by === 'newest') echo 'selected'; ?>>Terbaru</option>
                    </select>
                </div>

            
                <div class="row text-center">
                    <?php while ($product = mysqli_fetch_assoc($resultproduk)): ?>
                        <div class="col-6 col-sm-4 col-md-4 mb-4">
                            <div class="card product-card">
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>">
                                    <img src="image/<?php echo htmlspecialchars($product['foto']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['nama']); ?>">
                                </a>
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($product['nama']); ?></h6>
                                    <p class="card-text">Rp <?php echo number_format($product['harga'], 2, ',', '.'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Navigasi Halaman -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="produk.php?kategori=<?php echo $kategori_id; ?>&sort=<?php echo $sort_by; ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                            <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                                <a class="page-link" href="produk.php?kategori=<?php echo $kategori_id; ?>&sort=<?php echo $sort_by; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_halaman): ?>
                            <li class="page-item">
                                <a class="page-link" href="produk.php?kategori=<?php echo $kategori_id; ?>&sort=<?php echo $sort_by; ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>

    <!-- Link Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function sortProducts(value) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('sort', value);
            window.location.search = urlParams.toString();
        }
    </script>
</body>
</html>
