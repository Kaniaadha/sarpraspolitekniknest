<style>

/*==================================================
RUANGAN
==================================================*/

.ruangan-section{

    padding:60px 0 90px;

    background:#FFF9FC;

    position:relative;

}

.ruangan-section::before{

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

.ruangan-header{

    position:relative;

    display:flex;

    justify-content:space-between;

    align-items:end;

    flex-wrap:wrap;

    gap:25px;

    margin-bottom:55px;

}

.ruangan-subtitle{

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

.ruangan-title{

    font-size:44px;

    font-weight:800;

    color:#222;

    margin-bottom:14px;

}

.ruangan-description{

    max-width:620px;

    color:#6B7280;

    line-height:1.8;

    font-size:17px;

}

.ruangan-link{

    display:inline-flex;

    align-items:center;

    gap:10px;

    text-decoration:none;

    color:#DB2777;

    font-weight:700;

    transition:.35s;

}

.ruangan-link:hover{

    color:#FB923C;

}

/*==================================================
GRID
==================================================*/

.ruangan-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:28px;

}

/*==================================================
CARD
==================================================*/

.ruangan-card{

    position:relative;

    overflow:hidden;

    background:#fff;

    border-radius:28px;

    border:1px solid #FCE7F3;

    text-decoration:none;

    color:inherit;

    transition:.35s;

    box-shadow:0 15px 35px rgba(0,0,0,.08);

}

.ruangan-card:hover{

    transform:translateY(-10px);

    border-color:#FB923C;

    box-shadow:0 25px 50px rgba(251,146,60,.22);

}

/*==================================================
IMAGE
==================================================*/

.ruangan-image{

    position:relative;

    height:210px;

    overflow:hidden;

}

.ruangan-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.5s;

}

.ruangan-card:hover img{

    transform:scale(1.08);

}

.ruangan-overlay{

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

.ruangan-body{

    padding:24px;

}

.ruangan-name{

    font-size:24px;

    font-weight:700;

    color:#222;

    margin-bottom:16px;

}

.ruangan-desc{

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

.ruangan-info{

    display:flex;

    flex-direction:column;

    gap:14px;

    margin-bottom:24px;

}

.ruangan-item{

    display:flex;

    align-items:center;

    gap:12px;

    color:#555;

    font-size:15px;

}

.ruangan-item i{

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

        #FB923C

    );

    flex-shrink:0;

}

/*==================================================
BUTTON
==================================================*/

.ruangan-button{

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

        #FB923C

    );

    transition:.35s;

}

.ruangan-card:hover .ruangan-button{

    letter-spacing:.4px;

    box-shadow:0 15px 35px rgba(251,146,60,.28);

}

</style>

<section class="ruangan-section" id="ruangan">

    <div class="container">

        <!-- Header -->
        <div class="ruangan-header">

            <div>

                <span class="ruangan-subtitle">

                    <i class="fas fa-door-open"></i>

                    Ruangan Kampus

                </span>

                <h2 class="ruangan-title">

                    Jelajahi Ruangan <br>
                    Politeknik Nest

                </h2>

                <p class="ruangan-description">

                    Temukan berbagai ruang kelas, laboratorium, ruang rapat,
                    studio, hingga ruang pendukung lainnya yang tersedia di
                    lingkungan Politeknik Nest.

                </p>

            </div>

            <a href="ruangan.php" class="ruangan-link">

                Lihat Semua

                <i class="fas fa-arrow-right"></i>

            </a>

        </div>

        <!-- Grid -->
        <div class="ruangan-grid">

            <?php if (!empty($ruanganList)) : ?>

                <?php foreach ($ruanganList as $ruangan) : ?>

                    <?php

                    $foto = !empty($ruangan['nama_file'])
                        ? "assets/uploads/ruangan/" . $ruangan['nama_file']
                        : "assets/images/no-image.jpg";

                    ?>

                    <a
                        href="detail_ruangan.php?id=<?= $ruangan['id_ruangan']; ?>"
                        class="ruangan-card">

                        <!-- Foto -->
                        <div class="ruangan-image">

                            <img
                                src="<?= $foto; ?>"
                                alt="<?= htmlspecialchars($ruangan['nama_ruangan']); ?>">

                            <div class="ruangan-overlay"></div>

                        </div>

                        <!-- Body -->
                        <div class="ruangan-body">

                            <h3 class="ruangan-name">

                                <?= htmlspecialchars($ruangan['nama_ruangan']); ?>

                            </h3>

                            <p class="ruangan-desc">

                                <?= !empty($ruangan['deskripsi'])
                                    ? htmlspecialchars($ruangan['deskripsi'])
                                    : 'Belum memiliki deskripsi.'; ?>

                            </p>

                            <div class="ruangan-info">

                                <!-- Lokasi -->
                                <div class="ruangan-item">

                                    <i class="fas fa-building"></i>

                                    <span>

                                        <strong>Gedung :</strong>

                                        <?= htmlspecialchars($ruangan['nama_lokasi']); ?>

                                    </span>

                                </div>

                                <!-- Lantai -->
                                <div class="ruangan-item">

                                    <i class="fas fa-layer-group"></i>

                                    <span>

                                        <strong>Lantai :</strong>

                                        <?= htmlspecialchars($ruangan['nama_lantai']); ?>

                                    </span>

                                </div>

                                <!-- Kapasitas -->
                                <div class="ruangan-item">

                                    <i class="fas fa-users"></i>

                                    <span>

                                        <strong>Kapasitas :</strong>

                                        <?= !empty($ruangan['kapasitas'])
                                            ? $ruangan['kapasitas'] . ' Orang'
                                            : '-'; ?>

                                    </span>

                                </div>

                                <!-- Luas -->
                                <div class="ruangan-item">

                                    <i class="fas fa-ruler-combined"></i>

                                    <span>

                                        <strong>Luas :</strong>

                                        <?= !empty($ruangan['luas'])
                                            ? number_format($ruangan['luas'], 0, ',', '.') . ' m²'
                                            : '-'; ?>

                                    </span>

                                </div>

                            </div>

                            <div class="ruangan-button">

                                Lihat Detail

                                <i class="fas fa-arrow-right"></i>

                            </div>
                                                        </div>

                        </a>

                    <?php endforeach; ?>

                <?php else : ?>

                    <div class="empty-ruangan">

                        <i class="fas fa-door-open"></i>

                        <h3>

                            Belum Ada Data Ruangan

                        </h3>

                        <p>

                            Data ruangan belum tersedia saat ini.

                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>

</section>

<style>

/*==================================================
EMPTY STATE
==================================================*/

.empty-ruangan{

    grid-column:1/-1;

    background:#fff;

    border-radius:28px;

    padding:70px 30px;

    text-align:center;

    border:1px solid #FCE7F3;

    box-shadow:0 15px 35px rgba(0,0,0,.08);

}

.empty-ruangan i{

    font-size:60px;

    color:#EC4899;

    margin-bottom:18px;

}

.empty-ruangan h3{

    font-size:28px;

    font-weight:700;

    color:#222;

    margin-bottom:12px;

}

.empty-ruangan p{

    color:#6B7280;

    font-size:16px;

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:1200px){

    .ruangan-grid{

    display:flex;

    overflow-x:auto;

    gap:18px;

    padding-bottom:10px;

    scroll-snap-type:x mandatory;

    scrollbar-width:none;

}

.ruangan-grid::-webkit-scrollbar{

    display:none;

}

.ruangan-card{

    min-width:250px;

    max-width:250px;

    flex:none;

    scroll-snap-align:start;

}

@media(max-width:992px){

    .ruangan-header{

        flex-direction:column;

        align-items:flex-start;

    }

    .ruangan-title{

        font-size:38px;

    }

}

@media(max-width:768px){

    .ruangan-section{

        padding:60px 0;

    }

    .ruangan-grid{

        grid-template-columns:1fr;

    }

    .ruangan-title{

        font-size:32px;

    }

    .ruangan-description{

        font-size:15px;

    }

    .ruangan-image{

        height:220px;

    }

}

@media(max-width:576px){

    .ruangan-body{

        padding:20px;

    }

    .ruangan-name{

        font-size:22px;

    }

    .ruangan-button{

        height:45px;

        font-size:15px;

    }

}
</style>