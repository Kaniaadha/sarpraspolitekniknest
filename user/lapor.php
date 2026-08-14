<?php
session_start();

$baseUrl = "../";
$currentPage = 'lapor';
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../includes/user/header.php'; ?>

<body>

<?php include '../includes/user/navbar.php'; ?>

<style>
/* ==========================================
   PAGE
========================================== */

.lapor-page{
    background:#fff8fc;
    min-height:100vh;
}

/* ==========================================
   HERO
========================================== */

.lapor-hero{
    position:relative;
    overflow:hidden;
    background:linear-gradient(135deg,#EC4899 0%,#FF7A45 100%);
    padding:65px 0 75px;
}

.lapor-hero::before{
    content:"";
    position:absolute;
    width:420px;
    height:420px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    top:-180px;
    right:-120px;
}

.lapor-hero::after{
    content:"";
    position:absolute;
    width:260px;
    height:260px;
    border-radius:50%;
    background:rgba(255,255,255,.06);
    left:-80px;
    bottom:-150px;
}

.lapor-hero .container{
    position:relative;
    z-index:2;
}

/* ==========================================
   BREADCRUMB
========================================== */

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

/* ==========================================
   HERO CONTENT
========================================== */

.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding:10px 18px;
    border-radius:50px;
    background:rgba(255,255,255,.18);
    color:#fff;
    font-size:.9rem;
    font-weight:600;
    margin-bottom:18px;
}

.hero-title{
    color:#fff;
    font-size:3rem;
    font-weight:800;
    margin-bottom:14px;
    line-height:1.2;
}

.hero-description{
    max-width:700px;
    color:rgba(255,255,255,.92);
    line-height:1.8;
    margin-bottom:0;
}

/* ==========================================
   PILIHAN LAPORAN
========================================== */

.lapor-section{
    padding:50px 0 80px;
}

.section-title{
    text-align:center;
    margin-bottom:35px;
}

.section-title h2{
    color:#374151;
    font-size:26px;
    font-weight:800;
    margin-bottom:8px;
}

.section-title p{
    color:#6B7280;
    margin:0;
    font-size:14px;
}

/* ==========================================
   CARD
========================================== */

.jenis-lapor-card{
    height:100%;
    background:#fff;
    border-radius:22px;
    padding:32px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
    text-align:center;
    transition:.3s;
    border:1px solid transparent;
}

.jenis-lapor-card:hover{
    transform:translateY(-5px);
    border-color:#FCE7F3;
    box-shadow:0 15px 35px rgba(236,72,153,.10);
}

.jenis-icon{
    width:75px;
    height:75px;
    margin:0 auto 20px;
    border-radius:22px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:32px;
}

.icon-kerusakan{
    background:#FFF1F6;
    color:#EC4899;
}

.icon-kehilangan{
    background:#FFF4ED;
    color:#FF7A45;
}

.jenis-lapor-card h3{
    color:#374151;
    font-size:21px;
    font-weight:800;
    margin-bottom:10px;
}

.jenis-lapor-card p{
    color:#6B7280;
    font-size:14px;
    line-height:1.7;
    min-height:48px;
    margin-bottom:25px;
}

/* ==========================================
   BUTTON
========================================== */

.btn-pilih-lapor{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    min-width:150px;
    padding:12px 25px;
    border-radius:50px;
    background:linear-gradient(135deg,#EC4899,#FF7A45);
    color:#fff;
    text-decoration:none;
    font-size:14px;
    font-weight:700;
    transition:.3s;
}

.btn-pilih-lapor:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(236,72,153,.20);
}

/* ==========================================
   RESPONSIVE
========================================== */

@media(max-width:768px){

    .lapor-hero{
        padding:50px 0 60px;
    }

    .hero-title{
        font-size:40px;
    }

    .lapor-section{
        padding:40px 0 60px;
    }

}

@media(max-width:576px){

    .hero-title{
        font-size:32px;
    }

    .jenis-lapor-card{
        padding:25px 20px;
    }

}
.popup-sisarpas {
    border-radius: 24px !important;
    padding: 32px !important;
    font-family: inherit !important;
}
.popup-title {
    color: #000000 !important;
    font-weight: 700 !important;
}
.popup-button {
    border-radius: 50px !important;
    padding: 12px 38px !important;
    background: linear-gradient(135deg, #EC4899, #FF7A45) !important;
    color: #fff !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    border: 3px solid #374151 !important;
    box-shadow: none !important;
    outline: none !important;
}
.popup-button:focus {
    border: 3px solid #374151 !important;
    box-shadow: none !important;
    outline: none !important;
}
</style>

<main class="lapor-page">

    <!-- ==========================================
         HERO
    ========================================== -->

    <section class="lapor-hero">
        <div class="container">

            <div class="breadcrumb-custom">
                <a href="<?= $baseUrl ?>index.php">
                    Beranda
                </a>

                <span>/</span>

                <span>Lapor</span>
            </div>

            <span class="hero-badge">
                <i class="bi bi-megaphone-fill"></i>
                Layanan Pelaporan
            </span>

            <h1 class="hero-title">
                Lapor
            </h1>

            <p class="hero-description">
                Sampaikan laporan terkait sarana dan prasarana
                Politeknik Nest agar dapat segera ditindaklanjuti.
            </p>

        </div>
    </section>

    <!-- ==========================================
         PILIH JENIS LAPORAN
    ========================================== -->

    <section class="lapor-section">
        <div class="container">

            <div class="section-title">
                <h2>Pilih Jenis Laporan</h2>

                <p>
                    Pilih jenis laporan yang ingin kamu sampaikan.
                </p>
            </div>

            <div class="row g-4 justify-content-center">

                <!-- ==========================================
                     LAPOR KERUSAKAN
                ========================================== -->

                <div class="col-md-6 col-lg-5">

                    <div class="jenis-lapor-card">

                        <div class="jenis-icon icon-kerusakan">
                            <i class="bi bi-tools"></i>
                        </div>

                        <h3>
                            Lapor Kerusakan
                        </h3>

                        <p>
                            Laporkan barang atau fasilitas yang
                            mengalami kerusakan.
                        </p>

                        <a
                            href="lapor_kerusakan.php"
                            class="btn-pilih-lapor">

                            Lapor Kerusakan
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

                <!-- ==========================================
                     LAPOR KEHILANGAN
                ========================================== -->

                <div class="col-md-6 col-lg-5">

                    <div class="jenis-lapor-card">

                        <div class="jenis-icon icon-kehilangan">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                        <h3>
                            Lapor Kehilangan
                        </h3>

                        <p>
                            Laporkan barang atau fasilitas yang
                            mengalami kehilangan.
                        </p>

                        <a
                            href="lapor_kehilangan.php"
                            class="btn-pilih-lapor">

                            Lapor Kehilangan
                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>
    </section>

</main>

<?php include '../includes/user/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['user_success'])) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Laporan Berhasil!',
    text: <?= json_encode($_SESSION['user_success']) ?>,
    confirmButtonText: 'OK',
    background: '#fff',
    customClass: {
        popup: 'popup-sisarpas',
        title: 'popup-title',
        confirmButton: 'popup-button'
    }
});
</script>
<?php unset($_SESSION['user_success']); endif; ?>

</body>
</html>