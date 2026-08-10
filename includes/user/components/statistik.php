<style>
/*==================================================
STATISTIK
==================================================*/

.statistik-section{

    padding:20px 0;

    background:linear-gradient(
        180deg,
        #FFF8FB 0%,
        #FFFFFF 100%
    );

}

.statistik-header{

    text-align:center;

    margin-bottom:55px;

}

.statistik-badge{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:10px 22px;

    border-radius:50px;

    background:#FFE7F2;

    color:#F45B8D;

    font-weight:600;

    margin-bottom:18px;

}

.statistik-title{

    font-size:40px;

    font-weight:700;

    color:#2D2D2D;

    margin-bottom:15px;

}

.statistik-subtitle{

    max-width:700px;

    margin:auto;

    color:#777;

    line-height:1.8;

}

/*=================================
GRID
=================================*/

.statistik-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:28px;

}

/*=================================
CARD
=================================*/

.stat-card{

    display:block;

    position:relative;

    overflow:hidden;

    background:#fff;

    border-radius:28px;

    padding:35px;

    text-align:center;

    text-decoration:none;

    transition:.35s;

    box-shadow:0 12px 30px rgba(0,0,0,.07);

}

.stat-card:hover{

    transform:translateY(-10px);

    box-shadow:0 25px 45px rgba(0,0,0,.12);

}

.stat-card::before{

    content:"";

    position:absolute;

    width:180px;

    height:180px;

    border-radius:50%;

    right:-80px;

    top:-80px;

    opacity:.08;

}

/*=================================
ICON
=================================*/

.stat-icon{

    width:82px;

    height:82px;

    margin:auto;

    border-radius:50%;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:34px;

    color:#fff;

    margin-bottom:22px;

    transition:.35s;

}

.stat-card:hover .stat-icon{

    transform:scale(1.12);

}

/*=================================
NUMBER
=================================*/

.stat-number{

    font-size:46px;

    font-weight:700;

    color:#2D2D2D;

    line-height:1;

    margin-bottom:12px;

}

/*=================================
TITLE
=================================*/

.stat-label{

    font-size:18px;

    font-weight:600;

    color:#444;

}

/*=================================
LINK
=================================*/

.stat-link{

    margin-top:20px;

    display:inline-flex;

    align-items:center;

    gap:8px;

    color:#888;

    font-size:14px;

    transition:.3s;

}

.stat-card:hover .stat-link{

    color:#F45B8D;

}

/*=================================
GEDUNG
=================================*/

.card-gedung::before{

    background:#F45B8D;

}

.card-gedung .stat-icon{

    background:linear-gradient(
        135deg,
        #F45B8D,
        #FF74A6
    );

}

/*=================================
RUANGAN
=================================*/

.card-ruangan::before{

    background:#FF8A3D;

}

.card-ruangan .stat-icon{

    background:linear-gradient(
        135deg,
        #FF8A3D,
        #FFB347
    );

}

/*=================================
PUBLIC SPACE
=================================*/

.card-public::before{

    background:#10B981;

}

.card-public .stat-icon{

    background:linear-gradient(
        135deg,
        #10B981,
        #34D399
    );

}

/*=================================
INVENTARIS
=================================*/

.card-inventaris::before{

    background:#A855F7;

}

.card-inventaris .stat-icon{

    background:linear-gradient(
        135deg,
        #A855F7,
        #EC4899
    );

}

/*=================================
RESPONSIVE
=================================*/

@media(max-width:768px){

    .statistik-section{

        padding:60px 0;

        overflow:hidden;

    }

    .statistik-title{

        font-size:30px;

    }

    .statistik-subtitle{

        font-size:15px;

        padding:0 15px;

    }

    .statistik-grid{

        display:flex;

        flex-wrap:nowrap;

        overflow-x:auto;

        gap:18px;

        padding:10px 20px;

        scroll-snap-type:x mandatory;

        -webkit-overflow-scrolling:touch;

        scrollbar-width:none;

    }

    .statistik-grid::-webkit-scrollbar{

        display:none;

    }

    .stat-card{

        flex:0 0 220px;

        max-width:220px;

        padding:28px;

        scroll-snap-align:start;

    }

    .stat-icon{

        width:68px;

        height:68px;

        font-size:28px;

    }

    .stat-number{

        font-size:36px;

    }

}

@media(max-width:768px){

    .statistik-section{

        padding:70px 0;

    }

    .statistik-title{

        font-size:30px;

    }

    .statistik-subtitle{

        font-size:15px;

    }

    .statistik-grid{

        grid-template-columns:1fr;

    }

    .stat-card{

        padding:30px;

    }

    .stat-icon{

        width:70px;

        height:70px;

        font-size:30px;

    }

    .stat-number{

        font-size:38px;

    }

}

</style>

<section class="statistik-section">

<div class="container">

<div class="statistik-header">

<span class="statistik-badge">

<i class="bi bi-bar-chart-fill"></i>

Statistik Kampus

</span>

<h2 class="statistik-title">

Data Sarana & Prasarana

</h2>

<p class="statistik-subtitle">

Lihat ringkasan jumlah gedung, ruangan, public space, dan inventaris
yang tersedia di lingkungan Politeknik Nest.

</p>

</div>

<div class="statistik-grid">

<!-- ================= GEDUNG ================= -->

<a href="gedung.php"

class="stat-card card-gedung">

<div class="stat-icon">

<i class="bi bi-building"></i>

</div>

<div class="stat-number">

<?= number_format($statistik['lokasi']) ?>

</div>

<div class="stat-label">

Gedung

</div>

<div class="stat-link">

Lihat Detail

<i class="bi bi-arrow-right"></i>

</div>

</a>

<!-- ================= RUANGAN ================= -->

<a href="ruangan.php"

class="stat-card card-ruangan">

<div class="stat-icon">

<i class="bi bi-door-open"></i>

</div>

<div class="stat-number">

<?= number_format($statistik['ruangan']) ?>

</div>

<div class="stat-label">

Ruangan

</div>

<div class="stat-link">

Lihat Detail

<i class="bi bi-arrow-right"></i>

</div>

</a>

<!-- ================= PUBLIC SPACE ================= -->

<a href="public_space.php"

class="stat-card card-public">

<div class="stat-icon">

<i class="bi bi-tree"></i>

</div>

<div class="stat-number">

<?= number_format($statistik['public_space']) ?>

</div>

<div class="stat-label">

Public Space

</div>

<div class="stat-link">

Lihat Detail

<i class="bi bi-arrow-right"></i>

</div>

</a>


<!-- ================= INVENTARIS ================= -->

<a href="inventaris.php"

class="stat-card card-inventaris">

<div class="stat-icon">

<i class="bi bi-box-seam"></i>

</div>

<div class="stat-number">

<?= number_format($statistik['inventaris']) ?>

</div>

<div class="stat-label">

Inventaris

</div>

<div class="stat-link">

Lihat Detail

<i class="bi bi-arrow-right"></i>

</div>

</a>

</div>

</div>

</section>