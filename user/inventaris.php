<?php

session_start();

$currentPage = 'inventaris';

require_once '../config/database.php';

$baseUrl = "../";
$detailId = isset($_GET['id']) ? (int) $_GET['id'] : 0;                                                                        $detailId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
/*
|--------------------------------------------------------------------------
| DATA INVENTARIS
|--------------------------------------------------------------------------
*/

$queryInventaris = mysqli_query($conn, "
    SELECT
        i.*,
        k.nama_kategori,
        r.nama_ruangan,
        ps.nama_public_space,
        l.nama_lokasi,
        lant.nama_lantai
    FROM inventaris i

    LEFT JOIN kategori k
        ON i.id_kategori = k.id_kategori

    LEFT JOIN ruangan r
        ON i.id_ruangan = r.id_ruangan

    LEFT JOIN public_space ps
        ON i.id_public_space = ps.id_public_space

    LEFT JOIN lantai lant
        ON (
            r.id_lantai = lant.id_lantai
            OR ps.id_lantai = lant.id_lantai
        )

    LEFT JOIN lokasi l
        ON lant.id_lokasi = l.id_lokasi

    ORDER BY i.nama_barang ASC
");

/*
|--------------------------------------------------------------------------
| STATISTIK
|--------------------------------------------------------------------------
*/

// Total inventaris
$totalInventaris = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM inventaris"
    )
)['total'];

// Total kategori
$totalKategori = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM kategori
         WHERE status='Aktif'"
    )
)['total'];

// Kondisi baik
$totalBaik = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM inventaris
         WHERE kondisi='Baik'"
    )
)['total'];

// Kondisi rusak
$totalRusak = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM inventaris
         WHERE kondisi IN ('Rusak Ringan','Rusak Berat')"
    )
)['total'];

?>

<!DOCTYPE html>
<html lang="id">

<?php include '../includes/user/header.php'; ?>

<body>

<?php include '../includes/user/navbar.php'; ?>

<style>

/* ==================================================
   PAGE
================================================== */

.inventaris-page{

    background:#fff8fc;

    min-height:100vh;

}


/* ==================================================
   HERO
================================================== */

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


/* ==================================================
   BREADCRUMB
================================================== */

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


/* ==================================================
   BADGE
================================================== */

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


/* ==================================================
   TITLE
================================================== */

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


/* ==================================================
   SEARCH
================================================== */

.search-box{

    position:relative;

    max-width:600px;

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


/* ==================================================
   STATISTIK
================================================== */

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


/* ==================================================
   RESPONSIVE
================================================== */

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
/* ==================================================
   INVENTARIS SECTION
================================================== */

.inventaris-section{

    padding-top:10px;

}

.section-heading{

    display:flex;

    align-items:end;

    justify-content:space-between;

    gap:20px;

}

.section-label{

    color:#EC4899;

    font-size:12px;

    font-weight:700;

    letter-spacing:1.5px;

}

.section-title{

    margin:6px 0 5px;

    font-size:30px;

    font-weight:800;

    color:#2d3748;

}

.section-description{

    margin:0;

    color:#718096;

    font-size:14px;

}

.inventory-count{

    display:flex;

    align-items:center;

    gap:7px;

    color:#6b7280;

    font-size:14px;

    white-space:nowrap;

}

.inventory-count i{

    color:#EC4899;

}


/* ==================================================
   CARD
================================================== */

.inventory-card{

    height:100%;

    background:#fff;

    border-radius:22px;

    overflow:hidden;

    box-shadow:
        0 12px 35px rgba(0,0,0,.07);

    transition:
        transform .3s ease,
        box-shadow .3s ease;

}

.inventory-card:hover{

    transform:translateY(-7px);

    box-shadow:
        0 20px 45px rgba(0,0,0,.12);

}


/* ==================================================
   IMAGE
================================================== */

.inventory-image{

    position:relative;

    width:100%;

    height:220px;

    overflow:hidden;

    background:#f5f5f5;

}

.inventory-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:transform .5s ease;

}

.inventory-card:hover
.inventory-image img{

    transform:scale(1.05);

}


/* ==================================================
   CONDITION BADGE
================================================== */

.condition-badge{

    position:absolute;

    top:14px;

    right:14px;

    display:inline-flex;

    align-items:center;

    gap:6px;

    padding:7px 12px;

    border-radius:50px;

    font-size:11px;

    font-weight:600;

    backdrop-filter:blur(8px);

}

.condition-good{

    color:#15803d;

    background:rgba(220,252,231,.94);

}

.condition-warning{

    color:#b45309;

    background:rgba(254,243,199,.94);

}

.condition-danger{

    color:#b91c1c;

    background:rgba(254,226,226,.94);

}

.condition-default{

    color:#475569;

    background:rgba(241,245,249,.94);

}


/* ==================================================
   CONTENT
================================================== */

.inventory-content{

    padding:22px;

}

.inventory-category{

    display:inline-flex;

    align-items:center;

    gap:6px;

    color:#EC4899;

    font-size:11px;

    font-weight:700;

    text-transform:uppercase;

    letter-spacing:.7px;

}

.inventory-name{

    margin:8px 0 8px;

    color:#2d3748;

    font-size:20px;

    font-weight:700;

    line-height:1.4;

    display:-webkit-box;

    -webkit-line-clamp:2;

    -webkit-box-orient:vertical;

    overflow:hidden;

}


/* ==================================================
   CODE
================================================== */

.inventory-code{

    display:flex;

    align-items:flex-start;

    gap:8px;

    padding:9px 11px;

    margin-bottom:16px;

    border-radius:10px;

    background:#fff5f8;

    color:#EC4899;

    font-size:12px;

    font-weight:600;

    word-break:break-all;

}

.inventory-code i{

    flex-shrink:0;

    margin-top:2px;

}


/* ==================================================
   INFO
================================================== */

.inventory-info{

    display:flex;

    flex-direction:column;

    gap:9px;

    margin-bottom:18px;

}

.info-row{

    display:flex;

    align-items:center;

    gap:9px;

    color:#64748b;

    font-size:13px;

}

.info-row i{

    width:18px;

    color:#ff7a45;

    text-align:center;

}


/* ==================================================
   CARD FOOTER
================================================== */

.inventory-card-footer{

    display:flex;

    align-items:center;

    justify-content:space-between;

    gap:10px;

    padding-top:16px;

    border-top:1px solid #f1f1f1;

}

.status-text{

    display:flex;

    align-items:center;

    gap:7px;

    color:#64748b;

    font-size:12px;

    font-weight:600;

}

.status-text i{

    color:#22c55e;

    font-size:7px;

}


/* ==================================================
   DETAIL BUTTON
================================================== */

.btn-detail{

    display:inline-flex;

    align-items:center;

    gap:6px;

    border:none;

    background:none;

    color:#EC4899;

    font-size:13px;

    font-weight:700;

    cursor:pointer;

    padding:5px 0;

    transition:.25s;

}

.btn-detail:hover{

    color:#ff7a45;

}

.btn-detail i{

    transition:.25s;

}

.btn-detail:hover i{

    transform:translateX(4px);

}


/* ==================================================
   EMPTY STATE
================================================== */

.empty-state{

    padding:70px 20px;

    text-align:center;

    color:#718096;

}

.empty-icon{

    width:80px;
    height:80px;

    margin:0 auto 20px;

    display:flex;

    align-items:center;
    justify-content:center;

    border-radius:50%;

    background:#fff0f6;

    color:#EC4899;

    font-size:32px;

}

.empty-state h3{

    margin-bottom:8px;

    color:#2d3748;

    font-size:22px;

    font-weight:700;

}

.empty-state p{

    margin:0;

    font-size:14px;

}


/* ==================================================
   RESPONSIVE
================================================== */

@media(max-width:768px){

    .section-heading{

        align-items:flex-start;

        flex-direction:column;

    }

    .section-title{

        font-size:25px;

    }

}

@media(max-width:576px){

    .inventory-image{

        height:200px;

    }

    .inventory-content{

        padding:18px;

    }

}
/* ==================================================
   INVENTARIS MODAL
================================================== */

.inventory-modal{

    border:none;

    border-radius:24px;

    overflow:hidden;

    box-shadow:
        0 25px 70px rgba(0,0,0,.18);

}


/* ==================================================
   MODAL HEADER
================================================== */

.inventory-modal .modal-header{

    padding:24px 28px;

    border-bottom:1px solid #f1f1f1;

    background:
        linear-gradient(
            135deg,
            rgba(236,72,153,.06),
            rgba(255,122,69,.08)
        );

}

.modal-label{

    display:block;

    margin-bottom:4px;

    color:#EC4899;

    font-size:10px;

    font-weight:700;

    letter-spacing:1.5px;

}

.inventory-modal .modal-title{

    margin:0;

    color:#2d3748;

    font-size:23px;

    font-weight:800;

}


/* ==================================================
   MODAL IMAGE
================================================== */

.modal-image-wrapper{

    width:100%;

    height:320px;

    overflow:hidden;

    border-radius:18px;

    background:#f7f7f7;

}

.modal-inventory-image{

    width:100%;

    height:100%;

    object-fit:cover;

}


/* ==================================================
   MODAL CATEGORY
================================================== */

.modal-category{

    display:inline-flex;

    align-items:center;

    gap:7px;

    color:#EC4899;

    font-size:12px;

    font-weight:700;

    text-transform:uppercase;

    letter-spacing:.7px;

}

.modal-inventory-name{

    margin:8px 0 12px;

    color:#2d3748;

    font-size:25px;

    font-weight:800;

    line-height:1.3;

}


/* ==================================================
   MODAL CODE
================================================== */

.modal-code{

    display:flex;

    align-items:flex-start;

    gap:9px;

    padding:11px 13px;

    margin-bottom:20px;

    border-radius:11px;

    background:#fff4f8;

    color:#EC4899;

    font-size:13px;

    font-weight:700;

    word-break:break-all;

}

.modal-code i{

    flex-shrink:0;

    margin-top:2px;

}


/* ==================================================
   INFO LIST
================================================== */

.modal-info-list{

    display:grid;

    grid-template-columns:repeat(2,1fr);

    gap:12px;

}

.modal-info-item{

    display:flex;

    align-items:center;

    gap:10px;

    padding:11px;

    border:1px solid #f0f0f0;

    border-radius:12px;

}

.modal-info-icon{

    width:34px;

    height:34px;

    flex-shrink:0;

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:10px;

    background:#fff3f7;

    color:#EC4899;

    font-size:14px;

}

.modal-info-item span{

    display:block;

    margin-bottom:2px;

    color:#9ca3af;

    font-size:10px;

}

.modal-info-item strong{

    display:block;

    color:#374151;

    font-size:12px;

    font-weight:600;

}


/* ==================================================
   SPECIFICATION
================================================== */

.modal-specification{

    margin-top:25px;

    padding:18px;

    border-radius:15px;

    background:#fafafa;

}

.specification-title{

    display:flex;

    align-items:center;

    gap:8px;

    margin-bottom:8px;

    color:#374151;

    font-size:13px;

    font-weight:700;

}

.specification-title i{

    color:#EC4899;

}

.modal-specification p{

    margin:0;

    color:#6b7280;

    font-size:13px;

    line-height:1.7;

    white-space:pre-line;

}


/* ==================================================
   SOURCE
================================================== */

.modal-source{

    display:flex;

    align-items:center;

    gap:12px;

    margin-top:14px;

    padding:14px 18px;

    border-radius:14px;

    background:#fff8f5;

}

.modal-source > i{

    color:#ff7a45;

    font-size:20px;

}

.modal-source span{

    display:block;

    color:#9ca3af;

    font-size:10px;

}

.modal-source strong{

    color:#4b5563;

    font-size:13px;

    font-weight:600;

}


/* ==================================================
   MODAL FOOTER
================================================== */

.inventory-modal .modal-footer{

    padding:18px 28px;

    border-top:1px solid #f1f1f1;

}

.btn-modal-close{

    border:none;

    padding:9px 20px;

    border-radius:10px;

    background:#f3f4f6;

    color:#4b5563;

    font-size:13px;

    font-weight:600;

    cursor:pointer;

    transition:.25s;

}

.btn-modal-close:hover{

    background:#e5e7eb;

}


/* ==================================================
   MODAL RESPONSIVE
================================================== */

@media(max-width:768px){

    .modal-image-wrapper{

        height:250px;

    }

    .modal-inventory-name{

        font-size:21px;

    }

}

@media(max-width:576px){

    .inventory-modal .modal-header{

        padding:20px;

    }

    .inventory-modal .modal-body{

        padding:20px;

    }

    .inventory-modal .modal-footer{

        padding:15px 20px;

    }

    .modal-info-list{

        grid-template-columns:1fr;

    }

    .modal-image-wrapper{

        height:220px;

    }

}
</style>
<main class="inventaris-page">

<section class="page-hero">

    <div class="container">

        <div class="breadcrumb-custom">

            <a href="<?= $baseUrl ?>index.php">
                Beranda
            </a>

            <span>/</span>

            <span>Inventaris</span>

        </div>


        <span class="hero-badge">

            <i class="bi bi-box-seam-fill"></i>

            Data Inventaris

        </span>


        <h1 class="hero-title">

            Semua Inventaris

        </h1>


        <p class="hero-description">

            Jelajahi seluruh inventaris Politeknik Nest
            beserta informasi barang, kondisi, kategori,
            dan lokasi penempatannya.

        </p>


        <div class="search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="searchInventaris"
                placeholder="Cari nama barang, kode inventaris, atau merk...">

        </div>

    </div>

</section>


<!-- ==================================================
     STATISTIK
================================================== -->

<section class="statistik-section">

    <div class="container">

        <div class="statistik-grid">


            <!-- TOTAL -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-box-seam-fill"></i>

                </div>

                <h3>
                    <?= $totalInventaris; ?>
                </h3>

                <p>
                    Total Inventaris
                </p>

            </div>


            <!-- KATEGORI -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-tags-fill"></i>

                </div>

                <h3>
                    <?= $totalKategori; ?>
                </h3>

                <p>
                    Kategori
                </p>

            </div>


            <!-- BAIK -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <h3>
                    <?= $totalBaik; ?>
                </h3>

                <p>
                    Kondisi Baik
                </p>

            </div>


            <!-- RUSAK -->

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                </div>

                <h3>
                    <?= $totalRusak; ?>
                </h3>

                <p>
                    Kondisi Rusak
                </p>

            </div>


        </div>

    </div>

</section>

<!-- ==================================================
     DAFTAR INVENTARIS
================================================== -->

<section class="inventaris-section pb-5">

    <div class="container">

        <!-- SECTION HEADER -->

        <div class="section-heading mb-4">

            <div>

                <span class="section-label">
                    DATA SARANA PRASARANA
                </span>

                <h2 class="section-title">
                    Daftar Inventaris
                </h2>

                <p class="section-description">
                    Informasi inventaris yang tersedia di
                    Politeknik Nest.
                </p>

            </div>

            <div class="inventory-count">

                <i class="bi bi-box-seam"></i>

                <span id="inventoryCount">
                    <?= mysqli_num_rows($queryInventaris); ?>
                </span>

                Inventaris

            </div>

        </div>


        <!-- GRID -->

        <div
            class="row g-4"
            id="inventarisGrid">


            <?php if (mysqli_num_rows($queryInventaris) > 0) : ?>


                <?php while ($item = mysqli_fetch_assoc($queryInventaris)) : ?>


                    <?php

                    /*
                    |--------------------------------------------------------------------------
                    | FOTO
                    |--------------------------------------------------------------------------
                    */

                    $foto = !empty($item['foto'])
                        ? $baseUrl . 'assets/uploads/inventaris/' . $item['foto']
                        : $baseUrl . 'assets/img/no-image.png';


                    /*
                    |--------------------------------------------------------------------------
                    | LOKASI
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($item['nama_ruangan'])) {

                        $lokasi = $item['nama_ruangan'];

                    } elseif (!empty($item['nama_public_space'])) {

                        $lokasi = $item['nama_public_space'];

                    } else {

                        $lokasi = 'Lokasi belum ditentukan';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | KONDISI
                    |--------------------------------------------------------------------------
                    */

                    $kondisi = $item['kondisi'] ?? 'Tidak diketahui';

                    $kondisiClass = 'condition-default';

                    if (strtolower($kondisi) === 'baik') {

                        $kondisiClass = 'condition-good';

                    } elseif (
                        strtolower($kondisi) === 'rusak ringan'
                    ) {

                        $kondisiClass = 'condition-warning';

                    } elseif (
                        strtolower($kondisi) === 'rusak berat'
                    ) {

                        $kondisiClass = 'condition-danger';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    $status = $item['status'] ?? 'Tidak diketahui';

                    ?>


                    <!-- CARD -->

                    <div
                        class="col-xl-4 col-lg-4 col-md-6 inventaris-item"
                        data-search="

                            <?= htmlspecialchars(
                                strtolower(
                                    $item['nama_barang']
                                    . ' '
                                    . $item['kode_inventaris']
                                    . ' '
                                    . ($item['merk'] ?? '')
                                    . ' '
                                    . ($item['nama_kategori'] ?? '')
                                    . ' '
                                    . $lokasi
                                )
                            ); ?>

                        ">


                        <div class="inventory-card">


                            <!-- FOTO -->

                            <div class="inventory-image">

                                <img
                                    src="<?= htmlspecialchars($foto); ?>"
                                    alt="<?= htmlspecialchars(
                                        $item['nama_barang']
                                    ); ?>"
                                    loading="lazy">


                                <!-- KONDISI -->

                                <span
                                    class="condition-badge <?= $kondisiClass; ?>">

                                    <?php if ($kondisi === 'Baik') : ?>

                                        <i class="bi bi-check-circle-fill"></i>

                                    <?php elseif ($kondisi === 'Rusak Ringan') : ?>

                                        <i class="bi bi-exclamation-circle-fill"></i>

                                    <?php elseif ($kondisi === 'Rusak Berat') : ?>

                                        <i class="bi bi-x-circle-fill"></i>

                                    <?php else : ?>

                                        <i class="bi bi-info-circle-fill"></i>

                                    <?php endif; ?>

                                    <?= htmlspecialchars($kondisi); ?>

                                </span>

                            </div>


                            <!-- CONTENT -->

                            <div class="inventory-content">


                                <!-- CATEGORY -->

                                <span class="inventory-category">

                                    <i class="bi bi-tag-fill"></i>

                                    <?= htmlspecialchars(
                                        $item['nama_kategori']
                                        ?? 'Tanpa Kategori'
                                    ); ?>

                                </span>


                                <!-- NAME -->

                                <h3 class="inventory-name">

                                    <?= htmlspecialchars(
                                        $item['nama_barang']
                                    ); ?>

                                </h3>


                                <!-- CODE -->

                                <div class="inventory-code">

                                    <i class="bi bi-upc-scan"></i>

                                    <span>

                                        <?= htmlspecialchars(
                                            $item['kode_inventaris']
                                        ); ?>

                                    </span>

                                </div>


                                <!-- INFO -->

                                <div class="inventory-info">


                                    <!-- MERK -->

                                    <?php if (!empty($item['merk'])) : ?>

                                        <div class="info-row">

                                            <i class="bi bi-bookmark"></i>

                                            <span>

                                                <?= htmlspecialchars(
                                                    $item['merk']
                                                ); ?>

                                            </span>

                                        </div>

                                    <?php endif; ?>


                                    <!-- LOKASI -->

                                    <div class="info-row">

                                        <i class="bi bi-geo-alt-fill"></i>

                                        <span>

                                            <?= htmlspecialchars($lokasi); ?>

                                        </span>

                                    </div>


                                    <!-- JUMLAH -->

                                    <div class="info-row">

                                        <i class="bi bi-boxes"></i>

                                        <span>

                                            Jumlah:
                                            <?= (int) $item['jumlah']; ?>

                                        </span>

                                    </div>


                                </div>


                                <!-- FOOTER CARD -->

                                <div class="inventory-card-footer">


                                    <span class="status-text">

                                        <?php if (strtolower($status) === 'tersedia') : ?>

                                            <i class="bi bi-circle-fill"></i>

                                        <?php else : ?>

                                            <i class="bi bi-circle-fill"></i>

                                        <?php endif; ?>

                                        <?= htmlspecialchars($status); ?>

                                    </span>


<button
    type="button"
    class="btn-detail"
    data-bs-toggle="modal"
    data-bs-target="#inventoryModal"

    data-id="<?= (int) $item['id_inventaris']; ?>"

    data-spesifikasi="<?= htmlspecialchars(
        $item['spesifikasi'] ?? '',
        ENT_QUOTES
    ); ?>"

    data-tahun="<?= htmlspecialchars(
        $item['tahun_perolehan'] ?? '',
        ENT_QUOTES
    ); ?>"

    data-sumber="<?= htmlspecialchars(
        $item['sumber_perolehan'] ?? '',
        ENT_QUOTES
    ); ?>">

    Lihat Detail

    <i class="bi bi-arrow-right"></i>

</button>


                                </div>


                            </div>

                        </div>

                    </div>


                <?php endwhile; ?>


            <?php else : ?>


                <!-- EMPTY -->

                <div class="col-12">

                    <div class="empty-state">

                        <div class="empty-icon">

                            <i class="bi bi-box-seam"></i>

                        </div>

                        <h3>
                            Belum Ada Inventaris
                        </h3>

                        <p>
                            Data inventaris belum tersedia
                            saat ini.
                        </p>

                    </div>

                </div>


            <?php endif; ?>


        </div>


        <!-- SEARCH EMPTY -->

        <div
            id="searchEmpty"
            class="empty-state d-none">

            <div class="empty-icon">

                <i class="bi bi-search"></i>

            </div>

            <h3>
                Inventaris Tidak Ditemukan
            </h3>

            <p>
                Coba gunakan kata kunci pencarian
                yang berbeda.
            </p>

        </div>


    </div>

</section>

<!-- ==================================================
     MODAL DETAIL INVENTARIS
================================================== -->

<div
    class="modal fade"
    id="inventoryModal"
    tabindex="-1"
    aria-labelledby="inventoryModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content inventory-modal">


            <!-- HEADER -->

            <div class="modal-header">

                <div>

                    <span class="modal-label">
                        DETAIL INVENTARIS
                    </span>

                    <h5
                        class="modal-title"
                        id="inventoryModalLabel">

                        Detail Inventaris

                    </h5>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>


            <!-- BODY -->

            <div class="modal-body">


                <div class="row g-4">


                    <!-- FOTO -->

                    <div class="col-md-5">

                        <div class="modal-image-wrapper">

                            <img
                                src=""
                                id="modalInventoryImage"
                                alt="Foto Inventaris"
                                class="modal-inventory-image">

                        </div>

                    </div>


                    <!-- INFORMASI -->

                    <div class="col-md-7">

                        <div class="modal-category">

                            <i class="bi bi-tag-fill"></i>

                            <span id="modalInventoryCategory">
                                -
                            </span>

                        </div>


                        <h3
                            class="modal-inventory-name"
                            id="modalInventoryName">

                            -

                        </h3>


                        <!-- KODE -->

                        <div class="modal-code">

                            <i class="bi bi-upc-scan"></i>

                            <span id="modalInventoryCode">
                                -
                            </span>

                        </div>


                        <!-- INFO -->

                        <div class="modal-info-list">


                            <div class="modal-info-item">

                                <div class="modal-info-icon">

                                    <i class="bi bi-bookmark-fill"></i>

                                </div>

                                <div>

                                    <span>
                                        Merk
                                    </span>

                                    <strong id="modalInventoryMerk">
                                        -
                                    </strong>

                                </div>

                            </div>


                            <div class="modal-info-item">

                                <div class="modal-info-icon">

                                    <i class="bi bi-geo-alt-fill"></i>

                                </div>

                                <div>

                                    <span>
                                        Lokasi
                                    </span>

                                    <strong id="modalInventoryLocation">
                                        -
                                    </strong>

                                </div>

                            </div>


                            <div class="modal-info-item">

                                <div class="modal-info-icon">

                                    <i class="bi bi-boxes"></i>

                                </div>

                                <div>

                                    <span>
                                        Jumlah
                                    </span>

                                    <strong id="modalInventoryJumlah">
                                        -
                                    </strong>

                                </div>

                            </div>


                            <div class="modal-info-item">

                                <div class="modal-info-icon">

                                    <i class="bi bi-heart-pulse-fill"></i>

                                </div>

                                <div>

                                    <span>
                                        Kondisi
                                    </span>

                                    <strong id="modalInventoryCondition">
                                        -
                                    </strong>

                                </div>

                            </div>


                            <div class="modal-info-item">

                                <div class="modal-info-icon">

                                    <i class="bi bi-circle-fill"></i>

                                </div>

                                <div>

                                    <span>
                                        Status
                                    </span>

                                    <strong id="modalInventoryStatus">
                                        -
                                    </strong>

                                </div>

                            </div>


                            <div class="modal-info-item">

                                <div class="modal-info-icon">

                                    <i class="bi bi-calendar3"></i>

                                </div>

                                <div>

                                    <span>
                                        Tahun Perolehan
                                    </span>

                                    <strong id="modalInventoryYear">
                                        -
                                    </strong>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>


                <!-- SPESIFIKASI -->

                <div class="modal-specification">

                    <div class="specification-title">

                        <i class="bi bi-card-text"></i>

                        Spesifikasi

                    </div>

                    <p id="modalInventorySpecification">

                        Tidak ada spesifikasi.

                    </p>

                </div>


                <!-- SUMBER PEROLEHAN -->

                <div class="modal-source">

                    <i class="bi bi-box-arrow-in-down"></i>

                    <div>

                        <span>
                            Sumber Perolehan
                        </span>

                        <strong id="modalInventorySource">
                            -
                        </strong>

                    </div>

                </div>


            </div>


            <!-- FOOTER -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-modal-close"
                    data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>


        </div>

    </div>

</div>
<!-- ==================================================
     END MAIN
================================================== -->

</main>


<!-- ==================================================
     FOOTER
================================================== -->

<?php include '../includes/user/footer.php'; ?>


<!-- ==================================================
     BOOTSTRAP JS
================================================== -->

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


<script>

/* ==================================================
   SEARCH INVENTARIS
================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('searchInventaris');

    const inventoryItems =
        document.querySelectorAll('.inventaris-item');

    const searchEmpty =
        document.getElementById('searchEmpty');

    const inventoryCount =
        document.getElementById('inventoryCount');


    if (!searchInput) {
        return;
    }


    searchInput.addEventListener('input', function () {

        const keyword =
            this.value
                .toLowerCase()
                .trim();


        let visibleCount = 0;


        inventoryItems.forEach(function (item) {

            const searchableText =
                item.dataset.search || '';


            if (
                searchableText.includes(keyword)
            ) {

                item.style.display = '';

                visibleCount++;

            } else {

                item.style.display = 'none';

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Update jumlah
        |--------------------------------------------------------------------------
        */

        if (inventoryCount) {

            inventoryCount.textContent =
                visibleCount;

        }


        /*
        |--------------------------------------------------------------------------
        | Empty Search
        |--------------------------------------------------------------------------
        */

        if (searchEmpty) {

            if (
                keyword !== '' &&
                visibleCount === 0
            ) {

                searchEmpty.classList.remove('d-none');

            } else {

                searchEmpty.classList.add('d-none');

            }

        }

    });

});


/* ==================================================
   MODAL DETAIL INVENTARIS
================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const inventoryModal =
        document.getElementById('inventoryModal');


    if (!inventoryModal) {
        return;
    }


    inventoryModal.addEventListener(
        'show.bs.modal',
        function (event) {

            const button =
                event.relatedTarget;


            if (!button) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CARD
            |--------------------------------------------------------------------------
            */

            const card =
                button.closest('.inventory-card');


            if (!card) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | ELEMENT CARD
            |--------------------------------------------------------------------------
            */

            const image =
                card.querySelector(
                    '.inventory-image img'
                );

            const category =
                card.querySelector(
                    '.inventory-category'
                );

            const name =
                card.querySelector(
                    '.inventory-name'
                );

            const code =
                card.querySelector(
                    '.inventory-code span'
                );

            const infoRows =
                card.querySelectorAll(
                    '.info-row'
                );

            const condition =
                card.querySelector(
                    '.condition-badge'
                );

            const status =
                card.querySelector(
                    '.status-text'
                );


            /*
            |--------------------------------------------------------------------------
            | FOTO
            |--------------------------------------------------------------------------
            */

            const modalImage =
                document.getElementById(
                    'modalInventoryImage'
                );


            if (image && modalImage) {

                modalImage.src =
                    image.src;

                modalImage.alt =
                    image.alt;

            }


            /*
            |--------------------------------------------------------------------------
            | KATEGORI
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventoryCategory'
            ).textContent =
                category
                    ? category.textContent.trim()
                    : '-';


            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventoryName'
            ).textContent =
                name
                    ? name.textContent.trim()
                    : '-';


            /*
            |--------------------------------------------------------------------------
            | KODE
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventoryCode'
            ).textContent =
                code
                    ? code.textContent.trim()
                    : '-';


            /*
            |--------------------------------------------------------------------------
            | MERK
            |--------------------------------------------------------------------------
            */

            let merk = '-';


            if (infoRows.length >= 1) {

                merk =
                    infoRows[0]
                        .textContent
                        .trim();

            }


            document.getElementById(
                'modalInventoryMerk'
            ).textContent = merk;


            /*
            |--------------------------------------------------------------------------
            | LOKASI
            |--------------------------------------------------------------------------
            */

            let lokasi = '-';


            if (infoRows.length >= 2) {

                lokasi =
                    infoRows[1]
                        .textContent
                        .trim();

            }


            document.getElementById(
                'modalInventoryLocation'
            ).textContent = lokasi;


            /*
            |--------------------------------------------------------------------------
            | JUMLAH
            |--------------------------------------------------------------------------
            */

            let jumlah = '-';


            if (infoRows.length >= 3) {

                jumlah =
                    infoRows[2]
                        .textContent
                        .trim();

            }


            document.getElementById(
                'modalInventoryJumlah'
            ).textContent = jumlah;


            /*
            |--------------------------------------------------------------------------
            | KONDISI
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventoryCondition'
            ).textContent =
                condition
                    ? condition.textContent.trim()
                    : '-';


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventoryStatus'
            ).textContent =
                status
                    ? status.textContent.trim()
                    : '-';


            /*
            |--------------------------------------------------------------------------
            | DATA TAMBAHAN
            |--------------------------------------------------------------------------
            */

            const spesifikasi =
                button.dataset.spesifikasi || '';

            const tahun =
                button.dataset.tahun || '';

            const sumber =
                button.dataset.sumber || '';


            /*
            |--------------------------------------------------------------------------
            | SPESIFIKASI
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventorySpecification'
            ).textContent =
                spesifikasi !== ''
                    ? spesifikasi
                    : 'Tidak ada spesifikasi.';


            /*
            |--------------------------------------------------------------------------
            | TAHUN
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventoryYear'
            ).textContent =
                tahun !== ''
                    ? tahun
                    : '-';


            /*
            |--------------------------------------------------------------------------
            | SUMBER
            |--------------------------------------------------------------------------
            */

            document.getElementById(
                'modalInventorySource'
            ).textContent =
                sumber !== ''
                    ? sumber
                    : '-';

        }

    );

});
/* ==================================================
   AUTO OPEN DETAIL FROM URL
================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const urlParams = new URLSearchParams(window.location.search);
    const detailId = urlParams.get('id');

    if (!detailId) {
        return;
    }

    const detailButton = document.querySelector(
        '.btn-detail[data-id="' + detailId + '"]'
    );

    if (detailButton) {
        detailButton.click();
    }

});
</script>


<!-- ==================================================
     END BODY
================================================== -->

</body>

</html>
