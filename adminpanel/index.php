<?php
require "session.php";
require "../koneksi.php";

$querykategori = mysqli_query($conn, "SELECT * FROM kategori");
$jumlahkategori = mysqli_num_rows($querykategori);

$queryproduk = mysqli_query($conn, "SELECT * FROM produk");
$jumlahproduk = mysqli_num_rows($queryproduk);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel = "stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.cs">
</head>
<style>
    .kotak {
        border: solid;
    }
    .summary-kategori {
        background-color: #938C8A;
        border-radius: 10px;
    }
    .summary-produk {
        background-color: #93999D;
        border-radius: 10px;
    }
    .no-dekorasi {
        text-decoration: none;
    }
   
</style>
<body>
    <?php require"navbar.php" ?>
    <div class="container mt-4" >
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
               <i class="fas fa-home"></i>  Home
            </li>
           
        </ol>
    </nav>
    <h2>Hello admin</h2>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 mb-3 ">
                <div class="kotak summary-kategori p-3">
                    <div class="row">
                        <div class="col-6">
                            <i class="fas fa-align-justify fa-7x text-black-50"></i>
                        </div>
                        <div class="col-6 text-white ">
                            <h2 class="fs-2">Kategori</h2>
                            <p class="fs-4"><?php echo $jumlahkategori ?>  Kategori</p>
                            <p><a href= "kategori.php" class="text-white no-dekorasi ">lihat detail</a></p>
                        </div>
                    </div>  
                </div>
                 
            </div>
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class=" kotak summary-produk p-3">
                        <div class="row">
                            <div class="col-6">
                                <i class="fas fa-box fa-7x text-black-50"></i>
                            </div>
                            <div class="col-6 text-white">
                                <h2 class="fs-2">Produk</h2>
                                <p class="fs-4"><?php echo $jumlahproduk ?>  Produk</p>
                                <p><a href= "produk.php" class="text-white no-dekorasi ">lihat detail</a></p>
                            </div>
                        </div>
                    </div>
                    
                </div>
        </div>
        

    </div>
    </div>

    <script scr="../bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
       
</body>
</html>