<?php
// File: banner.php
?>

<div id="bannerCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <a href="kategori1.php">
                <div class="banner-slide" style="background-image: url('image/banner-1.png');">
                   
                </div>
            </a>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <a href="kategori2.php">
                <div class="banner-slide" style="background-image: url('image/banner-2.png');">
                   
                </div>
            </a>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <a href="kategori3.php">
                <div class="banner-slide" style="background-image: url('image/banner.png');">
                   
                </div>
            </a>
        </div>
    </div>

    <!-- Carousel controls -->
    <a class="carousel-control-prev" href="#bannerCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#bannerCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- CSS untuk banner -->
<style>
    #bannerCarousel {
        border-radius: 20px; /* Membuat sudut membulat */
        overflow: hidden; /* Menyembunyikan overflow */
        margin: 30px 30px;
       


    .banner-slide {
        height: 60vh; /* Tinggi banner slide */
        display: flex;
        align-items: center; /* Vertikal tengah */
        justify-content: center; /* Horizontal tengah */
        color: white; /* Warna teks putih */
        text-align: center;
        padding: 50px; /* Padding di dalam slide */
        background-size: cover; /* Mengisi area dengan gambar */
        background-position: center; /* Memusatkan gambar */
    }

    }
</style>
