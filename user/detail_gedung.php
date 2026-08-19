<?php
session_start();
$currentPage = 'gedung';
require_once "../config/database.php";
require_once "../helpers/gedung_helper.php";

$baseUrl = "../";

/*==================================================
VALIDASI ID GEDUNG
==================================================*/

if (!isset($_GET['id'])) {
    header("Location: gedung.php");
    exit;
}

$idLokasi = (int) $_GET['id'];

/*==================================================
AMBIL DATA
==================================================*/

$gedung = getGedungById($conn, $idLokasi);

if (!$gedung) {
    header("Location: gedung.php");
    exit;
}

$cover = getFotoCoverGedung($conn, $idLokasi);
$gallery = getGalleryGedung($conn, $idLokasi);

$totalLantai      = getJumlahLantai($conn, $idLokasi);
$totalRuangan     = getJumlahRuangan($conn, $idLokasi);
$totalPublicSpace = getJumlahPublicSpace($conn, $idLokasi);
$totalInventaris  = getJumlahInventaris($conn, $idLokasi);

$lantai = getLantaiGedung($conn, $idLokasi);

?>

<?php include "../includes/user/header.php"; ?>

<style>

/*==================================================
BODY
==================================================*/

body{

    background:#FFF8FB;

}

/*==================================================
HERO
==================================================*/

.detail-hero{

    padding:60px 0 40px;

}

.hero-box{

    overflow:hidden;

    border-radius:35px;

    display:grid;

    grid-template-columns:1.1fr 1fr;

    align-items:center;

    background:linear-gradient(135deg,#EC4899,#FF7A45);

    min-height:430px;

}
/*==================================================
GENERAL SECTION
==================================================*/

.detail-section{

    padding:35px 0;

}

.section-title{

    display:flex;

    align-items:center;

    gap:10px;

    margin-bottom:22px;

}

.section-title i{

    color:#EC4899;

    font-size:24px;

}

.section-title h2{

    margin:0;

    font-size:28px;

    font-weight:700;

    color:#2D3748;

}

/*==================================================
INFO CARD
==================================================*/

.info-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:22px;

}

.info-card{

    background:#fff;

    border-radius:22px;

    padding:28px 22px;

    text-align:center;

    box-shadow:0 10px 25px rgba(0,0,0,.06);

    transition:.3s;

}

.info-card:hover{

    transform:translateY(-5px);

}

.info-icon{

    width:68px;

    height:68px;

    margin:auto auto 15px;

    border-radius:18px;

    background:#FFF1F6;

    color:#EC4899;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:28px;

}

.info-card h5{

    margin-bottom:10px;

    font-weight:700;

    color:#2D3748;

}

.info-card p{

    margin:0;

    color:#6B7280;

    line-height:1.6;

    font-size:15px;

}

/*==================================================
GALERI
==================================================*/

.gallery-wrapper{

    display:flex;

    overflow-x:auto;

    gap:18px;

    scroll-behavior:smooth;

    padding-bottom:8px;

}

.gallery-wrapper::-webkit-scrollbar{

    height:8px;

}

.gallery-wrapper::-webkit-scrollbar-thumb{

    background:#F8A7C9;

    border-radius:50px;

}

.gallery-item{

    min-width:260px;

    height:180px;

    border-radius:22px;

    overflow:hidden;

    flex-shrink:0;

    box-shadow:0 10px 22px rgba(0,0,0,.08);

}

.gallery-item img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.35s;

}

.gallery-item:hover img{

    transform:scale(1.08);

}

/*==================================================
STATISTIK
==================================================*/

.stat-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:22px;

}

.stat-card{

    background:#fff;

    border-radius:22px;

    padding:28px;

    text-align:center;

    box-shadow:0 10px 25px rgba(0,0,0,.06);

}

.stat-icon{

    width:70px;

    height:70px;

    margin:auto auto 16px;

    border-radius:18px;

    background:linear-gradient(135deg,#EC4899,#FF7A45);

    color:white;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:30px;

}

.stat-card h3{

    margin:0;

    font-size:42px;

    color:#2D3748;

    font-weight:800;

}

.stat-card span{

    color:#6B7280;

}
/*==================================================
DAFTAR LANTAI
==================================================*/

.floor-section{

    padding:40px 0 60px;

}

.floor-list{

    display:flex;

    flex-direction:column;

    gap:18px;

}

.floor-card{

    background:#fff;

    border-radius:24px;

    padding:24px 28px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    box-shadow:0 10px 25px rgba(0,0,0,.06);

    transition:.3s;

    cursor:pointer;

}

.floor-card:hover{

    transform:translateY(-5px);

    box-shadow:0 18px 35px rgba(236,72,153,.12);

}

.floor-left{

    display:flex;

    align-items:center;

    gap:18px;

}

.floor-icon{

    width:62px;

    height:62px;

    border-radius:18px;

    background:linear-gradient(135deg,#EC4899,#FF7A45);

    display:flex;

    justify-content:center;

    align-items:center;

    color:white;

    font-size:26px;

}

.floor-name{

    font-size:22px;

    font-weight:700;

    color:#2D3748;

    margin-bottom:6px;

}

.floor-desc{

    display:flex;

    gap:18px;

    color:#6B7280;

    font-size:14px;

}

.floor-arrow{

    width:46px;

    height:46px;

    border-radius:50%;

    background:#FFF1F6;

    color:#EC4899;

    display:flex;

    justify-content:center;

    align-items:center;

    font-size:18px;

}

@media(max-width:768px){

.floor-card{

padding:18px;

}

.floor-icon{

width:52px;
height:52px;

font-size:22px;

}

.floor-name{

font-size:18px;

}

.floor-desc{

display:block;

}

}
@media(max-width:576px){

    .info-grid,
    .stat-grid{

        grid-template-columns:repeat(2,1fr);
        gap:12px;

    }

    .info-card{

        padding:18px 12px;
        border-radius:16px;

    }

    .info-icon{

        width:48px;
        height:48px;
        border-radius:12px;
        font-size:20px;
        margin-bottom:10px;

    }

    .info-card h5{

        font-size:13px;
        margin-bottom:6px;

    }

    .info-card p{

        font-size:11px;
        line-height:1.4;

    }

    .stat-card{

        padding:18px 12px;
        border-radius:16px;

    }

    .stat-icon{

        width:48px;
        height:48px;
        border-radius:12px;
        font-size:20px;
        margin-bottom:10px;

    }

    .stat-card h3{

        font-size:28px;

    }

    .stat-card span{

        font-size:11px;

    }

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:992px){

.info-grid,
.stat-grid{

grid-template-columns:repeat(2,1fr);

}

}
@media(max-width:768px){

    .info-grid,
    .stat-grid{

        grid-template-columns:repeat(2, 1fr);
        gap:12px;

    }

    .info-card{

        padding:18px 12px;
        border-radius:16px;

    }

    .info-icon{

        width:48px;
        height:48px;
        border-radius:12px;
        font-size:20px;
        margin-bottom:10px;

    }

    .info-card h5{

        font-size:13px;
        margin-bottom:6px;

    }

    .info-card p{

        font-size:11px;
        line-height:1.4;

    }

    .stat-card{

        padding:18px 12px;
        border-radius:16px;

    }

    .stat-icon{

        width:48px;
        height:48px;
        border-radius:12px;
        font-size:20px;
        margin-bottom:10px;

    }

    .stat-card h3{

        font-size:28px;

    }

    .stat-card span{

        font-size:11px;

    }

    .gallery-item{

        min-width:220px;
        height:160px;

    }

}

/*==================================================
TEXT
==================================================*/

.hero-content{

    color:white;

    padding:60px;

}

.breadcrumb-custom{

    font-size:14px;

    margin-bottom:20px;

    opacity:.9;

}

.breadcrumb-custom a{

    color:white;

    text-decoration:none;

}

.hero-title{

    font-size:58px;

    font-weight:800;

    margin-bottom:18px;

    line-height:1.1;

}

.hero-description{

    font-size:19px;

    line-height:1.9;

    margin-bottom:30px;

    opacity:.95;

    max-width:520px;

}

.hero-location{

    display:flex;

    align-items:center;

    gap:10px;

    font-size:17px;

    font-weight:500;

}

.hero-location i{

    font-size:20px;

}

/*==================================================
IMAGE
==================================================*/

.hero-image{

    height:100%;

}

.hero-image img{

    width:100%;

    height:100%;

    object-fit:cover;

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:992px){

.hero-box{

grid-template-columns:1fr;

}

.hero-content{

padding:40px 30px;

}

.hero-title{

font-size:40px;

}

.hero-image{

height:320px;

}

}

/*==================================================
DROPDOWN LANTAI
==================================================*/

.floor-card-wrapper {
    margin-bottom: 18px;
}

.floor-card {
    position: relative;
}

.floor-card.active {
    border-radius: 24px 24px 0 0;
}

.floor-card.active .floor-arrow i {
    transform: rotate(180deg);
}

.floor-arrow i {
    transition: transform .3s ease;
}

/*==================================================
ISI DROPDOWN
==================================================*/

.floor-content {
    display: none;

    background: #FFFFFF;

    border-radius: 0 0 24px 24px;

    padding: 0 28px 28px;

    box-shadow: 0 10px 25px rgba(0,0,0,.06);
}

.floor-card.active + .floor-content {
    display: block;
}

.floor-content-inner {
    border-top: 1px solid #F1F1F1;

    padding-top: 24px;
}
/*==================================================
ITEM RUANGAN & PUBLIC SPACE
==================================================*/

.floor-item-list {

    display: flex;

    flex-direction: column;

    gap: 10px;

}


.floor-item {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 12px 14px;

    background: #FFF8FB;

    border: 1px solid #FCE7F3;

    border-radius: 12px;

    transition: all .2s ease;

}


.floor-item:hover {

    background: #FFF0F6;

    transform: translateX(3px);

}


.floor-item-icon {

    width: 36px;

    height: 36px;

    min-width: 36px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    background: linear-gradient(
        135deg,
        #EC4899,
        #FF7A45
    );

    color: #FFFFFF;

}


.floor-item-info {

    min-width: 0;

}


.floor-item-name {

    font-size: 14px;

    font-weight: 600;

    color: #27364B;

}


.floor-item-code {

    margin-top: 2px;

    font-size: 12px;

    color: #9CA3AF;

}

/*==================================================
EMPTY DATA
==================================================*/

.empty-floor {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 15px;

    border-radius: 12px;

    background: #FFF8FB;

    color: #9CA3AF;

    font-size: 14px;

}


.empty-floor i {

    color: #EC4899;

}
/* ==================================================
   GALLERY LIGHTBOX / POPUP
================================================== */

.gallery-item {
    cursor: pointer;
}

.gallery-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(0, 0, 0, 0.90);
    align-items: center;
    justify-content: center;
    padding: 30px;
}

.gallery-lightbox.active {
    display: flex;
}

.gallery-lightbox-content {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-lightbox-image {
    max-width: 85%;
    max-height: 85vh;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 14px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, .45);
    user-select: none;
    -webkit-user-drag: none;
}


/* CLOSE */

.gallery-lightbox-close {
    position: fixed;
    top: 22px;
    right: 28px;
    width: 48px;
    height: 48px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, .18);
    color: #fff;
    font-size: 24px;
    cursor: pointer;
    z-index: 100001;

    display: flex;
    align-items: center;
    justify-content: center;

    transition: .2s;
}

.gallery-lightbox-close:hover {
    background: rgba(255, 255, 255, .32);
}


/* PREV / NEXT */

.gallery-lightbox-btn {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);

    width: 52px;
    height: 52px;

    border: none;
    border-radius: 50%;

    background: rgba(255, 255, 255, .18);
    color: #fff;

    font-size: 24px;
    cursor: pointer;

    z-index: 100001;

    display: flex;
    align-items: center;
    justify-content: center;

    transition: .2s;
}

.gallery-lightbox-btn:hover {
    background: rgba(255, 255, 255, .32);
}

.gallery-lightbox-prev {
    left: 25px;
}

.gallery-lightbox-next {
    right: 25px;
}


/* COUNTER */

.gallery-lightbox-counter {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);

    color: #fff;
    background: rgba(0, 0, 0, .45);

    padding: 7px 15px;
    border-radius: 30px;

    font-size: 14px;
    z-index: 100001;
}


/* MOBILE */

@media (max-width: 768px) {

    .gallery-lightbox {
        padding: 15px;
    }

    .gallery-lightbox-image {
        max-width: 92%;
        max-height: 78vh;
        border-radius: 10px;
    }

    .gallery-lightbox-close {
        top: 15px;
        right: 15px;

        width: 42px;
        height: 42px;

        font-size: 20px;
    }

    .gallery-lightbox-btn {
        width: 42px;
        height: 42px;
        font-size: 19px;
    }

    .gallery-lightbox-prev {
        left: 10px;
    }

    .gallery-lightbox-next {
        right: 10px;
    }

    .gallery-lightbox-counter {
        bottom: 15px;
        font-size: 12px;
    }

}
</style>

</head>

<body>

<?php include "../includes/user/navbar.php"; ?>

<section class="detail-hero">

<div class="container">

<div class="hero-box">

<div class="hero-content">

<div class="breadcrumb-custom">

<a href="../index.php">Beranda</a>

&nbsp;/&nbsp;

<a href="gedung.php">Gedung</a>

&nbsp;/&nbsp;

<?= htmlspecialchars($gedung['nama_lokasi']); ?>

</div>

<h1 class="hero-title">

<?= htmlspecialchars($gedung['nama_lokasi']); ?>

</h1>

<p class="hero-description">

<?= nl2br(htmlspecialchars($gedung['deskripsi'])); ?>

</p>

<div class="hero-location">

<i class="bi bi-geo-alt-fill"></i>

<?= htmlspecialchars($gedung['alamat']); ?>

</div>

</div>

<div class="hero-image">

<?php if($cover){ ?>

<img
src="<?= $baseUrl; ?>assets/uploads/lokasi/<?= $cover['nama_file']; ?>"
alt="<?= htmlspecialchars($gedung['nama_lokasi']); ?>">

<?php }else{ ?>

<img
src="<?= $baseUrl; ?>assets/img/no-image.png"
alt="No Image">

<?php } ?>

</div>

</div>

</div>

</section>
<section class="detail-section">

<div class="container">

<div class="section-title">

<i class="bi bi-card-list"></i>

<h2>Informasi Singkat</h2>

</div>

<div class="info-grid">

<div class="info-card">

<div class="info-icon">
<i class="bi bi-geo-alt-fill"></i>
</div>

<h5>Alamat</h5>

<p><?= htmlspecialchars($gedung['alamat']); ?></p>

</div>

<div class="info-card">

<div class="info-icon">
<i class="bi bi-building"></i>
</div>

<h5>Kode Gedung</h5>

<p><?= htmlspecialchars($gedung['kode_lokasi']); ?></p>

</div>

<div class="info-card">

<div class="info-icon">
<i class="bi bi-layers-fill"></i>
</div>

<h5>Total Lantai</h5>

<p><?= $totalLantai; ?> Lantai</p>

</div>

<div class="info-card">

<div class="info-icon">
<i class="bi bi-check-circle-fill"></i>
</div>

<h5>Status</h5>

<p><?= htmlspecialchars($gedung['status']); ?></p>

</div>

</div>

</div>

</section>
<section class="detail-section">

<div class="container">

<div class="section-title">

<i class="bi bi-images"></i>

<h2>Galeri Gedung</h2>

</div>

<div class="gallery-wrapper">

    <?php foreach ($gallery as $index => $foto): ?>

        <div
            class="gallery-item"
            data-gallery-index="<?= $index; ?>"
        >

            <img
                src="<?= $baseUrl; ?>assets/uploads/lokasi/<?= htmlspecialchars($foto['nama_file']); ?>"
                alt="<?= htmlspecialchars($gedung['nama_lokasi']); ?>"
                loading="lazy"
            >

        </div>

    <?php endforeach; ?>

</div>

</div>

</div>

</section>
<section class="detail-section">

<div class="container">

<div class="section-title">

<i class="bi bi-bar-chart"></i>

<h2>Statistik Gedung</h2>

</div>

<div class="stat-grid">

<div class="stat-card">

<div class="stat-icon">

<i class="bi bi-building"></i>

</div>

<h3><?= $totalLantai; ?></h3>

<span>Lantai</span>

</div>

<div class="stat-card">

<div class="stat-icon">

<i class="bi bi-door-open"></i>

</div>

<h3><?= $totalRuangan; ?></h3>

<span>Ruangan</span>

</div>

<div class="stat-card">

<div class="stat-icon">

<i class="bi bi-tree-fill"></i>

</div>

<h3><?= $totalPublicSpace; ?></h3>

<span>Public Space</span>

</div>

<div class="stat-card">

<div class="stat-icon">

<i class="bi bi-box-seam"></i>

</div>

<h3><?= $totalInventaris; ?></h3>

<span>Inventaris</span>

</div>

</div>

</div>

</section>
<section class="floor-section">

<div class="container">

<div class="section-title">

<i class="bi bi-buildings-fill"></i>

<h2>Daftar Lantai</h2>

</div>

<div class="floor-list">

    <?php foreach($lantai as $lt): ?>

        <?php

        $jumlahRuangan = count(
            getRuanganPerLantai($conn, $lt['id_lantai'])
        );

        $jumlahPublic = count(
            getPublicSpacePerLantai($conn, $lt['id_lantai'])
        );

        ?>

        <div class="floor-card-wrapper">

            <!-- CARD LANTAI -->

            <div
                class="floor-card"
                data-id="<?= $lt['id_lantai']; ?>">

                <div class="floor-left">

                    <div class="floor-icon">

                        <i class="bi bi-building"></i>

                    </div>

                    <div>

                        <div class="floor-name">

                            <?= htmlspecialchars($lt['nama_lantai']); ?>

                        </div>

                        <div class="floor-desc">

                            <span>
                                🚪 <?= $jumlahRuangan; ?> Ruangan
                            </span>

                            <span>
                                🌳 <?= $jumlahPublic; ?> Public Space
                            </span>

                        </div>

                    </div>

                </div>

                <div class="floor-arrow">

                    <i class="bi bi-chevron-down"></i>

                </div>

            </div>


            <!-- ISI DROPDOWN -->

            <div class="floor-content">

    <div class="floor-content-inner">

        <?php

        $ruangan = getRuanganPerLantai(
            $conn,
            $lt['id_lantai']
        );

        $publicSpace = getPublicSpacePerLantai(
            $conn,
            $lt['id_lantai']
        );

        ?>


        <div class="row g-4">


            <!-- ==============================
                 RUANGAN
            =============================== -->

            <div class="col-lg-7">

                <div class="content-title">

                    <i class="bi bi-door-open-fill"></i>

                    <h3>
                        Ruangan
                    </h3>

                </div>


                <?php if (!empty($ruangan)): ?>

                    <div class="floor-item-list">

                        <?php foreach ($ruangan as $r): ?>

                            <div class="floor-item">

                                <div class="floor-item-icon">

                                    <i class="bi bi-door-open"></i>

                                </div>

                                <div class="floor-item-info">

                                    <div class="floor-item-name">

                                        <?= htmlspecialchars(
                                            $r['nama_ruangan']
                                        ); ?>

                                    </div>

                                    <?php if (!empty($r['kode_ruangan'])): ?>

                                        <div class="floor-item-code">

                                            <?= htmlspecialchars(
                                                $r['kode_ruangan']
                                            ); ?>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-floor">

                        <i class="bi bi-door-open"></i>

                        <span>
                            Belum ada ruangan
                        </span>

                    </div>

                <?php endif; ?>

            </div>


            <!-- ==============================
                 PUBLIC SPACE
            =============================== -->

            <div class="col-lg-5">

                <div class="content-title">

                    <i class="bi bi-tree-fill"></i>

                    <h3>
                        Public Space
                    </h3>

                </div>


                <?php if (!empty($publicSpace)): ?>

                    <div class="floor-item-list">

                        <?php foreach ($publicSpace as $ps): ?>

                            <div class="floor-item">

                                <div class="floor-item-icon">

                                    <i class="bi bi-geo-alt-fill"></i>

                                </div>

                                <div class="floor-item-info">

                                    <div class="floor-item-name">

                                        <?= htmlspecialchars(
                                            $ps['nama_public_space']
                                        ); ?>

                                    </div>

                                    <?php if (!empty($ps['kode_public_space'])): ?>

                                        <div class="floor-item-code">

                                            <?= htmlspecialchars(
                                                $ps['kode_public_space']
                                            ); ?>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-floor">

                        <i class="bi bi-tree"></i>

                        <span>
                            Belum ada public space
                        </span>

                    </div>

                <?php endif; ?>

            </div>


        </div>

    </div>

</div>
    <?php endforeach; ?>

</div>

</div>

</section>
<!-- ==================================================
     GALLERY LIGHTBOX
================================================== -->

<div
    class="gallery-lightbox"
    id="galleryLightbox"
>

    <!-- CLOSE -->

    <button
        type="button"
        class="gallery-lightbox-close"
        id="galleryLightboxClose"
        aria-label="Tutup"
    >

        <i class="bi bi-x-lg"></i>

    </button>


    <!-- PREVIOUS -->

    <button
        type="button"
        class="gallery-lightbox-btn gallery-lightbox-prev"
        id="galleryLightboxPrev"
        aria-label="Foto sebelumnya"
    >

        <i class="bi bi-chevron-left"></i>

    </button>


    <!-- IMAGE -->

    <div class="gallery-lightbox-content">

        <img
            src=""
            alt="Gallery Gedung"
            class="gallery-lightbox-image"
            id="galleryLightboxImage"
        >

    </div>


    <!-- NEXT -->

    <button
        type="button"
        class="gallery-lightbox-btn gallery-lightbox-next"
        id="galleryLightboxNext"
        aria-label="Foto berikutnya"
    >

        <i class="bi bi-chevron-right"></i>

    </button>


    <!-- COUNTER -->

    <div
        class="gallery-lightbox-counter"
        id="galleryLightboxCounter"
    >
        1 / 1
    </div>

</div>
<script>

document.addEventListener("DOMContentLoaded", function () {

    /* ==================================================
       GALLERY LIGHTBOX
    ================================================== */

    const galleryItems =
        document.querySelectorAll(".gallery-item");

    const lightbox =
        document.getElementById("galleryLightbox");

    const lightboxImage =
        document.getElementById("galleryLightboxImage");

    const closeButton =
        document.getElementById("galleryLightboxClose");

    const prevButton =
        document.getElementById("galleryLightboxPrev");

    const nextButton =
        document.getElementById("galleryLightboxNext");

    const counter =
        document.getElementById("galleryLightboxCounter");


    if (!lightbox || galleryItems.length === 0) {
        return;
    }


    let currentIndex = 0;


    /* ==================================================
       AMBIL SEMUA FOTO
    ================================================== */

    const images =
        Array.from(galleryItems).map(function (item) {

            const image =
                item.querySelector("img");

            return image ? image.src : "";

        }).filter(Boolean);


    /* ==================================================
       TAMPILKAN FOTO
    ================================================== */

    function showImage(index) {

        if (images.length === 0) {
            return;
        }


        /* LOOP FOTO */

        if (index < 0) {

            index = images.length - 1;

        }


        if (index >= images.length) {

            index = 0;

        }


        currentIndex = index;


        lightboxImage.src =
            images[currentIndex];


        counter.textContent =
            (currentIndex + 1)
            + " / "
            + images.length;


        lightbox.classList.add("active");


        /* LOCK SCROLL */

        document.body.style.overflow =
            "hidden";

    }


    /* ==================================================
       TUTUP POPUP
    ================================================== */

    function closeLightbox() {

        lightbox.classList.remove("active");

        lightboxImage.src = "";

        document.body.style.overflow =
            "";

    }


    /* ==================================================
       NEXT
    ================================================== */

    function nextImage() {

        showImage(
            currentIndex + 1
        );

    }


    /* ==================================================
       PREVIOUS
    ================================================== */

    function prevImage() {

        showImage(
            currentIndex - 1
        );

    }


    /* ==================================================
       KLIK FOTO
    ================================================== */

    galleryItems.forEach(
        function (item, index) {

            item.addEventListener(
                "click",
                function () {

                    showImage(index);

                }
            );

        }
    );


    /* ==================================================
       TOMBOL NEXT
    ================================================== */

    nextButton.addEventListener(
        "click",
        function (event) {

            event.stopPropagation();

            nextImage();

        }
    );


    /* ==================================================
       TOMBOL PREV
    ================================================== */

    prevButton.addEventListener(
        "click",
        function (event) {

            event.stopPropagation();

            prevImage();

        }
    );


    /* ==================================================
       TOMBOL CLOSE
    ================================================== */

    closeButton.addEventListener(
        "click",
        function (event) {

            event.stopPropagation();

            closeLightbox();

        }
    );


    /* ==================================================
       KLIK BACKGROUND
    ================================================== */

    lightbox.addEventListener(
        "click",
        function (event) {

            if (
                event.target === lightbox
            ) {

                closeLightbox();

            }

        }
    );


    /* ==================================================
       KEYBOARD
    ================================================== */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                !lightbox.classList.contains("active")
            ) {

                return;

            }


            if (
                event.key === "Escape"
            ) {

                closeLightbox();

            }


            if (
                event.key === "ArrowRight"
            ) {

                nextImage();

            }


            if (
                event.key === "ArrowLeft"
            ) {

                prevImage();

            }

        }
    );


    /* ==================================================
       SWIPE MOBILE
    ================================================== */

    let touchStartX = 0;

    let touchEndX = 0;


    lightboxImage.addEventListener(
        "touchstart",
        function (event) {

            touchStartX =
                event.changedTouches[0].screenX;

        },
        {
            passive: true
        }
    );


    lightboxImage.addEventListener(
        "touchend",
        function (event) {

            touchEndX =
                event.changedTouches[0].screenX;


            const swipeDistance =
                touchEndX - touchStartX;


            /* Abaikan gerakan terlalu pendek */

            if (
                Math.abs(swipeDistance) < 50
            ) {

                return;

            }


            /* Swipe kiri */

            if (
                swipeDistance < 0
            ) {

                nextImage();

            }

            /* Swipe kanan */

            else {

                prevImage();

            }

        },
        {
            passive: true
        }
    );

});

</script>
<script>


document.querySelectorAll('.floor-card').forEach(function(card) {

    card.addEventListener('click', function() {

        this.classList.toggle('active');

    });

});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>