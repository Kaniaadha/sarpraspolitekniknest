<?php

session_start();

require_once "../config/database.php";

$baseUrl = "../";

/*==================================================
VALIDASI ID RUANGAN
==================================================*/

if (!isset($_GET['id'])) {
    header("Location: ruangan.php");
    exit;
}

$idRuangan = (int) $_GET['id'];


/*==================================================
AMBIL DATA RUANGAN
==================================================*/

$sql = "
    SELECT
        r.id_ruangan,
        r.id_lantai,
        r.kode_ruangan,
        r.nama_ruangan,
        r.luas,
        r.kapasitas,
        r.deskripsi,
        r.status AS status_ruangan,

        l.kode_lantai,
        l.nama_lantai,
        l.nomor_lantai,

        lok.id_lokasi,
        lok.kode_lokasi,
        lok.nama_lokasi,
        lok.alamat

    FROM ruangan r

    INNER JOIN lantai l
        ON l.id_lantai = r.id_lantai

    INNER JOIN lokasi lok
        ON lok.id_lokasi = l.id_lokasi

    WHERE
        r.id_ruangan = ?
        AND r.status = 'Aktif'
        AND l.status = 'Aktif'
        AND lok.status = 'Aktif'
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $idRuangan
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$ruangan = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/*==================================================
JIKA RUANGAN TIDAK DITEMUKAN
==================================================*/

if (!$ruangan) {
    header("Location: ruangan.php");
    exit;
}


/*==================================================
AMBIL FOTO COVER
==================================================*/

$sqlCover = "
    SELECT
        nama_file
    FROM foto_ruangan
    WHERE
        id_ruangan = ?
        AND is_cover = 1
    ORDER BY urutan ASC
    LIMIT 1
";

$stmtCover = mysqli_prepare($conn, $sqlCover);

mysqli_stmt_bind_param(
    $stmtCover,
    "i",
    $idRuangan
);

mysqli_stmt_execute($stmtCover);

$resultCover = mysqli_stmt_get_result($stmtCover);

$cover = mysqli_fetch_assoc($resultCover);

mysqli_stmt_close($stmtCover);


/*==================================================
AMBIL GALERI RUANGAN
==================================================*/

$sqlGallery = "
    SELECT
        nama_file,
        is_cover,
        urutan
    FROM foto_ruangan
    WHERE id_ruangan = ?
    ORDER BY
        is_cover DESC,
        urutan ASC,
        id_foto_ruangan ASC
";

$stmtGallery = mysqli_prepare($conn, $sqlGallery);

mysqli_stmt_bind_param(
    $stmtGallery,
    "i",
    $idRuangan
);

mysqli_stmt_execute($stmtGallery);

$resultGallery = mysqli_stmt_get_result($stmtGallery);

$gallery = [];

while ($foto = mysqli_fetch_assoc($resultGallery)) {
    $gallery[] = $foto;
}

mysqli_stmt_close($stmtGallery);


/*==================================================
JUMLAH INVENTARIS RUANGAN
==================================================*/

$sqlInventaris = "
    SELECT COUNT(*) AS total
    FROM inventaris
    WHERE
        id_ruangan = ?
        AND status = 'Aktif'
";

$stmtInventaris = mysqli_prepare($conn, $sqlInventaris);

mysqli_stmt_bind_param(
    $stmtInventaris,
    "i",
    $idRuangan
);

mysqli_stmt_execute($stmtInventaris);

$resultInventaris = mysqli_stmt_get_result($stmtInventaris);

$dataInventaris = mysqli_fetch_assoc($resultInventaris);

$totalInventaris = (int) $dataInventaris['total'];

mysqli_stmt_close($stmtInventaris);

?>

<?php include "../includes/user/header.php"; ?>
<?php
$currentPage = 'ruangan';
?>
<style>

/*==================================================
BODY
==================================================*/

body {
    background: #FFF8FB;
}


/*==================================================
HERO
==================================================*/

.detail-hero {
    padding: 60px 0 40px;
}

.hero-box {

    overflow: hidden;

    border-radius: 35px;

    display: grid;

    grid-template-columns: 1.1fr 1fr;

    align-items: center;

    background:
        linear-gradient(
            135deg,
            #EC4899,
            #FF7A45
        );

    min-height: 430px;
}


/*==================================================
GENERAL SECTION
==================================================*/

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


/*==================================================
INFO CARD
==================================================*/

.info-grid {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 22px;

}

.info-card {

    background: #fff;

    border-radius: 22px;

    padding: 28px 22px;

    text-align: center;

    box-shadow:
        0 10px 25px rgba(0,0,0,.06);

    transition: .3s;

}

.info-card:hover {

    transform:
        translateY(-5px);

}

.info-icon {

    width: 68px;

    height: 68px;

    margin:
        auto auto 15px;

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

    line-height: 1.6;

    font-size: 15px;

}


/*==================================================
GALERI
==================================================*/

.gallery-wrapper {

    display: flex;

    overflow-x: auto;

    gap: 18px;

    scroll-behavior: smooth;

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

    box-shadow:
        0 10px 22px rgba(0,0,0,.08);

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


/*==================================================
STATISTIK
==================================================*/

.stat-grid {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 22px;

}

.stat-card {

    background: #fff;

    border-radius: 22px;

    padding: 28px;

    text-align: center;

    box-shadow:
        0 10px 25px rgba(0,0,0,.06);

}

.stat-icon {

    width: 70px;

    height: 70px;

    margin:
        auto auto 16px;

    border-radius: 18px;

    background:
        linear-gradient(
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

    font-size: 36px;

    color: #2D3748;

    font-weight: 800;

}

.stat-card span {

    color: #6B7280;

}


/*==================================================
HERO TEXT
==================================================*/

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

    opacity: .95;

    max-width: 520px;

}

.hero-location {

    display: flex;

    align-items: center;

    gap: 10px;

    font-size: 17px;

    font-weight: 500;

}

.hero-location i {

    font-size: 20px;

}


/*==================================================
KODE RUANGAN
==================================================*/

.hero-code {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 9px 16px;

    margin-bottom: 18px;

    border-radius: 50px;

    background: rgba(255,255,255,.18);

    color: #fff;

    font-size: 14px;

    font-weight: 600;

}


/*==================================================
HERO IMAGE
==================================================*/

.hero-image {

    height: 100%;

}

.hero-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;

}


/*==================================================
EMPTY GALLERY
==================================================*/

.gallery-empty {

    background: #fff;

    border-radius: 22px;

    padding: 40px;

    text-align: center;

    color: #6B7280;

    box-shadow:
        0 10px 25px rgba(0,0,0,.06);

}

.gallery-empty i {

    display: block;

    font-size: 40px;

    color: #EC4899;

    margin-bottom: 10px;

}


/*==================================================
RESPONSIVE
==================================================*/

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

        grid-template-columns:
            repeat(2, 1fr);

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


<!-- ==================================================
HERO RUANGAN
================================================== -->

<section class="detail-hero">

    <div class="container">

        <div class="hero-box">

            <!-- KIRI -->

            <div class="hero-content">

                <div class="breadcrumb-custom">

                    <a href="../index.php">
                        Beranda
                    </a>

                    &nbsp;/&nbsp;

                    <a href="ruangan.php">
                        Ruangan
                    </a>

                    &nbsp;/&nbsp;

                    <?= htmlspecialchars(
                        $ruangan['nama_ruangan']
                    ); ?>

                </div>


                <div class="hero-code">

                    <i class="bi bi-door-open-fill"></i>

                    <?= htmlspecialchars(
                        $ruangan['kode_ruangan']
                    ); ?>

                </div>


                <h1 class="hero-title">

                    <?= htmlspecialchars(
                        $ruangan['nama_ruangan']
                    ); ?>

                </h1>


                <p class="hero-description">

                    <?php

                    if (
                        !empty(
                            trim($ruangan['deskripsi'])
                        )
                    ) {

                        echo nl2br(
                            htmlspecialchars(
                                $ruangan['deskripsi']
                            )
                        );

                    } else {

                        echo "Informasi ruangan Politeknik Nest.";

                    }

                    ?>

                </p>


                <div class="hero-location">

                    <i class="bi bi-geo-alt-fill"></i>

                    <?= htmlspecialchars(
                        $ruangan['nama_lokasi']
                    ); ?>

                    &nbsp;•&nbsp;

                    <?= htmlspecialchars(
                        $ruangan['nama_lantai']
                    ); ?>

                </div>

            </div>


            <!-- KANAN -->

            <div class="hero-image">

                <?php if ($cover) : ?>

                    <img
                        src="<?= $baseUrl; ?>assets/uploads/ruangan/<?= htmlspecialchars($cover['nama_file']); ?>"
                        alt="<?= htmlspecialchars($ruangan['nama_ruangan']); ?>"
                    >

                <?php else : ?>

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


            <!-- KODE RUANGAN -->

            <div class="info-card">

                <div class="info-icon">

                    <i class="bi bi-upc-scan"></i>

                </div>

                <h5>
                    Kode Ruangan
                </h5>

                <p>

                    <?= htmlspecialchars(
                        $ruangan['kode_ruangan']
                    ); ?>

                </p>

            </div>


            <!-- GEDUNG -->

            <div class="info-card">

                <div class="info-icon">

                    <i class="bi bi-building"></i>

                </div>

                <h5>
                    Gedung
                </h5>

                <p>

                    <?= htmlspecialchars(
                        $ruangan['nama_lokasi']
                    ); ?>

                </p>

            </div>


            <!-- LANTAI -->

            <div class="info-card">

                <div class="info-icon">

                    <i class="bi bi-layers-fill"></i>

                </div>

                <h5>
                    Lantai
                </h5>

                <p>

                    <?= htmlspecialchars(
                        $ruangan['nama_lantai']
                    ); ?>

                </p>

            </div>


            <!-- STATUS -->

            <div class="info-card">

                <div class="info-icon">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <h5>
                    Status
                </h5>

                <p>

                    <?= htmlspecialchars(
                        $ruangan['status_ruangan']
                    ); ?>

                </p>

            </div>


        </div>

    </div>

</section>



<!-- ==================================================
GALERI RUANGAN
================================================== -->

<section class="detail-section">

    <div class="container">

        <div class="section-title">

            <i class="bi bi-images"></i>

            <h2>
                Galeri Ruangan
            </h2>

        </div>


                <?php if (!empty($gallery)) : ?>

                <div class="gallery-wrapper">

                    <?php foreach ($gallery as $index => $foto) : ?>

                        <div
                            class="gallery-item"
                            data-gallery-index="<?= $index; ?>"
                        >

                            <img
                                src="<?= $baseUrl; ?>assets/uploads/ruangan/<?= htmlspecialchars($foto['nama_file']); ?>"
                                alt="<?= htmlspecialchars($ruangan['nama_ruangan']); ?>"
                                loading="lazy"
                            >

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else : ?>

            <div class="gallery-empty">

                <i class="bi bi-images"></i>

                Belum ada foto untuk ruangan ini.

            </div>

        <?php endif; ?>

    </div>

</section>



<!-- ==================================================
STATISTIK RUANGAN
================================================== -->

<section class="detail-section">

    <div class="container">

        <div class="section-title">

            <i class="bi bi-bar-chart"></i>

            <h2>
                Statistik Ruangan
            </h2>

        </div>


        <div class="stat-grid">


            <!-- KAPASITAS -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-people-fill"></i>

                </div>

                <h3>

                    <?= (int)
                        $ruangan['kapasitas'];
                    ?>

                </h3>

                <span>
                    Kapasitas (Orang)
                </span>

            </div>


            <!-- LUAS -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-rulers"></i>

                </div>

                <h3>

                    <?= htmlspecialchars(
                        $ruangan['luas'] ?? 0
                    ); ?>

                </h3>

                <span>
                    Luas (m²)
                </span>

            </div>


            <!-- INVENTARIS -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-box-seam"></i>

                </div>

                <h3>

                    <?= $totalInventaris; ?>

                </h3>

                <span>
                    Inventaris
                </span>

            </div>


            <!-- STATUS -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-check-circle"></i>

                </div>

                <h3 style="font-size:28px;">

                    <?= htmlspecialchars(
                        $ruangan['status_ruangan']
                    ); ?>

                </h3>

                <span>
                    Status Ruangan
                </span>

            </div>


        </div>

    </div>

</section>
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
            alt="Gallery Ruangan"
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


<!-- ==================================================
     GALLERY LIGHTBOX SCRIPT
================================================== -->

<script>

document.addEventListener("DOMContentLoaded", function () {

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
        Array.from(galleryItems)
            .map(function (item) {

                const image =
                    item.querySelector("img");

                return image
                    ? image.src
                    : "";

            })
            .filter(Boolean);


    /* ==================================================
       TAMPILKAN FOTO
    ================================================== */

    function showImage(index) {

        if (images.length === 0) {
            return;
        }


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


        document.body.style.overflow =
            "hidden";

    }


    /* ==================================================
       TUTUP POPUP
    ================================================== */

    function closeLightbox() {

        lightbox.classList.remove("active");

        lightboxImage.src = "";

        document.body.style.overflow = "";

    }


    /* ==================================================
       NEXT / PREVIOUS
    ================================================== */

    function nextImage() {

        showImage(
            currentIndex + 1
        );

    }


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

            if (event.target === lightbox) {

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


            if (event.key === "Escape") {

                closeLightbox();

            }


            if (event.key === "ArrowRight") {

                nextImage();

            }


            if (event.key === "ArrowLeft") {

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


            if (
                Math.abs(swipeDistance) < 50
            ) {

                return;

            }


            if (swipeDistance < 0) {

                nextImage();

            } else {

                prevImage();

            }

        },
        {
            passive: true
        }
    );

});

</script>


<?php include "../includes/user/footer.php"; ?>
<?php include "../includes/user/footer.php"; ?>


</body>

</html>