<?php
session_start();

require_once '../config/database.php';
require_once '../helpers/gedung_helper.php';

$baseUrl = "../";

/*
|--------------------------------------------------------------------------
| DATA
|--------------------------------------------------------------------------
*/

$gedungList = getAllGedung($conn);

$totalGedung      = countGedung($conn);
$totalLantai      = countLantai($conn);
$totalRuangan     = countRuangan($conn);
$totalInventaris  = countInventaris($conn);
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../includes/user/header.php'; ?>

<body>

<?php include '../includes/user/navbar.php'; ?>

<style>

/*==================================================
PAGE
==================================================*/

.gedung-page{

    background:#fff8fc;

    min-height:100vh;

}

/*==================================================
HERO
==================================================*/

.page-hero{

    position:relative;

    overflow:hidden;

    background:
        linear-gradient(
            135deg,
            #EC4899 0%,
            #FF7A45 100%
        );

    padding:70px 0 120px;

}

.page-hero::before{

    content:"";

    position:absolute;

    width:420px;
    height:420px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    top:-180px;
    right:-120px;

}

.page-hero::after{

    content:"";

    position:absolute;

    width:260px;
    height:260px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    left:-80px;
    bottom:-120px;

}

.page-hero .container{

    position:relative;

    z-index:2;

}

/*==================================================
BREADCRUMB
==================================================*/

.breadcrumb-custom{

    display:flex;

    align-items:center;

    gap:8px;

    margin-bottom:18px;

    font-size:14px;

}

.breadcrumb-custom a{

    color:#fff;

    text-decoration:none;

}

.breadcrumb-custom span{

    color:rgba(255,255,255,.8);

}

/*==================================================
BADGE
==================================================*/

.hero-badge{

    display:inline-flex;

    align-items:center;

    gap:10px;

    padding:10px 18px;

    border-radius:50px;

    background:rgba(255,255,255,.18);

    backdrop-filter:blur(12px);

    color:#fff;

    font-size:.9rem;

    font-weight:600;

    margin-bottom:18px;

}

/*==================================================
TITLE
==================================================*/

.hero-title{

    color:#fff;

    font-size:3rem;

    font-weight:800;

    margin-bottom:14px;

    line-height:1.2;

}

.hero-description{

    max-width:650px;

    color:rgba(255,255,255,.92);

    line-height:1.8;

    margin-bottom:30px;

}

/*==================================================
SEARCH
==================================================*/

.search-box{

    position:relative;

    max-width:560px;

}

.search-box i{

    position:absolute;

    left:22px;

    top:50%;

    transform:translateY(-50%);

    color:#EC4899;

}

.search-box input{

    width:100%;

    height:58px;

    border:none;

    outline:none;

    border-radius:50px;

    padding:0 20px 0 56px;

    font-size:15px;

    box-shadow:
        0 18px 40px rgba(0,0,0,.12);

}

/*==================================================
STATISTIK
==================================================*/

.statistik-section{

    margin-top:-55px;

    margin-bottom:60px;

}

.statistik-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:22px;

}

.stat-card{

    background:#fff;

    border-radius:22px;

    padding:22px;

    text-align:center;

    box-shadow:
        0 15px 35px rgba(0,0,0,.08);

    transition:.3s;

}

.stat-card:hover{

    transform:translateY(-6px);

}

.stat-icon{

    width:58px;
    height:58px;

    margin:auto auto 16px;

    border-radius:50%;

    background:
        linear-gradient(
            135deg,
            #EC4899,
            #FF7A45
        );

    display:flex;

    justify-content:center;

    align-items:center;

    color:#fff;

    font-size:22px;

}

.stat-card h3{

    margin:0;

    font-size:30px;

    font-weight:800;

    color:#2d3748;

}

.stat-card p{

    margin:5px 0 0;

    color:#6b7280;

    font-size:14px;

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:768px){

.page-hero{

    padding:55px 0 100px;

}

.hero-title{

    font-size:2rem;

}

.hero-description{

    font-size:14px;

}

.statistik-grid{

    grid-template-columns:repeat(2,1fr);

    gap:14px;

}

.search-box input{

    height:52px;

}

}
/*==================================================
SECTION HEADER
==================================================*/

.gedung-section{

    padding:20px 0 80px;

}

.section-header{

    margin-bottom:35px;

}

.section-badge{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:8px 16px;

    border-radius:50px;

    background:#FCE7F3;

    color:#EC4899;

    font-size:13px;

    font-weight:600;

    margin-bottom:12px;

}

.section-header h2{

    font-size:34px;

    font-weight:800;

    margin-bottom:8px;

}

.section-header p{

    color:#6B7280;

}

/*==================================================
GRID
==================================================*/

.gedung-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:22px;

}

/*==================================================
CARD
==================================================*/

.gedung-card{

    background:#fff;

    border-radius:22px;

    overflow:hidden;

    box-shadow:
        0 15px 35px rgba(0,0,0,.08);

    transition:.35s;

}

.gedung-card:hover{

    transform:translateY(-8px);

    box-shadow:
        0 25px 45px rgba(236,72,153,.18);

}

/*==================================================
IMAGE
==================================================*/

.gedung-image{

    position:relative;

    height:170px;

    overflow:hidden;

}

.gedung-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.4s;

}

.gedung-card:hover img{

    transform:scale(1.08);

}

.status-badge{

    position:absolute;

    top:14px;

    right:14px;

    background:#fff;

    color:#EC4899;

    font-size:11px;

    font-weight:700;

    padding:6px 12px;

    border-radius:50px;

}

/*==================================================
BODY
==================================================*/

.gedung-body{

    padding:18px;

}

.gedung-body h5{

    font-size:18px;

    font-weight:700;

    margin-bottom:3px;

}

.gedung-body small{

    display:block;

    color:#6B7280;

    margin-bottom:14px;

    line-height:1.4;

}

/*==================================================
INFO
==================================================*/

.gedung-info{

    display:flex;

    flex-direction:column;

    gap:8px;

    margin-bottom:16px;

}

.gedung-info div{

    display:flex;

    align-items:center;

    gap:8px;

    font-size:14px;

    color:#4B5563;

}

.gedung-info i{

    color:#EC4899;

}

/*==================================================
BUTTON
==================================================*/

.btn-detail{

    display:flex;

    justify-content:center;

    align-items:center;

    gap:8px;

    width:100%;

    height:42px;

    border-radius:50px;

    background:
        linear-gradient(
            135deg,
            #EC4899,
            #FF7A45
        );

    color:#fff;

    text-decoration:none;

    font-size:14px;

    font-weight:600;

    transition:.35s;

}

.btn-detail:hover{

    color:#fff;

    transform:translateY(-2px);

}

.btn-detail i{

    transition:.3s;

}

.btn-detail:hover i{

    transform:translateX(4px);

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:992px){

.gedung-grid{

    grid-template-columns:repeat(2,1fr);

}

}

@media(max-width:768px){

.gedung-grid{

    grid-template-columns:repeat(2,1fr);

    gap:14px;

}

.gedung-image{

    height:120px;

}

.gedung-body{

    padding:14px;

}

.gedung-body h5{

    font-size:15px;

}

.gedung-body small{

    font-size:11px;

    margin-bottom:10px;

}

.gedung-info{

    gap:6px;

}

.gedung-info div{

    font-size:12px;

}

.btn-detail{

    height:38px;

    font-size:12px;

}

.section-header h2{

    font-size:28px;

}

}

</style>

<main class="gedung-page">

<section class="page-hero">

    <div class="container">

        <div class="breadcrumb-custom">

            <a href="<?= $baseUrl ?>index.php">Beranda</a>

            <span>/</span>

            <span>Gedung</span>

        </div>

        <span class="hero-badge">

            <i class="bi bi-buildings-fill"></i>

            Data Gedung

        </span>

        <h1 class="hero-title">

            Semua Gedung

        </h1>

        <p class="hero-description">

            Jelajahi seluruh gedung Politeknik Nest beserta informasi lantai,
            ruangan, dan inventaris yang tersedia.

        </p>

        <div class="search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                placeholder="Cari nama gedung...">

        </div>

    </div>

</section>

<section class="statistik-section">

    <div class="container">

        <div class="statistik-grid">

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-buildings-fill"></i>

                </div>

                <h3><?= $totalGedung ?></h3>

                <p>Gedung</p>

            </div>

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-layers-fill"></i>

                </div>

                <h3><?= $totalLantai ?></h3>

                <p>Lantai</p>

            </div>

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-door-open-fill"></i>

                </div>

                <h3><?= $totalRuangan ?></h3>

                <p>Ruangan</p>

            </div>

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-box-seam-fill"></i>

                </div>

                <h3><?= $totalInventaris ?></h3>

                <p>Inventaris</p>

            </div>

        </div>

    </div>

</section>

<!-- ==================================================
DAFTAR GEDUNG
================================================== -->

<section class="gedung-section">

    <div class="container">

        <div class="section-header">

            <div>

                <span class="section-badge">

                    <i class="bi bi-buildings-fill"></i>

                    Gedung Politeknik Nest

                </span>

                <h2>

                    Daftar Gedung

                </h2>

                <p>

                    Pilih salah satu gedung untuk melihat detail lantai,
                    ruangan dan inventaris yang tersedia.

                </p>

            </div>

        </div>

        <div class="gedung-grid">

            <?php foreach($gedungList as $gedung): ?>

            <div class="gedung-card">

                <div class="gedung-image">

                    <?php if(!empty($gedung['nama_file'])): ?>

                        <img
                            src="<?= $baseUrl ?>assets/uploads/lokasi/<?= $gedung['nama_file']; ?>"
                            alt="<?= htmlspecialchars($gedung['nama_lokasi']); ?>">

                    <?php else: ?>

                        <img
                            src="<?= $baseUrl ?>assets/images/no-image.png"
                            alt="No Image">

                    <?php endif; ?>

                    <span class="status-badge">

                        <?= $gedung['status']; ?>

                    </span>

                </div>

                <div class="gedung-body">

                    <h5>

                        <?= htmlspecialchars($gedung['nama_lokasi']); ?>

                    </h5>

                    <small>

                        <?= htmlspecialchars($gedung['alamat']); ?>

                    </small>

                    <div class="gedung-info">

                        <div>

                            <i class="bi bi-layers-fill"></i>

                            <?= $gedung['jumlah_lantai']; ?> Lantai

                        </div>

                        <div>

                            <i class="bi bi-door-open-fill"></i>

                            <?= $gedung['jumlah_ruangan']; ?> Ruangan

                        </div>

                        <div>

                            <i class="bi bi-box-seam-fill"></i>

                            <?= $gedung['jumlah_inventaris']; ?> Inventaris

                        </div>

                    </div>

                    <a
                        href="detail_gedung.php?id=<?= $gedung['id_lokasi']; ?>"
                        class="btn-detail">

                        Lihat Detail

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

    </div>
</section>

<?php include '../includes/user/footer.php'; ?>

</main>

</body>
</html>