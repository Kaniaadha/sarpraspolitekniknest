<?php

session_start();

require_once '../config/database.php';

$baseUrl = "../";

$currentPage = "ruangan";


/* ==========================================================
|  DATA RUANGAN
========================================================== */

$sql = "
    SELECT
        l.id_lantai,
        l.id_lokasi,
        l.kode_lantai,
        l.nama_lantai,
        l.nomor_lantai,

        lok.kode_lokasi,
        lok.nama_lokasi,

        r.id_ruangan,
        r.kode_ruangan,
        r.nama_ruangan,
        r.luas,
        r.kapasitas,
        r.deskripsi,
        r.status AS status_ruangan,

        fr.nama_file AS foto_ruangan

    FROM lantai l

    INNER JOIN lokasi lok
        ON lok.id_lokasi = l.id_lokasi

    LEFT JOIN ruangan r
        ON r.id_lantai = l.id_lantai
        AND r.status = 'Aktif'

    LEFT JOIN foto_ruangan fr
        ON fr.id_ruangan = r.id_ruangan
        AND fr.is_cover = 1

    WHERE
        l.status = 'Aktif'
        AND lok.status = 'Aktif'

    ORDER BY
        lok.id_lokasi ASC,
        l.nomor_lantai ASC,
        r.nama_ruangan ASC
";

$query = mysqli_query($conn, $sql);


/* ==========================================================
|  KELOMPOKKAN DATA BERDASARKAN LANTAI
========================================================== */

$lantaiList = [];

if ($query) {

    while ($row = mysqli_fetch_assoc($query)) {

        $idLantai = $row['id_lantai'];

        if (!isset($lantaiList[$idLantai])) {

            $lantaiList[$idLantai] = [
                'id_lantai'     => $row['id_lantai'],
                'id_lokasi'     => $row['id_lokasi'],
                'kode_lantai'   => $row['kode_lantai'],
                'nama_lantai'   => $row['nama_lantai'],
                'nomor_lantai'  => $row['nomor_lantai'],
                'kode_lokasi'   => $row['kode_lokasi'],
                'nama_lokasi'   => $row['nama_lokasi'],
                'ruangan'       => []
            ];

        }

        /*
        | Kalau lantai belum memiliki ruangan,
        | jangan masukkan data kosong sebagai card.
        */

        if (!empty($row['id_ruangan'])) {

            $lantaiList[$idLantai]['ruangan'][] = [

                'id_ruangan'     => $row['id_ruangan'],
                'kode_ruangan'   => $row['kode_ruangan'],
                'nama_ruangan'   => $row['nama_ruangan'],
                'luas'           => $row['luas'],
                'kapasitas'      => $row['kapasitas'],
                'deskripsi'      => $row['deskripsi'],
                'status_ruangan' => $row['status_ruangan'],
                'foto_ruangan'   => $row['foto_ruangan']

            ];

        }

    }

}

/* ==========================================================
|  HITUNG TOTAL RUANGAN
========================================================== */

$totalRuangan = 0;

foreach ($lantaiList as $lantai) {

    $totalRuangan += count($lantai['ruangan']);

}

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

.ruangan-page{

    background:#fff8fc;

    min-height:100vh;

}


/*==================================================
HERO
==================================================*/

.ruangan-hero{

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


.ruangan-hero::before{

    content:"";

    position:absolute;

    width:420px;

    height:420px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    top:-180px;

    right:-120px;

}


.ruangan-hero::after{

    content:"";

    position:absolute;

    width:260px;

    height:260px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    left:-80px;

    bottom:-120px;

}


.ruangan-hero .container{

    position:relative;

    z-index:2;

}

/*==================================================
BREADCRUMB
==================================================*/

.ruangan-breadcrumb{

    display:flex;

    align-items:center;

    gap:8px;

    margin-bottom:18px;

    font-size:14px;

}

.ruangan-breadcrumb a{

    color:#fff;

    text-decoration:none;

}

.ruangan-breadcrumb span{

    color:rgba(255,255,255,.8);

}

/*==================================================
BADGE
==================================================*/

.ruangan-badge{

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

.ruangan-hero h1{

    color:#fff;

    font-size:3rem;

    font-weight:800;

    margin-bottom:14px;

    line-height:1.2;

}


.ruangan-description{

    max-width:650px;

    color:rgba(255,255,255,.92);

    line-height:1.8;

    margin-bottom:30px;

}


/*==================================================
SEARCH
==================================================*/

.ruangan-search{

    position:relative;

    max-width:560px;

}


.ruangan-search i{

    position:absolute;

    left:22px;

    top:50%;

    transform:translateY(-50%);

    color:#EC4899;

    z-index:2;

}


.ruangan-search input{

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
CONTENT
==================================================*/

.ruangan-content{

    padding:20px 0 80px;

}


/*==================================================
TOTAL INFO
==================================================*/

.ruangan-total{

    margin-top:-55px;

    margin-bottom:45px;

    position:relative;

    z-index:5;

}


.ruangan-total-card{

    display:flex;

    align-items:center;

    gap:18px;

    background:#fff;

    border-radius:22px;

    padding:22px 26px;

    box-shadow:
        0 15px 35px rgba(0,0,0,.08);

}


.ruangan-total-icon{

    width:58px;

    height:58px;

    flex-shrink:0;

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


.ruangan-total-card h3{

    margin:0;

    font-size:28px;

    font-weight:800;

    color:#2d3748;

}


.ruangan-total-card p{

    margin:3px 0 0;

    color:#6b7280;

    font-size:14px;

}


/*==================================================
LANTAI SECTION
==================================================*/

.lantai-section{

    margin-bottom:45px;

}


.lantai-section:last-child{

    margin-bottom:0;

}


/*==================================================
LANTAI HEADER
==================================================*/

.lantai-header{

    display:flex;

    align-items:center;

    justify-content:space-between;

    margin-bottom:22px;

}

.lantai-title-wrapper{

    display:flex;

    align-items:center;

    gap:12px;

}

.lantai-icon{

    width:48px;

    height:48px;

    flex-shrink:0;

    border-radius:14px;

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

    font-size:20px;

}

.lantai-title-wrapper h2{

    margin:0;

    font-size:25px;

    font-weight:800;

    color:#2d3748;

}

.lantai-title-wrapper p{

    margin:3px 0 0;

    color:#6b7280;

    font-size:13px;

}

.lantai-count{

    padding:7px 14px;

    border-radius:50px;

    background:#FCE7F3;

    color:#EC4899;

    font-size:13px;

    font-weight:700;

}

/*==================================================
RUANGAN GRID
==================================================*/

.ruangan-grid{

    display:grid;

    grid-template-columns:repeat(3,1fr);

    gap:22px;

}

/*==================================================
RUANGAN CARD
==================================================*/

.ruangan-card{

    background:#fff;

    border-radius:22px;

    overflow:hidden;

    box-shadow:
        0 15px 35px rgba(0,0,0,.08);

    transition:.35s;

}

.ruangan-card:hover{

    transform:translateY(-8px);

    box-shadow:
        0 25px 45px rgba(236,72,153,.18);

}

/*==================================================
IMAGE
==================================================*/

.ruangan-image{

    position:relative;

    height:175px;

    overflow:hidden;

    background:
        linear-gradient(
            135deg,
            #FCE7F3,
            #FFF0E8
        );

}


.ruangan-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.4s;

}

.ruangan-card:hover .ruangan-image img{

    transform:scale(1.08);

}

.ruangan-image-placeholder{

    width:100%;

    height:100%;

    display:flex;

    align-items:center;

    justify-content:center;

}

.ruangan-image-placeholder i{

    font-size:48px;

    color:#EC4899;

}

.ruangan-code{

    position:absolute;

    top:14px;

    left:14px;

    padding:6px 12px;

    border-radius:50px;

    background:#fff;

    color:#EC4899;

    font-size:11px;

    font-weight:700;

    box-shadow:
        0 5px 15px rgba(0,0,0,.08);

}

/*==================================================
CARD BODY
==================================================*/

.ruangan-body{

    padding:18px;

}

.ruangan-body h5{

    font-size:18px;

    font-weight:700;

    margin:0 0 5px;

    color:#2d3748;

}

.ruangan-location{

    display:flex;

    align-items:center;

    gap:6px;

    color:#6b7280;

    font-size:12px;

    margin-bottom:14px;

}

.ruangan-location i{

    color:#EC4899;

}

/*==================================================
INFO
==================================================*/

.ruangan-info{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:8px;

    margin-bottom:16px;

}

.ruangan-info div{

    display:flex;

    align-items:center;

    gap:7px;

    font-size:13px;

    color:#4B5563;

}

.ruangan-info i{

    color:#EC4899;

}

/*==================================================
DESCRIPTION
==================================================*/

.ruangan-desc{

    color:#6B7280;

    font-size:13px;

    line-height:1.6;

    margin-bottom:16px;

}

/*==================================================
BUTTON
==================================================*/

.btn-detail-ruangan{

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

.btn-detail-ruangan:hover{

    color:#fff;

    transform:translateY(-2px);

}

.btn-detail-ruangan i{

    transition:.3s;

}

.btn-detail-ruangan:hover i{

    transform:translateX(4px);

}

/*==================================================
EMPTY
==================================================*/

.ruangan-empty{

    padding:25px;

    border-radius:18px;

    background:#fff;

    text-align:center;

    color:#6B7280;

    box-shadow:
        0 10px 25px rgba(0,0,0,.05);

}

.ruangan-empty i{

    display:block;

    margin-bottom:8px;

    font-size:28px;

    color:#EC4899;

}

/*==================================================
SEARCH EMPTY
==================================================*/

.search-empty{

    display:none;

    padding:35px;

    text-align:center;

    border-radius:20px;

    background:#fff;

    color:#6B7280;

    box-shadow:
        0 10px 25px rgba(0,0,0,.05);

}

.search-empty i{

    display:block;

    margin-bottom:10px;

    font-size:35px;

    color:#EC4899;

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:992px){

    .ruangan-grid{

        grid-template-columns:repeat(2,1fr);

    }

}

@media(max-width:768px){

    .ruangan-hero{

        padding:55px 0 100px;

    }

    .ruangan-hero h1{

        font-size:2rem;

    }

    .ruangan-description{

        font-size:14px;

    }

    .ruangan-grid{

        grid-template-columns:repeat(2,1fr);

        gap:14px;

    }

    .ruangan-total-card{

        padding:18px;

    }

    .lantai-title-wrapper h2{

        font-size:21px;

    }

}

@media(max-width:576px){

    .ruangan-grid{

        grid-template-columns:1fr;

    }

    .ruangan-content{

        padding-bottom:55px;

    }

    .lantai-header{

        align-items:flex-start;

        gap:12px;

    }

    .lantai-count{

        font-size:11px;

        padding:6px 10px;

    }

    .ruangan-image{

        height:180px;

    }
}
.section-label {
    color: #EC4899 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    letter-spacing: 1.5px !important;
}
.section-title {
    font-weight: 700;
}
</style>

<main class="ruangan-page">

<!-- ==========================================================
     HERO
========================================================== -->

<section class="ruangan-hero">

    <div class="container">

        <div class="ruangan-breadcrumb">

            <a href="<?= $baseUrl; ?>index.php">
                Beranda
            </a>

            <span>/</span>

            <span>
                Ruangan
            </span>

        </div>

        <span class="ruangan-badge">

            <i class="bi bi-door-open-fill"></i>

            Data Ruangan

        </span>


        <h1>

            Semua Ruangan

        </h1>

        <p class="ruangan-description">

            Jelajahi seluruh ruangan yang tersedia di Politeknik Nest
            berdasarkan lantai dan lokasi gedung.

        </p>

        <div class="ruangan-search">

            <i class="bi bi-search"></i>

            <input
                type="text"
                id="searchRuangan"
                placeholder="Cari nama ruangan...">

        </div>

    </div>

</section>

<!-- ==========================================================
     TOTAL RUANGAN
========================================================== -->

<section class="ruangan-total">

    <div class="container">

        <div class="ruangan-total-card">

            <div class="ruangan-total-icon">

                <i class="bi bi-door-open-fill"></i>

            </div>

            <div>

                <h3>
                    <?= $totalRuangan; ?>
                </h3>

                <p>
                    Total ruangan aktif
                </p>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================================
     DAFTAR RUANGAN PER LANTAI
========================================================== -->

<section class="ruangan-content">

    <div class="container">

        <div class="section-header">

            <div>

                <span class="section-label">
                    DATA RUANGAN

                </span>

                <h2 class="section-title">
                    Daftar Ruangan

                </h2>

                <p>

                    Pilih ruangan berdasarkan lantai untuk melihat
                    informasi lengkap mengenai ruangan tersebut.

                </p>

            </div>

        </div>


        <div id="lantaiContainer">


            <?php if (!empty($lantaiList)): ?>


                <?php foreach ($lantaiList as $lantai): ?>


                    <?php

                    $jumlahRuangan = count(
                        $lantai['ruangan']
                    );

                    ?>


                    <div
                        class="lantai-section"
                        data-lokasi="<?= htmlspecialchars(
                            strtolower($lantai['nama_lokasi'])
                        ); ?>"
                        data-lantai="<?= htmlspecialchars(
                            strtolower($lantai['nama_lantai'])
                        ); ?>">


                        <!-- ==================================
                             HEADER LANTAI
                        =================================== -->

                        <div class="lantai-header">


                            <div class="lantai-title-wrapper">


                                <div class="lantai-icon">

                                    <i class="bi bi-layers-fill"></i>

                                </div>


                                <div>

                                    <h2>

                                        <?= htmlspecialchars(
                                            $lantai['nama_lantai']
                                        ); ?>

                                    </h2>


                                    <p>

                                        <?= htmlspecialchars(
                                            $lantai['nama_lokasi']
                                        ); ?>

                                        •

                                        <?= htmlspecialchars(
                                            $lantai['kode_lantai']
                                        ); ?>

                                    </p>

                                </div>


                            </div>


                            <span class="lantai-count">

                                <?= $jumlahRuangan; ?>

                                Ruangan

                            </span>


                        </div>


                        <!-- ==================================
                             CARD RUANGAN
                        =================================== -->

                        <?php if ($jumlahRuangan > 0): ?>


                            <div class="ruangan-grid">


                                <?php foreach (
                                    $lantai['ruangan']
                                    as $ruangan
                                ): ?>


                                    <?php

                                    $namaRuangan = $ruangan[
                                        'nama_ruangan'
                                    ];

                                    $fotoRuangan =
                                        !empty(
                                            $ruangan['foto_ruangan']
                                        )
                                        ? $baseUrl .
                                          'assets/uploads/ruangan/' .
                                          $ruangan['foto_ruangan']
                                        : $baseUrl .
                                          'assets/img/no-image.png';

                                    ?>


                                    <div
                                        class="ruangan-card"
                                        data-search="<?= htmlspecialchars(
                                            strtolower(
                                                $namaRuangan .
                                                ' ' .
                                                $ruangan['kode_ruangan'] .
                                                ' ' .
                                                $lantai['nama_lantai'] .
                                                ' ' .
                                                $lantai['nama_lokasi']
                                            )
                                        ); ?>">


                                        <!-- FOTO -->

                                        <div class="ruangan-image">


                                            <?php if (
                                                !empty(
                                                    $ruangan['foto_ruangan']
                                                )
                                            ): ?>

                                                <img
                                                    src="<?= htmlspecialchars(
                                                        $fotoRuangan
                                                    ); ?>"
                                                    alt="<?= htmlspecialchars(
                                                        $namaRuangan
                                                    ); ?>">

                                            <?php else: ?>

                                                <div
                                                    class="ruangan-image-placeholder">

                                                    <i class="bi bi-door-open-fill"></i>

                                                </div>

                                            <?php endif; ?>


                                            <span class="ruangan-code">

                                                <?= htmlspecialchars(
                                                    $ruangan['kode_ruangan']
                                                ); ?>

                                            </span>


                                        </div>


                                        <!-- BODY -->

                                        <div class="ruangan-body">


                                            <h5>

                                                <?= htmlspecialchars(
                                                    $namaRuangan
                                                ); ?>

                                            </h5>


                                            <div class="ruangan-location">

                                                <i class="bi bi-geo-alt-fill"></i>

                                                <?= htmlspecialchars(
                                                    $lantai['nama_lokasi']
                                                ); ?>

                                                •

                                                <?= htmlspecialchars(
                                                    $lantai['nama_lantai']
                                                ); ?>

                                            </div>


                                            <div class="ruangan-info">


                                                <div>

                                                    <i class="bi bi-people-fill"></i>

                                                    <?= (int)
                                                        $ruangan['kapasitas'];
                                                    ?>

                                                    Orang

                                                </div>


                                                <div>

                                                    <i class="bi bi-rulers"></i>

                                                    <?= htmlspecialchars(
                                                        $ruangan['luas']
                                                    ); ?>

                                                    m²

                                                </div>


                                            </div>


                                            <?php if (
                                                !empty(
                                                    $ruangan['deskripsi']
                                                )
                                            ): ?>


                                                <p class="ruangan-desc">

                                                    <?= htmlspecialchars(
                                                        mb_strimwidth(
                                                            strip_tags(
                                                                $ruangan[
                                                                    'deskripsi'
                                                                ]
                                                            ),
                                                            0,
                                                            90,
                                                            "..."
                                                        )
                                                    ); ?>

                                                </p>


                                            <?php endif; ?>


                                            <a
                                                href="detail_ruangan.php?id=<?= $ruangan['id_ruangan']; ?>"
                                                class="btn-detail-ruangan">

                                                Lihat Detail

                                                <i class="bi bi-arrow-right"></i>

                                            </a>


                                        </div>

                                    </div>


                                <?php endforeach; ?>


                            </div>


                        <?php else: ?>


                            <div class="ruangan-empty">

                                <i class="bi bi-door-open"></i>

                                Belum ada ruangan pada lantai ini.

                            </div>


                        <?php endif; ?>


                    </div>


                <?php endforeach; ?>


            <?php else: ?>


                <div class="ruangan-empty">

                    <i class="bi bi-exclamation-circle"></i>

                    Data ruangan belum tersedia.

                </div>


            <?php endif; ?>


        </div>


        <!-- HASIL PENCARIAN KOSONG -->

        <div
            class="search-empty"
            id="searchEmpty">

            <i class="bi bi-search"></i>

            Ruangan yang kamu cari tidak ditemukan.

        </div>

    </div>

</section>

</main>

<?php include '../includes/user/footer.php'; ?>

<script>

/* ==========================================================
   SEARCH RUANGAN
========================================================== */

const searchRuangan =
    document.getElementById('searchRuangan');

const lantaiSections =
    document.querySelectorAll('.lantai-section');

const searchEmpty =
    document.getElementById('searchEmpty');


if (searchRuangan) {

    searchRuangan.addEventListener(
        'input',
        function () {


            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            let totalHasil = 0;


            lantaiSections.forEach(
                function (lantai) {


                    const cards =
                        lantai.querySelectorAll(
                            '.ruangan-card'
                        );


                    let hasilLantai = 0;


                    cards.forEach(
                        function (card) {


                            const dataSearch =
                                card.dataset.search || "";


                            if (
                                dataSearch.includes(
                                    keyword
                                )
                            ) {

                                card.style.display = "";

                                hasilLantai++;

                                totalHasil++;

                            } else {

                                card.style.display = "none";

                            }

                        }
                    );


                    if (hasilLantai > 0) {

                        lantai.style.display = "";

                    } else {

                        lantai.style.display = "none";

                    }


                }
            );


            if (
                keyword !== "" &&
                totalHasil === 0
            ) {

                searchEmpty.style.display = "block";

            } else {

                searchEmpty.style.display = "none";

            }


        }
    );

}

</script>