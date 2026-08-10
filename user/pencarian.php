<?php

session_start();

require_once '../config/database.php';
require_once '../helpers/pencarian_helpers.php';


/* ==================================================
   SETTING HALAMAN
================================================== */

$baseUrl = "../";

$pageTitle = "Pencarian | SISARPRAS";

$currentPage = "pencarian";


/* ==================================================
   AMBIL KEYWORD
================================================== */

$keyword = isset($_GET['keyword'])
    ? trim($_GET['keyword'])
    : '';


/* ==================================================
   HASIL PENCARIAN
================================================== */

$hasilPencarian = [];

if ($keyword !== '') {

    $hasilPencarian = cariFasilitas(
        $conn,
        $keyword
    );

}


/* ==================================================
   HEADER
================================================== */

include '../includes/user/header.php';

?>

<body>


<?php

/* ==================================================
   NAVBAR
================================================== */

include '../includes/user/navbar.php';

?>


<style>

/*==================================================
PAGE
==================================================*/

.pencarian-page{

    min-height:100vh;

    background:#FFF8FC;

    padding-bottom:80px;

}


/*==================================================
HERO
==================================================*/

.pencarian-hero{

    position:relative;

    overflow:hidden;

    background:linear-gradient(
        135deg,
        #EC4899 0%,
        #FF7A45 100%
    );

    padding:70px 0 110px;

}

.pencarian-hero::before{

    content:"";

    position:absolute;

    width:420px;

    height:420px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    top:-190px;

    right:-120px;

}

.pencarian-hero::after{

    content:"";

    position:absolute;

    width:260px;

    height:260px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    left:-90px;

    bottom:-130px;

}

.pencarian-hero .container{

    position:relative;

    z-index:2;

}


/*==================================================
BREADCRUMB
==================================================*/

.pencarian-breadcrumb{

    display:flex;

    align-items:center;

    gap:8px;

    margin-bottom:20px;

    font-size:14px;

}

.pencarian-breadcrumb a{

    color:#fff;

    text-decoration:none;

}

.pencarian-breadcrumb span{

    color:rgba(255,255,255,.75);

}


/*==================================================
BADGE
==================================================*/

.pencarian-badge{

    display:inline-flex;

    align-items:center;

    gap:9px;

    padding:10px 18px;

    border-radius:50px;

    background:rgba(255,255,255,.18);

    backdrop-filter:blur(12px);

    color:#fff;

    font-size:14px;

    font-weight:600;

    margin-bottom:18px;

}


/*==================================================
TITLE
==================================================*/

.pencarian-title{

    color:#fff;

    font-size:46px;

    font-weight:800;

    line-height:1.2;

    margin-bottom:15px;

}

.pencarian-description{

    max-width:700px;

    color:rgba(255,255,255,.92);

    font-size:16px;

    line-height:1.8;

    margin:0;

}


/*==================================================
SEARCH FORM
==================================================*/

.pencarian-form{

    margin-top:32px;

    max-width:800px;

}

.pencarian-search-box{

    display:flex;

    align-items:center;

    overflow:hidden;

    background:#fff;

    border-radius:60px;

    box-shadow:
        0 15px 35px rgba(0,0,0,.15);

}

.pencarian-search-box input{

    flex:1;

    height:65px;

    border:none;

    outline:none;

    padding:0 25px;

    font-size:16px;

    color:#444;

}

.pencarian-search-box input::placeholder{

    color:#999;

}

.pencarian-search-box button{

    width:75px;

    height:65px;

    border:none;

    color:#fff;

    font-size:20px;

    cursor:pointer;

    background:linear-gradient(
        135deg,
        #F45B8D,
        #FF8A3D
    );

    transition:.3s;

}

.pencarian-search-box button:hover{

    transform:scale(1.04);

}


/*==================================================
RESULT SECTION
==================================================*/

.pencarian-result-section{

    margin-top:-55px;

    position:relative;

    z-index:5;

}

.pencarian-result-card{

    background:#fff;

    border-radius:28px;

    padding:40px;

    box-shadow:
        0 15px 40px rgba(0,0,0,.08);

}


/*==================================================
RESULT HEADER
==================================================*/

.result-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:20px;

    margin-bottom:30px;

    flex-wrap:wrap;

}

.result-title{

    margin:0;

    font-size:26px;

    font-weight:700;

    color:#222;

}

.result-title span{

    color:#EC4899;

}

.result-count{

    display:inline-flex;

    align-items:center;

    gap:7px;

    padding:8px 15px;

    border-radius:50px;

    background:#FFF1F7;

    color:#DB2777;

    font-size:13px;

    font-weight:600;

}


/*==================================================
RESULT GRID
==================================================*/

.hasil-grid{

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:22px;

}


/*==================================================
RESULT ITEM
==================================================*/

.hasil-card{

    display:flex;

    flex-direction:column;

    background:#fff;

    border:1px solid #FCE7F3;

    border-radius:22px;

    padding:24px;

    text-decoration:none;

    color:inherit;

    transition:.35s;

}

.hasil-card:hover{

    transform:translateY(-7px);

    border-color:#FB923C;

    box-shadow:
        0 18px 35px rgba(251,146,60,.15);

}


/*==================================================
RESULT ICON
==================================================*/

.hasil-icon{

    width:55px;

    height:55px;

    display:flex;

    justify-content:center;

    align-items:center;

    border-radius:16px;

    color:#fff;

    font-size:22px;

    margin-bottom:18px;

    background:linear-gradient(
        135deg,
        #EC4899,
        #FB923C
    );

}


/*==================================================
TYPE
==================================================*/

.hasil-type{

    display:inline-flex;

    align-items:center;

    width:max-content;

    padding:6px 12px;

    border-radius:50px;

    background:#FFF1F7;

    color:#DB2777;

    font-size:12px;

    font-weight:700;

    margin-bottom:12px;

}


/*==================================================
TITLE
==================================================*/

.hasil-title{

    font-size:20px;

    font-weight:700;

    color:#222;

    margin-bottom:8px;

    line-height:1.4;

}

.hasil-code{

    color:#888;

    font-size:13px;

    margin-bottom:14px;

    font-weight:500;

}


/*==================================================
DESCRIPTION
==================================================*/

.hasil-description{

    color:#6B7280;

    font-size:14px;

    line-height:1.7;

    margin-bottom:15px;

    display:-webkit-box;

    -webkit-line-clamp:2;

    -webkit-box-orient:vertical;

    overflow:hidden;

}


/*==================================================
INFO
==================================================*/

.hasil-info{

    display:flex;

    align-items:center;

    gap:8px;

    color:#555;

    font-size:14px;

    margin-top:auto;

    padding-top:15px;

    border-top:1px solid #f3f3f3;

}

.hasil-info i{

    color:#EC4899;

}


/*==================================================
DETAIL LINK
==================================================*/

.hasil-link{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-top:18px;

    color:#DB2777;

    font-size:14px;

    font-weight:700;

}

.hasil-link i{

    transition:.3s;

}

.hasil-card:hover .hasil-link i{

    transform:translateX(5px);

}


/*==================================================
EMPTY / NO RESULT
==================================================*/

.empty-result{

    text-align:center;

    padding:70px 30px;

}

.empty-result-icon{

    width:90px;

    height:90px;

    display:flex;

    justify-content:center;

    align-items:center;

    margin:0 auto 25px;

    border-radius:50%;

    background:#FFF1F7;

    color:#EC4899;

    font-size:38px;

}

.empty-result h3{

    font-size:25px;

    font-weight:700;

    color:#222;

    margin-bottom:10px;

}

.empty-result p{

    max-width:520px;

    margin:0 auto;

    color:#777;

    line-height:1.7;

}


/*==================================================
INITIAL STATE
==================================================*/

.initial-result{

    text-align:center;

    padding:55px 20px;

}

.initial-result i{

    font-size:50px;

    color:#F9A8D4;

    margin-bottom:18px;

}

.initial-result h3{

    font-size:23px;

    font-weight:700;

    color:#333;

    margin-bottom:10px;

}

.initial-result p{

    color:#777;

    margin:0;

}


/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:992px){

    .hasil-grid{

        grid-template-columns:
            repeat(2,1fr);

    }

    .pencarian-title{

        font-size:38px;

    }

}


@media(max-width:768px){

    .pencarian-hero{

        padding:55px 0 100px;

    }

    .pencarian-title{

        font-size:32px;

    }

    .pencarian-result-card{

        padding:28px;

        border-radius:22px;

    }

    .hasil-grid{

        grid-template-columns:1fr;

    }

    .pencarian-search-box input{

        height:58px;

        padding:0 20px;

    }

    .pencarian-search-box button{

        width:65px;

        height:58px;

    }

}


@media(max-width:576px){

    .pencarian-description{

        font-size:15px;

    }

    .result-title{

        font-size:22px;

    }

    .pencarian-result-card{

        padding:22px;

    }

}

</style>


<main class="pencarian-page">


    <!-- ==================================================
         HERO
    ================================================== -->

    <section class="pencarian-hero">

        <div class="container">


            <div class="pencarian-breadcrumb">

                <a href="../index.php">

                    Beranda

                </a>

                <span>/</span>

                <span>Pencarian</span>

            </div>


            <span class="pencarian-badge">

                <i class="fas fa-search"></i>

                Pencarian Sarana & Prasarana

            </span>


            <h1 class="pencarian-title">

                Cari Fasilitas Kampus

            </h1>


            <p class="pencarian-description">

                Temukan informasi gedung, ruangan,
                public space, maupun inventaris
                Politeknik Nest dengan cepat.

            </p>


            <!-- SEARCH -->

            <form
                action="pencarian.php"
                method="GET"
                class="pencarian-form">

                <div class="pencarian-search-box">

                    <input
                        type="text"
                        name="keyword"
                        value="<?= htmlspecialchars($keyword); ?>"
                        placeholder="Cari gedung, ruangan, public space atau inventaris..."
                        autocomplete="off"
                        autofocus>

                    <button type="submit">

                        <i class="fas fa-search"></i>

                    </button>

                </div>

            </form>


        </div>

    </section>


    <!-- ==================================================
         HASIL
    ================================================== -->

    <section class="pencarian-result-section">

        <div class="container">

            <div class="pencarian-result-card">


                <?php if ($keyword === '') : ?>


                    <!-- INITIAL -->

                    <div class="initial-result">

                        <i class="fas fa-search"></i>

                        <h3>
                            Mulai Pencarian
                        </h3>

                        <p>
                            Masukkan nama gedung,
                            ruangan, public space,
                            atau inventaris pada kolom
                            pencarian di atas.
                        </p>

                    </div>


                <?php elseif (empty($hasilPencarian)) : ?>


                    <!-- NO RESULT -->

                    <div class="empty-result">

                        <div class="empty-result-icon">

                            <i class="fas fa-search-minus"></i>

                        </div>


                        <h3>
                            Data Tidak Ditemukan
                        </h3>


                        <p>

                            Maaf, kami tidak menemukan
                            fasilitas yang sesuai dengan
                            kata pencarian

                            <strong>
                                "<?= htmlspecialchars($keyword); ?>"
                            </strong>.

                            Coba gunakan kata kunci lain.

                        </p>

                    </div>


                <?php else : ?>


                    <!-- RESULT HEADER -->

                    <div class="result-header">

                        <h2 class="result-title">

                            Hasil pencarian untuk

                            <span>
                                "<?= htmlspecialchars($keyword); ?>"
                            </span>

                        </h2>


                        <span class="result-count">

                            <i class="fas fa-list"></i>

                            <?= count($hasilPencarian); ?>

                            hasil

                        </span>

                    </div>


                    <!-- RESULT GRID -->

                    <div class="hasil-grid">


                        <?php foreach (
                            $hasilPencarian
                            as $hasil
                        ) : ?>


                            <?php

                            /*
                             * Icon berdasarkan jenis data
                             */

                            switch ($hasil['jenis']) {

                                case 'Gedung':

                                    $icon =
                                        'fa-building';

                                    break;

                                case 'Ruangan':

                                    $icon =
                                        'fa-door-open';

                                    break;

                                case 'Public Space':

                                    $icon =
                                        'fa-tree';

                                    break;

                                case 'Inventaris':

                                    $icon =
                                        'fa-box-open';

                                    break;

                                default:

                                    $icon =
                                        'fa-circle';

                                    break;

                            }

                            ?>


                            <a
                                href="<?= htmlspecialchars(
                                    $hasil['url']
                                ); ?>"
                                class="hasil-card">


                                <!-- ICON -->

                                <div class="hasil-icon">

                                    <i class="
                                        fas
                                        <?= $icon; ?>
                                    "></i>

                                </div>


                                <!-- TYPE -->

                                <span class="hasil-type">

                                    <?= htmlspecialchars(
                                        $hasil['jenis']
                                    ); ?>

                                </span>


                                <!-- TITLE -->

                                <h3 class="hasil-title">

                                    <?= htmlspecialchars(
                                        $hasil['judul']
                                    ); ?>

                                </h3>


                                <!-- CODE -->

                                <?php if (
                                    !empty($hasil['kode'])
                                ) : ?>

                                    <div class="hasil-code">

                                        <?= htmlspecialchars(
                                            $hasil['kode']
                                        ); ?>

                                    </div>

                                <?php endif; ?>


                                <!-- DESCRIPTION -->

                                <?php if (
                                    !empty(
                                        $hasil['deskripsi']
                                    )
                                ) : ?>

                                    <p class="
                                        hasil-description
                                    ">

                                        <?= htmlspecialchars(
                                            $hasil['deskripsi']
                                        ); ?>

                                    </p>

                                <?php endif; ?>


                                <!-- INFO -->

                                <?php if (
                                    !empty(
                                        $hasil['info']
                                    )
                                ) : ?>

                                    <div class="hasil-info">

                                        <i class="
                                            fas
                                            fa-info-circle
                                        "></i>

                                        <span>

                                            <?= htmlspecialchars(
                                                $hasil['info']
                                            ); ?>

                                        </span>

                                    </div>

                                <?php endif; ?>


                                <!-- LINK -->

                                <div class="hasil-link">

                                    Lihat Detail

                                    <i class="
                                        fas
                                        fa-arrow-right
                                    "></i>

                                </div>


                            </a>


                        <?php endforeach; ?>


                    </div>


                <?php endif; ?>


            </div>

        </div>

    </section>


</main>


<?php

/* ==================================================
   FOOTER
================================================== */

include '../includes/user/footer.php';

?>


</body>

</html>