<?php

session_start();

require_once "../config/database.php";

$baseUrl = "../";


/* ==================================================
   VALIDASI ID
================================================== */

if (!isset($_GET['id'])) {
    header("Location: public_space.php");
    exit;
}

$idPublicSpace = (int) $_GET['id'];


/* ==================================================
   AMBIL DATA PUBLIC SPACE
================================================== */

$sql = "
    SELECT
        p.id_public_space,
        p.id_lantai,
        p.kode_public_space,
        p.nama_public_space,
        p.luas,
        p.deskripsi,
        p.status AS status_public_space,

        l.kode_lantai,
        l.nama_lantai,

        lok.kode_lokasi,
        lok.nama_lokasi,
        lok.alamat

    FROM public_space p

    INNER JOIN lantai l
        ON l.id_lantai = p.id_lantai

    INNER JOIN lokasi lok
        ON lok.id_lokasi = l.id_lokasi

    WHERE
        p.id_public_space = ?
        AND p.status = 'Aktif'
        AND l.status = 'Aktif'
        AND lok.status = 'Aktif'
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $idPublicSpace
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$publicSpace = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* ==================================================
   JIKA TIDAK DITEMUKAN
================================================== */

if (!$publicSpace) {
    header("Location: public_space.php");
    exit;
}


/* ==================================================
   FOTO COVER
================================================== */

$sqlCover = "
    SELECT nama_file
    FROM foto_public_space
    WHERE
        id_public_space = ?
        AND is_cover = 1
    ORDER BY urutan ASC
    LIMIT 1
";

$stmtCover = mysqli_prepare($conn, $sqlCover);

mysqli_stmt_bind_param(
    $stmtCover,
    "i",
    $idPublicSpace
);

mysqli_stmt_execute($stmtCover);

$resultCover = mysqli_stmt_get_result($stmtCover);

$cover = mysqli_fetch_assoc($resultCover);

mysqli_stmt_close($stmtCover);


/* ==================================================
   GALERI
================================================== */

$sqlGallery = "
    SELECT
        nama_file,
        is_cover,
        urutan
    FROM foto_public_space
    WHERE id_public_space = ?
    ORDER BY
        is_cover DESC,
        urutan ASC
";

$stmtGallery = mysqli_prepare($conn, $sqlGallery);

mysqli_stmt_bind_param(
    $stmtGallery,
    "i",
    $idPublicSpace
);

mysqli_stmt_execute($stmtGallery);

$resultGallery = mysqli_stmt_get_result($stmtGallery);

$gallery = [];

while ($foto = mysqli_fetch_assoc($resultGallery)) {
    $gallery[] = $foto;
}

mysqli_stmt_close($stmtGallery);

?>

<!DOCTYPE html>
<html lang="id">

<?php include "../includes/user/header.php"; ?>
<?php
$currentPage = 'public_space';
?>
<style>

body {
    background: #FFF8FB;
}


/* ==================================================
   HERO
================================================== */

.detail-hero {
    padding: 60px 0 40px;
}

.hero-box {
    overflow: hidden;
    border-radius: 35px;
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    align-items: center;
    background: linear-gradient(
        135deg,
        #EC4899,
        #FF7A45
    );
    min-height: 430px;
}

.hero-content {
    color: white;
    padding: 60px;
}

.breadcrumb-custom {
    font-size: 14px;
    margin-bottom: 20px;
    opacity: .9;
}

.breadcrumb-custom a {
    color: white;
    text-decoration: none;
}

.hero-code {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    margin-bottom: 18px;
    border-radius: 50px;
    background: rgba(255,255,255,.18);
    color: white;
    font-size: 14px;
    font-weight: 600;
}

.hero-title {
    font-size: 52px;
    font-weight: 800;
    margin-bottom: 18px;
    line-height: 1.1;
}

.hero-description {
    font-size: 18px;
    line-height: 1.8;
    margin-bottom: 30px;
    max-width: 520px;
}

.hero-location {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 17px;
}

.hero-image {
    height: 100%;
}

.hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


/* ==================================================
   SECTION
================================================== */

.detail-section {
    padding: 35px 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
}

.section-title i {
    color: #EC4899;
    font-size: 24px;
}

.section-title h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #2D3748;
}


/* ==================================================
   INFO CARD
================================================== */

.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.info-card {
    background: #fff;
    border-radius: 22px;
    padding: 28px 22px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,.06);
    transition: .3s;
}

.info-card:hover {
    transform: translateY(-5px);
}

.info-icon {
    width: 68px;
    height: 68px;
    margin: auto auto 15px;
    border-radius: 18px;
    background: #FFF1F6;
    color: #EC4899;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.info-card h5 {
    margin-bottom: 10px;
    font-weight: 700;
    color: #2D3748;
}

.info-card p {
    margin: 0;
    color: #6B7280;
    font-size: 15px;
    line-height: 1.6;
}


/* ==================================================
   GALERI
================================================== */

.gallery-wrapper {
    display: flex;
    overflow-x: auto;
    gap: 18px;
    padding-bottom: 8px;
}

.gallery-wrapper::-webkit-scrollbar {
    height: 8px;
}

.gallery-wrapper::-webkit-scrollbar-thumb {
    background: #F8A7C9;
    border-radius: 50px;
}

.gallery-item {
    min-width: 260px;
    height: 180px;
    border-radius: 22px;
    overflow: hidden;
    flex-shrink: 0;
    box-shadow: 0 10px 22px rgba(0,0,0,.08);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: .35s;
}

.gallery-item:hover img {
    transform: scale(1.08);
}

.gallery-empty {
    background: #fff;
    border-radius: 22px;
    padding: 40px;
    text-align: center;
    color: #6B7280;
}

.gallery-empty i {
    display: block;
    font-size: 40px;
    color: #EC4899;
    margin-bottom: 10px;
}


/* ==================================================
   STATISTIK
================================================== */

.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.stat-card {
    background: #fff;
    border-radius: 22px;
    padding: 28px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,.06);
}

.stat-icon {
    width: 70px;
    height: 70px;
    margin: auto auto 16px;
    border-radius: 18px;
    background: linear-gradient(
        135deg,
        #EC4899,
        #FF7A45
    );
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.stat-card h3 {
    margin: 0;
    font-size: 34px;
    color: #2D3748;
    font-weight: 800;
}

.stat-card span {
    color: #6B7280;
}


/* ==================================================
   RESPONSIVE
================================================== */

@media(max-width: 992px) {

    .hero-box {
        grid-template-columns: 1fr;
    }

    .hero-content {
        padding: 40px 30px;
    }

    .hero-title {
        font-size: 40px;
    }

    .hero-image {
        height: 320px;
    }

    .info-grid,
    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 768px) {

    .info-grid,
    .stat-grid {
        grid-template-columns: 1fr;
    }

    .gallery-item {
        min-width: 220px;
        height: 160px;
    }

    .hero-title {
        font-size: 34px;
    }
}
/* ==================================================
   RESPONSIVE MOBILE - 2 CARD SEBARIS
================================================== */

@media (max-width: 576px) {

    .info-grid,
    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    /* INFO CARD */

    .info-card {
        border-radius: 16px;
        padding: 18px 12px;
    }

    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .info-card h5 {
        font-size: 12px;
        line-height: 1.2;
        margin-bottom: 5px;
    }

    .info-card p {
        font-size: 10px;
        line-height: 1.3;
        word-break: break-word;
    }

    /* STAT CARD */

    .stat-card {
        border-radius: 16px;
        padding: 18px 12px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .stat-card h3 {
        font-size: 24px;
        line-height: 1.2;
    }

    .stat-card span {
        font-size: 10px;
        line-height: 1.3;
    }

}
</style>

</head>

<body>

<?php include "../includes/user/navbar.php"; ?>


<!-- ==================================================
     HERO
================================================== -->

<section class="detail-hero">

    <div class="container">

        <div class="hero-box">

            <div class="hero-content">

                <div class="breadcrumb-custom">

                    <a href="../index.php">
                        Beranda
                    </a>

                    &nbsp;/&nbsp;

                    <a href="public_space.php">
                        Public Space
                    </a>

                    &nbsp;/&nbsp;

                    <?= htmlspecialchars(
                        $publicSpace['nama_public_space']
                    ); ?>

                </div>


                <div class="hero-code">

                    <i class="bi bi-geo-alt-fill"></i>

                    <?= htmlspecialchars(
                        $publicSpace['kode_public_space']
                    ); ?>

                </div>


                <h1 class="hero-title">

                    <?= htmlspecialchars(
                        $publicSpace['nama_public_space']
                    ); ?>

                </h1>


                <p class="hero-description">

                    <?php if (
                        !empty(
                            trim(
                                $publicSpace['deskripsi']
                            )
                        )
                    ): ?>

                        <?= nl2br(
                            htmlspecialchars(
                                $publicSpace['deskripsi']
                            )
                        ); ?>

                    <?php else: ?>

                        Informasi public space
                        Politeknik Nest.

                    <?php endif; ?>

                </p>


                <div class="hero-location">

                    <i class="bi bi-geo-alt-fill"></i>

                    <?= htmlspecialchars(
                        $publicSpace['nama_lokasi']
                    ); ?>

                    &nbsp;•&nbsp;

                    <?= htmlspecialchars(
                        $publicSpace['nama_lantai']
                    ); ?>

                </div>

            </div>


            <div class="hero-image">

                <?php if ($cover): ?>

                    <img
                        src="<?= $baseUrl; ?>assets/uploads/public_space/<?= htmlspecialchars($cover['nama_file']); ?>"
                        alt="<?= htmlspecialchars(
                            $publicSpace['nama_public_space']
                        ); ?>"
                    >

                <?php else: ?>

                    <img
                        src="<?= $baseUrl; ?>assets/img/no-image.png"
                        alt="No Image"
                    >

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>



<!-- ==================================================
     INFORMASI SINGKAT
================================================== -->

<section class="detail-section">

    <div class="container">

        <div class="section-title">

            <i class="bi bi-card-list"></i>

            <h2>
                Informasi Singkat
            </h2>

        </div>


        <div class="info-grid">


            <div class="info-card">

                <div class="info-icon">
                    <i class="bi bi-upc-scan"></i>
                </div>

                <h5>
                    Kode Public Space
                </h5>

                <p>
                    <?= htmlspecialchars(
                        $publicSpace['kode_public_space']
                    ); ?>
                </p>

            </div>


            <div class="info-card">

                <div class="info-icon">
                    <i class="bi bi-building"></i>
                </div>

                <h5>
                    Gedung
                </h5>

                <p>
                    <?= htmlspecialchars(
                        $publicSpace['nama_lokasi']
                    ); ?>
                </p>

            </div>


            <div class="info-card">

                <div class="info-icon">
                    <i class="bi bi-layers-fill"></i>
                </div>

                <h5>
                    Lantai
                </h5>

                <p>
                    <?= htmlspecialchars(
                        $publicSpace['nama_lantai']
                    ); ?>
                </p>

            </div>


            <div class="info-card">

                <div class="info-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <h5>
                    Status
                </h5>

                <p>
                    <?= htmlspecialchars(
                        $publicSpace['status_public_space']
                    ); ?>
                </p>

            </div>


        </div>

    </div>

</section>



<!-- ==================================================
     GALERI PUBLIC SPACE
================================================== -->

<section class="detail-section">

    <div class="container">

        <div class="section-title">

            <i class="bi bi-images"></i>

            <h2>
                Galeri Public Space
            </h2>

        </div>


        <?php if (!empty($gallery)): ?>

            <div class="gallery-wrapper">

                <?php foreach ($gallery as $foto): ?>

                    <div class="gallery-item">

                        <img
                            src="<?= $baseUrl; ?>assets/uploads/public_space/<?= htmlspecialchars($foto['nama_file']); ?>"
                            alt="<?= htmlspecialchars(
                                $publicSpace['nama_public_space']
                            ); ?>"
                        >

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="gallery-empty">

                <i class="bi bi-images"></i>

                Belum ada foto untuk public space ini.

            </div>

        <?php endif; ?>

    </div>

</section>



<!-- ==================================================
     STATISTIK PUBLIC SPACE
================================================== -->

<section class="detail-section">

    <div class="container">

        <div class="section-title">

            <i class="bi bi-bar-chart"></i>

            <h2>
                Statistik Public Space
            </h2>

        </div>


        <div class="stat-grid">


            <!-- LUAS -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-rulers"></i>

                </div>

                <h3>

                    <?= htmlspecialchars(
                        $publicSpace['luas'] ?? 0
                    ); ?>

                </h3>

                <span>
                    Luas (m²)
                </span>

            </div>


            <!-- LANTAI -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-layers-fill"></i>

                </div>

                <h3 style="font-size:28px;">

                    <?= htmlspecialchars(
                        $publicSpace['kode_lantai']
                    ); ?>

                </h3>

                <span>
                    Kode Lantai
                </span>

            </div>


            <!-- FOTO -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-images"></i>

                </div>

                <h3>

                    <?= count($gallery); ?>

                </h3>

                <span>
                    Foto
                </span>

            </div>


            <!-- STATUS -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-check-circle"></i>

                </div>

                <h3 style="font-size:28px;">

                    <?= htmlspecialchars(
                        $publicSpace['status_public_space']
                    ); ?>

                </h3>

                <span>
                    Status
                </span>

            </div>


        </div>

    </div>

</section>


<?php include "../includes/user/footer.php"; ?>

</body>

</html>