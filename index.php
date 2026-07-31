<?php
require_once 'config/database.php';

/* ==========================================================
|  QUERY BANNER
========================================================== */

$banners = [];

$sqlBanner = "
    SELECT
        b.id_banner,
        b.judul,
        b.subjudul,
        b.deskripsi,
        fb.nama_file
    FROM banner b
    LEFT JOIN foto_banner fb
        ON b.id_banner = fb.id_banner
    WHERE b.status = 'Aktif'
    ORDER BY
        b.id_banner ASC,
        fb.urutan ASC
";

$resultBanner = mysqli_query($conn, $sqlBanner);

if ($resultBanner) {
    while ($row = mysqli_fetch_assoc($resultBanner)) {
        $banners[] = $row;
    }
}

/* ==========================================================
|  QUERY STATISTIK
========================================================== */

$totalGedung      = 0;
$totalRuangan     = 0;
$totalInventaris  = 0;
$totalPublicSpace = 0;
$totalPeminjaman  = 0;
$totalKerusakan   = 0;


/* ==========================================================
|  TOTAL GEDUNG
========================================================== */

$q = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM lokasi
    WHERE status = 'Aktif'
    "
);

if ($q) {
    $totalGedung = mysqli_fetch_assoc($q)['total'];
}


/* ==========================================================
|  TOTAL RUANGAN
========================================================== */

$q = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM ruangan
    WHERE status = 'Aktif'
    "
);

if ($q) {
    $totalRuangan = mysqli_fetch_assoc($q)['total'];
}


/* ==========================================================
|  TOTAL INVENTARIS
========================================================== */

$q = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM inventaris
    WHERE status = 'Aktif'
    "
);

if ($q) {
    $totalInventaris = mysqli_fetch_assoc($q)['total'];
}


/* ==========================================================
|  TOTAL PUBLIC SPACE
========================================================== */

$q = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM public_space
    WHERE status = 'Aktif'
    "
);

if ($q) {
    $totalPublicSpace = mysqli_fetch_assoc($q)['total'];
}


/* ==========================================================
|  TOTAL PEMINJAMAN
========================================================== */

$q = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM peminjaman
    "
);

if ($q) {
    $totalPeminjaman = mysqli_fetch_assoc($q)['total'];
}


/* ==========================================================
|  TOTAL KERUSAKAN
========================================================== */

$q = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM kerusakan
    "
);

if ($q) {
    $totalKerusakan = mysqli_fetch_assoc($q)['total'];
}
/* ==========================================================
|  QUERY GEDUNG
========================================================== */

$gedung = [];

$sqlGedung = "
    SELECT
        l.id_lokasi,
        l.kode_lokasi,
        l.nama_lokasi,
        l.alamat,
        l.deskripsi,
        fl.nama_file
    FROM lokasi l
    LEFT JOIN foto_lokasi fl
        ON l.id_lokasi = fl.id_lokasi
        AND fl.is_cover = 1
    WHERE l.status = 'Aktif'
    ORDER BY l.nama_lokasi ASC
    LIMIT 4
";

$resultGedung = mysqli_query($conn, $sqlGedung);

if ($resultGedung) {
    while ($row = mysqli_fetch_assoc($resultGedung)) {
        $gedung[] = $row;
    }
}

/* ==========================================================
   QUERY RUANGAN
========================================================== */

$ruangan = [];

$sqlRuangan = "
    SELECT
        r.id_ruangan,
        r.kode_ruangan,
        r.nama_ruangan,
        r.luas,
        r.kapasitas,
        r.deskripsi,
        l.nama_lantai,
        lk.nama_lokasi,
        fr.nama_file
    FROM ruangan r

    LEFT JOIN lantai l
        ON r.id_lantai = l.id_lantai

    LEFT JOIN lokasi lk
        ON l.id_lokasi = lk.id_lokasi

    LEFT JOIN foto_ruangan fr
        ON r.id_ruangan = fr.id_ruangan
        AND fr.is_cover = 1

    WHERE r.status='Aktif'

    ORDER BY
        r.nama_ruangan ASC

    LIMIT 4
";

$resultRuangan = mysqli_query($conn, $sqlRuangan);

if ($resultRuangan) {

    while ($row = mysqli_fetch_assoc($resultRuangan)) {

        $ruangan[] = $row;

    }

}

/* ==========================================================
|  QUERY PUBLIC SPACE
========================================================== */

$publicSpace = [];

$sqlPublic = "
    SELECT
        p.id_public_space,
        p.kode_public_space,
        p.nama_public_space,
        p.luas,
        p.deskripsi,
        fp.nama_file
    FROM public_space p
    LEFT JOIN foto_public_space fp
        ON p.id_public_space = fp.id_public_space
        AND fp.is_cover = 1
    WHERE p.status = 'Aktif'
    ORDER BY p.nama_public_space ASC
    LIMIT 4
";

$resultPublic = mysqli_query($conn, $sqlPublic);

if ($resultPublic) {
    while ($row = mysqli_fetch_assoc($resultPublic)) {
        $publicSpace[] = $row;
    }
}


/* ==========================================================
|  QUERY INVENTARIS
========================================================== */

$inventaris = [];

$sqlInventaris = "
    SELECT
        i.id_inventaris,
        i.kode_inventaris,
        i.nama_barang,
        i.merk,
        i.spesifikasi,
        i.jumlah,
        i.kondisi,
        i.foto,
        k.nama_kategori
    FROM inventaris i
    LEFT JOIN kategori k
        ON i.id_kategori = k.id_kategori
    WHERE i.status = 'Aktif'
    ORDER BY i.nama_barang ASC
    LIMIT 4
";

$resultInventaris = mysqli_query($conn, $sqlInventaris);

if ($resultInventaris) {
    while ($row = mysqli_fetch_assoc($resultInventaris)) {
        $inventaris[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        SISARPRAS | Politeknik Nest
    </title>

    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Google Font -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icon -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <style>

                :root {
            --primary: #E95B94;
            --secondary: #FF9F43;
            --dark: #243447;
            --light: #F8FAFC;
            --white: #FFFFFF;
            --gray: #6C757D;
            --shadow: 0 10px 30px rgba(0, 0, 0, .08);
            --transition: .3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #F7F8FC;
            color: #333;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        section {
            position: relative;
        }

        .section {
            padding: 80px 0;
        }

        .section-title {
            font-size: 34px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .section-subtitle {
            color: var(--gray);
            font-size: 16px;
        }

        /* ==========================================================
            NAVBAR
        ========================================================== */

        .navbar {
            background: rgba(255, 255, 255, .96);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, .05);
            padding: 14px 0;
            transition: var(--transition);
            z-index: 999;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary) !important;
        }

        .navbar-brand span {
            color: var(--secondary);
        }

        .navbar-nav .nav-link {
            color: #444 !important;
            font-weight: 500;
            margin-left: 16px;
            transition: var(--transition);
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary) !important;
        }

        .btn-login {
            background: var(--primary);
            color: #FFF;
            border-radius: 10px;
            padding: 10px 24px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-login:hover {
            background: #CF3D79;
            color: #FFF;
        }

        /* ==========================================================
            HERO
        ========================================================== */

        .hero {
            margin-top: 90px;
            margin-bottom: 70px;
        }

        .carousel-item {
            height: 560px;
            border-radius: 24px;
            overflow: hidden;
            position: relative;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                rgba(0, 0, 0, .60),
                rgba(0, 0, 0, .45)
            );
        }

        .carousel-caption {
            text-align: left;
            bottom: 18%;
        }

        .carousel-caption h1 {
            font-size: 52px;
            font-weight: 700;
            line-height: 1.25;
        }

        .carousel-caption p {
            max-width: 700px;
            font-size: 18px;
            margin: 20px 0 28px;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: #FFF;
            border: none;
            padding: 13px 28px;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary-custom:hover {
            background: #CF3D79;
            color: #FFF;
        }

        .btn-outline-custom {
            border: 2px solid #FFF;
            color: #FFF;
            padding: 13px 28px;
            border-radius: 12px;
            font-weight: 600;
            margin-left: 10px;
            transition: var(--transition);
        }

        .btn-outline-custom:hover {
            background: #FFF;
            color: #222;
        }

        /* ==========================================================
            SEARCH BOX
        ========================================================== */

        .search-box {
            background: #FFF;
            margin-top: -35px;
            position: relative;
            z-index: 10;
            padding: 25px;
            border-radius: 18px;
            box-shadow: var(--shadow);
        }

        .search-box input {
            height: 55px;
            border-radius: 12px;
        }

        .search-box button {
            height: 55px;
            border-radius: 12px;
        }

                /* ==========================================================
            STATISTIK
        ========================================================== */

        .stat-section {
            padding: 80px 0;
        }

        .stat-card {
            background: #FFF;
            border-radius: 18px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-8px);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            font-size: 30px;
            color: #FFF;
            background: linear-gradient(
                45deg,
                var(--primary),
                var(--secondary)
            );
        }

        .stat-card h2 {
            font-size: 36px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-card p {
            margin: 0;
            color: var(--gray);
            font-size: 15px;
        }

        /* ==========================================================
            CARD SARPRAS
        ========================================================== */

        .card-sarpras {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            background: #FFF;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        .card-sarpras:hover {
            transform: translateY(-10px);
        }

        .card-sarpras img {
            width: 100%;
            height: 230px;
            object-fit: cover;
        }

        .card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .badge-custom {
            display: inline-block;
            background: #FFE3EF;
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .card-body h5 {
            margin-top: 18px;
            margin-bottom: 12px;
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
        }

        .card-body p {
            color: var(--gray);
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 8px;
        }

        .deskripsi-public {
            min-height: 55px;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 12px;
        }

        .view-all {
            color: var(--primary);
            font-weight: 600;
            transition: var(--transition);
        }

        .view-all:hover {
            color: var(--secondary);
        }

        /* ==========================================================
            FOOTER
        ========================================================== */

        .footer {
            background: var(--dark);
            color: #FFF;
            padding: 70px 0 30px;
            margin-top: 80px;
        }

        .footer h5 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer p,
        .footer li {
            color: rgba(255, 255, 255, .8);
            line-height: 1.8;
        }

        .footer ul {
            padding-left: 0;
            list-style: none;
        }

        .footer a {
            color: rgba(255, 255, 255, .8);
            transition: var(--transition);
        }

        .footer a:hover {
            color: #FFF;
        }

        .footer hr {
            border-color: rgba(255, 255, 255, .15);
            margin: 35px 0 20px;
        }

        /* ==========================================================
            RESPONSIVE
        ========================================================== */

        @media (max-width: 992px) {

            .carousel-item {
                height: 430px;
            }

            .carousel-caption h1 {
                font-size: 38px;
            }

            .section-title {
                font-size: 30px;
            }

        }

        @media (max-width: 768px) {

            .hero {
                margin-top: 78px;
            }

            .carousel-item {
                height: 320px;
            }

            .carousel-caption {
                bottom: 12%;
            }

            .carousel-caption h1 {
                font-size: 28px;
            }

            .carousel-caption p {
                font-size: 14px;
                margin-bottom: 18px;
            }

            .btn-primary-custom,
            .btn-outline-custom {
                display: block;
                width: 100%;
                margin: 8px 0;
                text-align: center;
            }

            .section {
                padding: 60px 0;
            }

            .section-title {
                font-size: 26px;
            }

            .search-box {
                margin-top: 20px;
            }

        }

    </style>

</head>

<body>

<!-- ==========================================================
    NAVBAR
========================================================== -->

<nav class="navbar navbar-expand-lg fixed-top">

    <div class="container">

        <a
            class="navbar-brand"
            href="index.php">

            SISAR<span>PRAS</span>

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="index.php">

                        Beranda

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="user/gedung.php">

                        Gedung

                    </a>

                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#ruangan">
                        Ruangan
                    </a>
                </li>

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="user/public_space.php">

                        Public Space

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="user/inventaris.php">

                        Inventaris

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="user/peminjaman.php">

                        Peminjaman

                    </a>

                </li>

                <li class="nav-item ms-lg-3">

                    <a
                        href="login.php"
                        class="btn btn-login">

                        Login

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- ==========================================================
    HERO
========================================================== -->

<section class="hero">

    <div class="container">

        <div
            id="bannerCarousel"
            class="carousel slide carousel-fade"
            data-bs-ride="carousel">

            <div class="carousel-inner">

            <?php if (!empty($banners)) : ?>

    <?php $no = 0; ?>

    <?php foreach ($banners as $banner) : ?>

        <div class="carousel-item <?= ($no === 0) ? 'active' : ''; ?>">

            <?php
            $gambar = !empty($banner['nama_file'])
                ? 'uploads/banner/' . $banner['nama_file']
                : 'assets/img/banner-default.jpg';
            ?>

            <img
                src="<?= $gambar; ?>"
                alt="<?= htmlspecialchars($banner['judul']); ?>">

            <div class="carousel-overlay"></div>

            <div class="carousel-caption">

                <h1>
                    <?= htmlspecialchars($banner['judul']); ?>
                </h1>

                <p>
                    <?= htmlspecialchars($banner['subjudul']); ?>
                </p>

                <?php if (!empty($banner['deskripsi'])) : ?>

                    <p class="mb-4">
                        <?= htmlspecialchars($banner['deskripsi']); ?>
                    </p>

                <?php endif; ?>

                <a
                    href="#gedung"
                    class="btn btn-primary-custom">

                    Jelajahi Sarpras

                </a>

                <a
                    href="user/peminjaman.php"
                    class="btn btn-outline-custom">

                    Ajukan Peminjaman

                </a>

            </div>

        </div>

        <?php $no++; ?>

    <?php endforeach; ?>

<?php else : ?>

    <div class="carousel-item active">

        <img
            src="assets/img/banner-default.jpg"
            alt="Banner SISARPRAS">

        <div class="carousel-overlay"></div>

        <div class="carousel-caption">

            <h1>
                Sistem Informasi Sarana dan Prasarana
            </h1>

            <p>
                Selamat datang di Sistem Informasi Sarana dan
                Prasarana Politeknik Nest.
            </p>

            <a
                href="#gedung"
                class="btn btn-primary-custom">

                Jelajahi Sarpras

            </a>

            <a
                href="user/peminjaman.php"
                class="btn btn-outline-custom">

                Ajukan Peminjaman

            </a>

        </div>

    </div>

<?php endif; ?>

            </div>

            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#bannerCarousel"
                data-bs-slide="prev">

                <span class="carousel-control-prev-icon"></span>

            </button>

            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#bannerCarousel"
                data-bs-slide="next">

                <span class="carousel-control-next-icon"></span>

            </button>

        </div>

    </div>

</section>

<!-- ==========================================================
    SEARCH BOX
========================================================== -->

<section class="search-section">

    <div class="container">

        <div class="search-box">

            <form
                action="user/pencarian.php"
                method="GET">

                <div class="row g-3 align-items-center">

                    <div class="col-lg-10">

                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Cari gedung, ruangan, public space, atau inventaris...">

                    </div>

                    <div class="col-lg-2 d-grid">

                        <button
                            type="submit"
                            class="btn btn-primary-custom">

                            <i class="bi bi-search"></i>
                            Cari

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</section>


<!-- ==========================================================
    STATISTIK
========================================================== -->

<section class="stat-section">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="section-title">
                Statistik Sarana & Prasarana
            </h2>

            <p class="section-subtitle">
                Seluruh data berikut ditampilkan secara realtime
                berdasarkan data yang dikelola oleh admin.
            </p>

        </div>

        <div class="row g-4">

            <!-- Gedung -->

            <div class="col-lg-2 col-md-4 col-6">

                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <h2><?= $totalGedung; ?></h2>

                    <p>Gedung</p>

                </div>

            </div>

            <!-- Ruangan -->

            <div class="col-lg-2 col-md-4 col-6">

                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-door-open"></i>
                    </div>

                    <h2><?= $totalRuangan; ?></h2>

                    <p>Ruangan</p>

                </div>

            </div>

            <!-- Public Space -->

            <div class="col-lg-2 col-md-4 col-6">

                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </div>

                    <h2><?= $totalPublicSpace; ?></h2>

                    <p>Public Space</p>

                </div>

            </div>

            <!-- Inventaris -->

            <div class="col-lg-2 col-md-4 col-6">

                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <h2><?= $totalInventaris; ?></h2>

                    <p>Inventaris</p>

                </div>

            </div>

            <!-- Peminjaman -->

            <div class="col-lg-2 col-md-4 col-6">

                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>

                    <h2><?= $totalPeminjaman; ?></h2>

                    <p>Peminjaman</p>

                </div>

            </div>

            <!-- Kerusakan -->

            <div class="col-lg-2 col-md-4 col-6">

                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>

                    <h2><?= $totalKerusakan; ?></h2>

                    <p>Kerusakan</p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================================
    SECTION GEDUNG
========================================================== -->

<section
    class="section"
    id="gedung">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-5">

            <div>

                <h2 class="section-title">
                    Gedung
                </h2>

                <p class="section-subtitle">
                    Daftar gedung yang tersedia di lingkungan
                    Politeknik Nest.
                </p>

            </div>

            <a
                href="user/gedung.php"
                class="view-all">

                Lihat Semua
                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="row g-4">

            <?php if (!empty($gedung)) : ?>

                <?php foreach ($gedung as $g) : ?>

                    <?php
                    $fotoGedung = !empty($g['nama_file'])
                        ? 'uploads/lokasi/' . $g['nama_file']
                        : 'assets/img/no-image.jpg';
                    ?>

                    <div class="col-lg-3 col-md-6">

                        <div class="card-sarpras">

                            <img
                                src="<?= $fotoGedung; ?>"
                                alt="<?= htmlspecialchars($g['nama_lokasi']); ?>">

                            <div class="card-body">

                                <h5>

                                    <?= htmlspecialchars($g['nama_lokasi']); ?>

                                </h5>

                                <p>

                                    <?= mb_strimwidth(
                                        strip_tags($g['deskripsi']),
                                        0,
                                        100,
                                        "..."
                                    ); ?>

                                </p>
                                                                <div class="d-flex justify-content-between align-items-center mt-2">

                                    <a
                                        href="user/gedung.php?id=<?= $g['id_lokasi']; ?>"
                                        class="btn btn-primary-custom btn-sm">

                                        Lihat Detail

                                    </a>

                                    <i
                                        class="bi bi-building"
                                        style="
                                            font-size: 24px;
                                            color: var(--primary);
                                        ">
                                    </i>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else : ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        Data gedung belum tersedia.

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<!-- ==========================================================
    SECTION RUANGAN
========================================================== -->

<section
    class="section"
    id="ruangan">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-5">

            <div>

                <h2 class="section-title">
                    Ruangan
                </h2>

                <p class="section-subtitle">
                    Daftar ruangan yang tersedia di lingkungan
                    Politeknik Nest.
                </p>

            </div>

            <a
                href="user/ruangan.php"
                class="view-all">

                Lihat Semua
                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="row g-4">

            <?php if (!empty($ruangan)) : ?>

                <?php foreach ($ruangan as $r) : ?>

                    <?php
                    $fotoRuangan = !empty($r['nama_file'])
                        ? 'uploads/ruangan/' . $r['nama_file']
                        : 'assets/img/no-image.jpg';
                    ?>

                    <div class="col-lg-3 col-md-6">

                        <div class="card-sarpras h-100">

                            <img
                                src="<?= $fotoRuangan; ?>"
                                alt="<?= htmlspecialchars($r['nama_ruangan']); ?>">

                            <div class="card-body">

                                <h5>

                                    <?= htmlspecialchars($r['nama_ruangan']); ?>

                                </h5>

                                <p class="mb-2">

                                    <strong>Gedung :</strong>

                                    <?= htmlspecialchars($r['nama_lokasi']); ?>

                                </p>

                                <p class="mb-2">

                                    <strong>Lantai :</strong>

                                    <?= htmlspecialchars($r['nama_lantai']); ?>

                                </p>

                                <p class="mb-3">

                                    <strong>Kapasitas :</strong>

                                    <?= (int) $r['kapasitas']; ?> Orang

                                </p>

                                <p>

                                    <?= mb_strimwidth(
                                        strip_tags($r['deskripsi']),
                                        0,
                                        80,
                                        "..."
                                    ); ?>

                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-4">

                                    <a
                                        href="user/ruangan.php?id=<?= $r['id_ruangan']; ?>"
                                        class="btn btn-primary-custom btn-sm">

                                        Lihat Detail

                                    </a>

                                    <i
                                        class="bi bi-door-open"
                                        style="
                                            font-size:24px;
                                            color:var(--primary);
                                        ">
                                    </i>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else : ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        Data ruangan belum tersedia.

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<!-- ==========================================================
    SECTION PUBLIC SPACE
========================================================== -->

<section
    class="section bg-light"
    id="public-space">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-2">

            <div>

                <h2 class="section-title">
                    Public Space
                </h2>

                <p class="section-subtitle">
                    Area publik yang dapat digunakan oleh civitas
                    akademika maupun pengunjung.
                </p>

            </div>

            <a
                href="user/public_space.php"
                class="view-all">

                Lihat Semua
                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="row g-4">

            <?php if (!empty($publicSpace)) : ?>

                <?php foreach ($publicSpace as $ps) : ?>

                    <?php
                    $fotoPublicSpace = !empty($ps['nama_file'])
                        ? 'uploads/public_space/' . $ps['nama_file']
                        : 'assets/img/no-image.jpg';
                    ?>

                    <div class="col-lg-3 col-md-6">

                        <div class="card-sarpras h-100">

                            <img
                                src="<?= $fotoPublicSpace; ?>"
                                alt="<?= htmlspecialchars($ps['nama_public_space']); ?>">

                            <div class="card-body">

                                <h5>

                                    <?= htmlspecialchars($ps['nama_public_space']); ?>

                                </h5>

                                <p class="deskripsi-public">

                                    <?= mb_strimwidth(
                                        strip_tags($ps['deskripsi']),
                                        0,
                                        100,
                                        "..."
                                    ); ?>

                                </p>
                                                                <div class="d-flex justify-content-between align-items-center mt-2">

                                    <div>

                                        <small class="text-muted d-block">
                                            Luas Area
                                        </small>

                                        <strong>

                                             <?= number_format($ps['luas'], 0, ',', '.'); ?> m²

                                        </strong>

                                    </div>

                                    <a
                                        href="user/public_space.php?id=<?= $ps['id_public_space']; ?>"
                                        class="btn btn-primary-custom btn-sm">

                                        Lihat Detail

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else : ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        Data Public Space belum tersedia.

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<!-- ==========================================================
    SECTION INVENTARIS
========================================================== -->

<section
    class="section"
    id="inventaris">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-5">

            <div>

                <h2 class="section-title">
                    Inventaris
                </h2>

                <p class="section-subtitle">
                    Daftar inventaris yang tersedia pada Sarana dan
                    Prasarana Politeknik Nest.
                </p>

            </div>

            <a
                href="user/inventaris.php"
                class="view-all">

                Lihat Semua
                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="row g-4">

            <?php if (!empty($inventaris)) : ?>

                <?php foreach ($inventaris as $item) : ?>

                    <?php
                    $fotoInventaris = !empty($item['foto'])
                        ? 'uploads/inventaris/' . $item['foto']
                        : 'assets/img/no-image.jpg';
                    ?>

                    <div class="col-lg-3 col-md-6">

                        <div class="card-sarpras h-100">

                            <img
                                src="<?= $fotoInventaris; ?>"
                                alt="<?= htmlspecialchars($item['nama_barang']); ?>">

                            <div class="card-body">

                                <span class="badge-custom">

                                    <?= htmlspecialchars($item['nama_kategori']); ?>

                                </span>

                                <h5>

                                    <?= htmlspecialchars($item['nama_barang']); ?>

                                </h5>

                                <p class="mb-2">

                                    <strong>Merk :</strong>

                                    <?= htmlspecialchars($item['merk']); ?>

                                </p>

                                <p class="mb-2">

                                    <strong>Kondisi :</strong>

                                    <?= htmlspecialchars($item['kondisi']); ?>

                                </p>

                                <p>

                                    <?= mb_strimwidth(
                                        strip_tags($item['spesifikasi']),
                                        0,
                                        90,
                                        "..."
                                    ); ?>

                                </p>
                                                                <div class="d-flex justify-content-between align-items-center mt-2">

                                    <div>

                                        <small class="text-muted d-block">
                                            Jumlah Barang
                                        </small>

                                        <strong>

                                            <?= htmlspecialchars($item['jumlah']); ?>

                                        </strong>

                                    </div>

                                    <a
                                        href="user/inventaris.php?id=<?= $item['id_inventaris']; ?>"
                                        class="btn btn-primary-custom btn-sm">

                                        Lihat Detail

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else : ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        Data inventaris belum tersedia.

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<!-- ==========================================================
    SECTION INFORMASI
========================================================== -->

<section class="section bg-light">

    <div class="container">

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card-sarpras text-center p-4 h-100">

                    <div class="stat-icon mx-auto mb-4">

                        <i class="bi bi-building"></i>

                    </div>

                    <h4 class="mb-3">
                        Data Selalu Terbaru
                    </h4>

                    <p class="text-muted">

                        Seluruh data gedung, public space,
                        dan inventaris diperbarui secara otomatis
                        ketika admin melakukan perubahan data.

                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card-sarpras text-center p-4 h-100">

                    <div class="stat-icon mx-auto mb-4">

                        <i class="bi bi-box-seam"></i>

                    </div>

                    <h4 class="mb-3">
                        Inventaris Lengkap
                    </h4>

                    <p class="text-muted">

                        Pengguna dapat melihat detail inventaris,
                        spesifikasi, kondisi barang, serta lokasi
                        penyimpanan secara langsung.

                    </p>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card-sarpras text-center p-4 h-100">

                    <div class="stat-icon mx-auto mb-4">

                        <i class="bi bi-journal-check"></i>

                    </div>

                    <h4 class="mb-3">
                        Peminjaman Online
                    </h4>

                    <p class="text-muted">

                        Proses peminjaman sarana dan prasarana
                        dilakukan secara online sehingga lebih
                        mudah, cepat, dan transparan.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================================
    FOOTER
========================================================== -->

<footer class="footer">

    <div class="container">

        <div class="row g-5">

            <div class="col-lg-5">

                <h5>

                    SISARPRAS Politeknik Nest

                </h5>

                <p>

                    Sistem Informasi Sarana dan Prasarana
                    yang digunakan untuk memudahkan
                    pengelolaan data fasilitas kampus,
                    inventaris, serta proses peminjaman
                    secara digital.

                </p>

            </div>

            <div class="col-lg-3">

                <h5>

                    Menu

                </h5>

                <ul>

                    <li>
                        <a href="index.php">Beranda</a>
                    </li>

                    <li>
                        <a href="user/gedung.php">Gedung</a>
                    </li>

                    <li>
                        <a href="user/public_space.php">Public Space</a>
                    </li>

                    <li>
                        <a href="user/inventaris.php">Inventaris</a>
                    </li>

                </ul>

            </div>

            <div class="col-lg-4">

                <h5>

                    Kontak

                </h5>

                <p class="mb-2">

                    <i class="bi bi-geo-alt-fill me-2"></i>

                    Politeknik Nest

                </p>

                <p class="mb-2">

                    <i class="bi bi-envelope-fill me-2"></i>

                    info@polinest.ac.id

                </p>

                <p>

                    <i class="bi bi-telephone-fill me-2"></i>

                    (0271) 000000

                </p>

            </div>

        </div>

        <hr>

        <div class="text-center">

            <p class="mb-0">

                © <?= date('Y'); ?>

                SISARPRAS Politeknik Nest.
                All Rights Reserved.

            </p>

        </div>

    </div>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>