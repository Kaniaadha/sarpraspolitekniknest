<style>

/*==================================================
GEDUNG SECTION
==================================================*/

.gedung-section{

    padding:100px 0;

    background:#fff;

}

/*=================================
HEADER
=================================*/

.gedung-header{

    display:flex;

    justify-content:space-between;

    align-items:end;

    flex-wrap:wrap;

    gap:25px;

    margin-bottom:55px;

}

.gedung-subtitle{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:10px 20px;

    border-radius:50px;

    background:#FFE7F2;

    color:#EC4899;

    font-size:14px;

    font-weight:600;

    margin-bottom:18px;

}

.gedung-title{

    font-size:42px;

    font-weight:800;

    color:#222;

    margin-bottom:12px;

}

.gedung-description{

    max-width:620px;

    color:#777;

    line-height:1.8;

    font-size:17px;

}

.gedung-link{

    display:inline-flex;

    align-items:center;

    gap:8px;

    text-decoration:none;

    color:#EC4899;

    font-weight:700;

    transition:.3s;

}

.gedung-link:hover{

    color:#FF7A48;

}

/*=================================
GRID
=================================*/

.gedung-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:28px;

}

/*=================================
CARD
=================================*/

.gedung-card{

    position:relative;

    overflow:hidden;

    background:#fff;

    border-radius:28px;

    text-decoration:none;

    color:inherit;

    transition:.35s;

    box-shadow:0 15px 35px rgba(0,0,0,.08);

}

.gedung-card:hover{

    transform:translateY(-10px);

    box-shadow:0 22px 50px rgba(236,72,153,.20);

}

/*=================================
IMAGE
=================================*/

.gedung-image{

    position:relative;

    overflow:hidden;

    height:240px;

}

.gedung-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.45s;

}

.gedung-card:hover img{

    transform:scale(1.08);

}

.gedung-overlay{

    position:absolute;

    inset:0;

    background:linear-gradient(

        rgba(0,0,0,0),

        rgba(0,0,0,.55)

    );

}

/*=================================
BODY
=================================*/

.gedung-body{

    padding:26px;

}

.gedung-name{

    font-size:24px;

    font-weight:700;

    color:#222;

    margin-bottom:20px;

}

/*=================================
INFO
=================================*/

.gedung-info{

    display:flex;

    justify-content:space-between;

    gap:15px;

    margin-bottom:24px;

}

.gedung-item{

    display:flex;

    align-items:center;

    gap:10px;

    color:#666;

    font-size:15px;

}

.gedung-item i{

    width:42px;

    height:42px;

    border-radius:50%;

    display:flex;

    justify-content:center;

    align-items:center;

    color:#fff;

    background:linear-gradient(

        135deg,

        #EC4899,

        #FF7A48

    );

}

/*=================================
BUTTON
=================================*/

.gedung-button{

    display:flex;

    justify-content:center;

    align-items:center;

    gap:8px;

    height:52px;

    border-radius:14px;

    color:#fff;

    font-weight:700;

    background:linear-gradient(

        135deg,

        #EC4899,

        #FF7A48

    );

    transition:.35s;

}

.gedung-card:hover .gedung-button{

    letter-spacing:.5px;

}
/*=================================
RESPONSIVE
=================================*/

@media(max-width:1200px){

    .gedung-grid{

        grid-template-columns:repeat(2,1fr);

    }

}

@media(max-width:768px){

    .gedung-section{

        padding:80px 0;

    }

    .gedung-header{

        flex-direction:column;

        align-items:flex-start;

    }

    .gedung-title{

        font-size:32px;

    }

    .gedung-description{

        font-size:15px;

    }

    .gedung-grid{

        grid-template-columns:1fr;

    }

    .gedung-image{

        height:220px;

    }

    .gedung-body{

        padding:22px;

    }

    .gedung-info{

        flex-direction:column;

        gap:15px;

    }

}

.empty-gedung{

    background:#fff;

    border-radius:25px;

    padding:60px 30px;

    text-align:center;

    box-shadow:0 12px 30px rgba(0,0,0,.08);

}

.empty-gedung i{

    font-size:55px;

    color:#EC4899;

    margin-bottom:20px;

}

.empty-gedung h4{

    font-size:24px;

    color:#222;

    margin-bottom:10px;

}

.empty-gedung p{

    color:#777;

}

</style>

<section class="gedung-section" id="gedung">

<div class="container">

<div class="gedung-header">

<div>

<span class="gedung-subtitle">

<i class="bi bi-building"></i>

Gedung Kampus

</span>

<h2 class="gedung-title">

Jelajahi Gedung Politeknik Nest

</h2>

<p class="gedung-description">

Temukan informasi gedung, jumlah lantai, serta jumlah ruangan yang tersedia di lingkungan Politeknik Nest.

</p>

</div>

<a href="gedung.php" class="gedung-link">

Lihat Semua

<i class="bi bi-arrow-right"></i>

</a>

</div>

<div class="gedung-grid">

<?php if(!empty($lokasiList)): ?>

<?php foreach($lokasiList as $lokasi): ?>

<a
href="gedung.php?id=<?= $lokasi['id_lokasi']; ?>"
class="gedung-card">

<div class="gedung-image">

<img
src="<?= $baseUrl ?>assets/uploads/lokasi/<?= htmlspecialchars($lokasi['nama_file']); ?>"
alt="<?= htmlspecialchars($lokasi['nama_lokasi']); ?>">

<div class="gedung-overlay"></div>

</div>

<div class="gedung-body">

<h3 class="gedung-name">

<?= htmlspecialchars($lokasi['nama_lokasi']); ?>

</h3>

<div class="gedung-info">

<div class="gedung-item">

<i class="bi bi-layers"></i>

<?= $lokasi['jumlah_lantai']; ?>

Lantai

</div>

<div class="gedung-item">

<i class="bi bi-door-open"></i>

<?= $lokasi['jumlah_ruangan']; ?>

Ruangan

</div>

</div>

<div class="gedung-button">

Lihat Detail

<i class="bi bi-arrow-right"></i>

</div>

</div>

</a>

<?php endforeach; ?>

<?php else: ?>

<div class="empty-gedung">

<i class="bi bi-building"></i>

<h4>

Belum Ada Gedung

</h4>

<p>

Data gedung belum tersedia.

</p>

</div>

<?php endif; ?>

</div>

</div>

</section>