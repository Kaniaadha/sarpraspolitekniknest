<style>

/*==================================================
PUBLIC SPACE
==================================================*/

.public-section{

    padding:60px 0 90px;

    background:#FFF9FC;

    position:relative;

}

.public-section::before{

    content:"";

    position:absolute;

    top:0;

    left:0;

    width:100%;

    height:220px;

    background:linear-gradient(

        180deg,

        rgba(236,72,153,.05),

        transparent

    );

    pointer-events:none;

}

/*==================================================
HEADER
==================================================*/

.public-header{

    position:relative;

    display:flex;

    justify-content:space-between;

    align-items:end;

    flex-wrap:wrap;

    gap:25px;

    margin-bottom:55px;

}

.public-subtitle{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:10px 22px;

    border-radius:50px;

    background:#FCE7F3;

    color:#DB2777;

    font-size:14px;

    font-weight:600;

    margin-bottom:18px;

}

.public-title{

    font-size:44px;

    font-weight:800;

    color:#222;

    margin-bottom:14px;

}

.public-description{

    max-width:620px;

    color:#6B7280;

    line-height:1.8;

    font-size:17px;

}

.public-link{

    display:inline-flex;

    align-items:center;

    gap:10px;

    text-decoration:none;

    color:#DB2777;

    font-weight:700;

    transition:.35s;

}

.public-link:hover{

    color:#8B5CF6;

}

/*==================================================
GRID
==================================================*/

.public-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:28px;

}

/*==================================================
CARD
==================================================*/

.public-card{

    position:relative;

    overflow:hidden;

    background:#fff;

    border-radius:28px;

    border:1px solid #F3E8FF;

    text-decoration:none;

    color:inherit;

    transition:.35s;

    box-shadow:0 15px 35px rgba(0,0,0,.08);

}

.public-card:hover{

    transform:translateY(-10px);

    border-color:#C084FC;

    box-shadow:0 25px 50px rgba(168,85,247,.18);

}

/*==================================================
IMAGE
==================================================*/

.public-image{

    position:relative;

    height:200px;

    overflow:hidden;

}

.public-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.5s;

}

.public-card:hover img{

    transform:scale(1.08);

}

.public-overlay{

    position:absolute;

    inset:0;

    background:linear-gradient(

        rgba(0,0,0,0),

        rgba(0,0,0,.45)

    );

}

/*==================================================
BODY
==================================================*/

.public-body{

    padding:24px;

}

.public-name{

    font-size:24px;

    font-weight:700;

    color:#222;

    margin-bottom:16px;

}

.public-desc{

    color:#6B7280;

    font-size:15px;

    line-height:1.7;

    margin-bottom:22px;

    display:-webkit-box;

    -webkit-line-clamp:2;

    -webkit-box-orient:vertical;

    overflow:hidden;

    min-height:52px;

}

/*==================================================
INFO
==================================================*/

.public-info{

    display:flex;

    flex-direction:column;

    gap:14px;

    margin-bottom:24px;

}

.public-item{

    display:flex;

    align-items:center;

    gap:12px;

    color:#555;

    font-size:15px;

}

.public-item i{

    width:36px;

    height:36px;

    border-radius:50%;

    display:flex;

    justify-content:center;

    align-items:center;

    color:#fff;

    background:linear-gradient(

        135deg,

        #EC4899,

        #8B5CF6

    );

    flex-shrink:0;

}

/*==================================================
BUTTON
==================================================*/

.public-button{

    display:flex;

    justify-content:center;

    align-items:center;

    gap:8px;

    height:48px;

    border-radius:14px;

    color:#fff;

    font-weight:700;

    background:linear-gradient(

        135deg,

        #EC4899,

        #8B5CF6

    );

    transition:.35s;

}

.public-card:hover .public-button{

    letter-spacing:.4px;

    box-shadow:0 15px 35px rgba(168,85,247,.28);

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:1200px){

   .public-grid{

    display:flex;

    overflow-x:auto;

    gap:18px;

    padding-bottom:10px;

    scroll-snap-type:x mandatory;

    scrollbar-width:none;

}

.public-grid::-webkit-scrollbar{

    display:none;

}

.public-card{

    min-width:250px;

    max-width:250px;

    flex:none;

    scroll-snap-align:start;

}

@media(max-width:768px){

    .public-section{

        padding:45px 0 70px;

    }

    .public-header{

        flex-direction:column;

        align-items:flex-start;

    }

    .public-title{

        font-size:32px;

    }

    .public-description{

        font-size:15px;

    }

    .public-grid{

        grid-template-columns:1fr;

    }

    .public-image{

        height:210px;

    }

}

/*==================================================
EMPTY STATE
==================================================*/

.empty-public{

    background:#fff;

    border:1px solid #F3E8FF;

    border-radius:28px;

    padding:60px 35px;

    text-align:center;

    box-shadow:0 15px 35px rgba(0,0,0,.06);

}

.empty-public i{

    font-size:60px;

    color:#DB2777;

    margin-bottom:20px;

}

.empty-public h4{

    font-size:24px;

    font-weight:700;

    color:#222;

    margin-bottom:10px;

}

.empty-public p{

    color:#6B7280;

}

</style>

<section class="public-section" id="public-space">

<div class="container">

<div class="public-header">

<div>

<span class="public-subtitle">

<i class="fas fa-tree"></i>

Public Space

</span>

<h2 class="public-title">

Area Public Space

</h2>

<p class="public-description">

Temukan berbagai area publik di lingkungan Politeknik Nest yang dapat digunakan untuk kegiatan akademik, diskusi, maupun aktivitas mahasiswa.

</p>

</div>

<a
href="public_space.php"
class="public-link">

Lihat Semua

<i class="fas fa-arrow-right"></i>

</a>

</div>

<div class="public-grid">

<?php if(!empty($publicSpaceList)): ?>

<?php foreach($publicSpaceList as $public): ?>

<a
href="public_space.php?id=<?= $public['id_public_space']; ?>"
class="public-card">

<div class="public-image">

<?php

$foto = !empty($public['nama_file'])
    ? "assets/uploads/public_space/".$public['nama_file']
    : "assets/images/no-image.jpg";

?>

<img
src="<?= $foto; ?>"
alt="<?= htmlspecialchars($public['nama_public_space']); ?>">

<div class="public-overlay"></div>

</div>

<div class="public-body">

<h3 class="public-name">

<?= htmlspecialchars($public['nama_public_space']); ?>

</h3>

<p class="public-desc">

<?= !empty($public['deskripsi'])
? htmlspecialchars($public['deskripsi'])
: 'Belum memiliki deskripsi.'; ?>

</p>

<div class="public-info">

<?php if(!empty($public['luas'])): ?>

<div class="public-item">

    <i class="fas fa-expand-arrows-alt"></i>

    <?= number_format($public['luas'],0,',','.'); ?> m²

</div>

<?php endif; ?>

<div class="public-item">

    <i class="fas fa-hashtag"></i>

    <?= htmlspecialchars($public['kode_public_space']); ?>

</div>

</div>

<div class="public-button">

    Lihat Detail

    <i class="fas fa-arrow-right"></i>

</div>

</div>

</a>

<?php endforeach; ?>

<?php else: ?>

<div class="empty-public">

    <i class="fas fa-tree"></i>

    <h4>

        Belum Ada Public Space

    </h4>

    <p>

        Data public space belum tersedia saat ini.

    </p>

</div>

<?php endif; ?>

</div>

</div>

</section>