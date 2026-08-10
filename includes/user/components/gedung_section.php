<style>

/*==================================================
GEDUNG
==================================================*/

.gedung-section{

    padding:60px 0 90px;

    background:#FFF9FC;

    position:relative;

}

.gedung-section::before{

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

.gedung-header{

    position:relative;

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

    padding:10px 22px;

    border-radius:50px;

    background:#FCE7F3;

    color:#DB2777;

    font-size:14px;

    font-weight:600;

    margin-bottom:18px;

}

.gedung-title{

    font-size:44px;

    font-weight:800;

    color:#222;

    margin-bottom:14px;

}

.gedung-description{

    max-width:620px;

    color:#6B7280;

    line-height:1.8;

    font-size:17px;

}

.gedung-link{

    display:inline-flex;

    align-items:center;

    gap:10px;

    text-decoration:none;

    color:#DB2777;

    font-weight:700;

    transition:.35s;

}

.gedung-link:hover{

    color:#FB923C;

}


/*==================================================
GRID
==================================================*/

.gedung-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:28px;

}


/*==================================================
CARD
==================================================*/

.gedung-card{

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

.gedung-card:hover{

    transform:translateY(-10px);

    border-color:#FB923C;

    box-shadow:
        0 25px 50px rgba(251,146,60,.22);

}


/*==================================================
IMAGE
==================================================*/

.gedung-image{

    position:relative;

    height:210px;

    overflow:hidden;

}

.gedung-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.5s;

}

.gedung-card:hover img{

    transform:scale(1.08);

}

.gedung-overlay{

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

.gedung-body{

    padding:24px;

}

.gedung-name{

    font-size:24px;

    font-weight:700;

    color:#222;

    margin-bottom:16px;

}


/*==================================================
INFO
==================================================*/

.gedung-info{

    display:flex;

    flex-direction:column;

    gap:14px;

    margin-bottom:24px;

}

.gedung-item{

    display:flex;

    align-items:center;

    gap:12px;

    color:#555;

    font-size:15px;

}

.gedung-item i{

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

.gedung-button{

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

.gedung-card:hover .gedung-button{

    letter-spacing:.4px;

    box-shadow:
        0 15px 35px rgba(251,146,60,.28);

}


/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:1200px){

    .gedung-grid{

        display:flex;

        overflow-x:auto;

        gap:18px;

        padding-bottom:10px;

        scroll-snap-type:x mandatory;

        scrollbar-width:none;

    }

    .gedung-grid::-webkit-scrollbar{

        display:none;

    }

    .gedung-card{

        min-width:250px;

        max-width:250px;

        flex:none;

        scroll-snap-align:start;

    }

}


/*==================================================
TABLET
==================================================*/

@media(max-width:992px){

    .gedung-header{

        flex-direction:column;

        align-items:flex-start;

    }

    .gedung-title{

        font-size:38px;

    }

}


/*==================================================
MOBILE
==================================================*/

@media(max-width:768px){

    .gedung-section{

        padding:60px 0;

    }

    .gedung-grid{

        grid-template-columns:1fr;

    }

    .gedung-title{

        font-size:32px;

    }

    .gedung-description{

        font-size:15px;

    }

    .gedung-image{

        height:220px;

    }

}


/*==================================================
SMALL MOBILE
==================================================*/

@media(max-width:576px){

    .gedung-body{

        padding:20px;

    }

    .gedung-name{

        font-size:22px;

    }

    .gedung-button{

        height:45px;

        font-size:15px;

    }

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

                    Temukan informasi gedung beserta jumlah lantai dan ruangan yang tersedia di lingkungan Politeknik Nest.

                </p>

            </div>

            <a href="user/gedung.php" class="gedung-link">

                Lihat Semua

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="gedung-grid">

            <?php if(!empty($lokasiList)): ?>

                <?php foreach($lokasiList as $lokasi): ?>

                    <a
                        href="user/detail_gedung.php?id=<?= $lokasi['id_lokasi']; ?>"
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

                                <i class="bi bi-arrow-right ms-2"></i>

                            </div>

                        </div>

                    </a>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-12">

                    <div class="alert alert-light text-center rounded-4 shadow-sm">

                        Belum ada data gedung.

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>