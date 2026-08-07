<style>

/*==================================================
INVENTARIS SECTION
==================================================*/

.inventaris-section{

    padding:60px 0 90px;

    background:#FFF9FC;

    position:relative;

}

.inventaris-section::before{

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

.inventaris-header{

    display:flex;

    justify-content:space-between;

    align-items:end;

    gap:25px;

    flex-wrap:wrap;

    margin-bottom:55px;

}

.inventaris-subtitle{

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

.inventaris-title{

    font-size:44px;

    font-weight:800;

    color:#222;

    margin-bottom:14px;

}

.inventaris-description{

    max-width:640px;

    color:#6B7280;

    font-size:17px;

    line-height:1.8;

}

.inventaris-link{

    display:inline-flex;

    align-items:center;

    gap:10px;

    text-decoration:none;

    color:#DB2777;

    font-weight:700;

    transition:.3s;

}

.inventaris-link:hover{

    color:#FB923C;

}

/*==================================================
GRID
==================================================*/

.inventaris-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:28px;

}

/*==================================================
CARD
==================================================*/

.inventaris-card{

    background:#fff;

    border-radius:28px;

    overflow:hidden;

    text-decoration:none;

    color:inherit;

    border:1px solid #FCE7F3;

    transition:.35s;

    box-shadow:0 15px 35px rgba(0,0,0,.08);

}

.inventaris-card:hover{

    transform:translateY(-10px);

    border-color:#FB923C;

    box-shadow:0 25px 50px rgba(251,146,60,.22);

}

/*==================================================
IMAGE
==================================================*/

.inventaris-image{

    position:relative;

    height:220px;

    overflow:hidden;

}

.inventaris-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.5s;

}

.inventaris-card:hover img{

    transform:scale(1.08);

}

.inventaris-overlay{

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

.inventaris-body{

    padding:24px;

}

.inventaris-name{

    font-size:24px;

    font-weight:700;

    color:#222;

    margin-bottom:8px;

    line-height:1.4;

}

.inventaris-category{

    display:inline-block;

    padding:6px 14px;

    border-radius:50px;

    background:#FFF1F2;

    color:#DB2777;

    font-size:13px;

    font-weight:600;

    margin-bottom:18px;

}

.inventaris-info{

    display:flex;

    flex-direction:column;

    gap:14px;

    margin-bottom:24px;

}

.inventaris-item{

    display:flex;

    align-items:center;

    gap:12px;

    font-size:15px;

    color:#555;

}

.inventaris-item i{

    width:36px;

    height:36px;

    display:flex;

    justify-content:center;

    align-items:center;

    border-radius:50%;

    color:#fff;

    background:linear-gradient(

        135deg,

        #EC4899,

        #FB923C

    );

    flex-shrink:0;

}

/*==================================================
BADGE KONDISI
==================================================*/

.badge-kondisi{

    display:inline-block;

    padding:6px 14px;

    border-radius:30px;

    font-size:13px;

    font-weight:700;

}

.badge-baik{

    background:#DCFCE7;

    color:#15803D;

}

.badge-ringan{

    background:#FEF3C7;

    color:#B45309;

}

.badge-berat{

    background:#FEE2E2;

    color:#B91C1C;

}

/*==================================================
BUTTON
==================================================*/

.inventaris-button{

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

.inventaris-card:hover .inventaris-button{

    letter-spacing:.5px;

    box-shadow:0 15px 35px rgba(251,146,60,.25);

}

</style>

<section class="inventaris-section" id="inventaris">

    <div class="container">

        <!-- Header -->
        <div class="inventaris-header">

            <div>

                <span class="inventaris-subtitle">

                    <i class="fas fa-box-open"></i>

                    Inventaris Kampus

                </span>

                <h2 class="inventaris-title">

                    Inventaris <br>
                    Politeknik Nest

                </h2>

                <p class="inventaris-description">

                    Lihat berbagai inventaris yang dimiliki Politeknik Nest,
                    mulai dari peralatan pembelajaran, elektronik,
                    hingga fasilitas pendukung lainnya.

                </p>

            </div>

            <a href="inventaris.php" class="inventaris-link">

                Lihat Semua

                <i class="fas fa-arrow-right"></i>

            </a>

        </div>

        <!-- Grid -->
        <div class="inventaris-grid">

            <?php if (!empty($inventarisList)) : ?>

                <?php foreach ($inventarisList as $inventaris) : ?>

                    <?php

                    $foto = !empty($inventaris['foto'])
                        ? "assets/uploads/inventaris/" . $inventaris['foto']
                        : "assets/images/no-image.jpg";

                    ?>

                    <a
                        href="detail_inventaris.php?id=<?= $inventaris['id_inventaris']; ?>"
                        class="inventaris-card">

                        <!-- Foto -->
                        <div class="inventaris-image">

                            <img
                                src="<?= $foto; ?>"
                                alt="<?= htmlspecialchars($inventaris['nama_barang']); ?>">

                            <div class="inventaris-overlay"></div>

                        </div>

                        <!-- Body -->
                        <div class="inventaris-body">

                            <h3 class="inventaris-name">

                                <?= htmlspecialchars($inventaris['nama_barang']); ?>

                            </h3>

                            <span class="inventaris-category">

                                <i class="fas fa-tag"></i>

                                <?= htmlspecialchars($inventaris['nama_kategori']); ?>

                            </span>

                            <div class="inventaris-info">

                                <!-- Merk -->
                                <div class="inventaris-item">

                                    <i class="fas fa-copyright"></i>

                                    <span>

                                        <strong>Merk :</strong>

                                        <?= !empty($inventaris['merk'])
                                            ? htmlspecialchars($inventaris['merk'])
                                            : '-'; ?>

                                    </span>

                                </div>

                                <!-- Jumlah -->
                                <div class="inventaris-item">

                                    <i class="fas fa-boxes"></i>

                                    <span>

                                        <strong>Jumlah :</strong>

                                        <?= $inventaris['jumlah']; ?> Unit

                                    </span>

                                </div>

                                <!-- Kondisi -->
                                <div class="inventaris-item">

                                    <i class="fas fa-circle-check"></i>

                                    <span>

                                        <strong>Kondisi :</strong>

                                        <?php

                                        if ($inventaris['kondisi'] == 'Baik') {

                                            echo '<span class="badge-kondisi badge-baik">Baik</span>';

                                        } elseif ($inventaris['kondisi'] == 'Rusak Ringan') {

                                            echo '<span class="badge-kondisi badge-ringan">Rusak Ringan</span>';

                                        } else {

                                            echo '<span class="badge-kondisi badge-berat">Rusak Berat</span>';

                                        }

                                        ?>

                                    </span>

                                </div>

                            </div>

                            <div class="inventaris-button">

                                Lihat Detail

                                <i class="fas fa-arrow-right"></i>

                            </div>

                                                        </div>

                        </a>

                    <?php endforeach; ?>

                <?php else : ?>

                    <div class="empty-inventaris">

                        <i class="fas fa-box-open"></i>

                        <h3>Belum Ada Data Inventaris</h3>

                        <p>
                            Data inventaris belum tersedia saat ini.
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

.empty-inventaris{

    grid-column:1/-1;

    background:#fff;

    border-radius:28px;

    padding:70px 30px;

    text-align:center;

    border:1px solid #FCE7F3;

    box-shadow:0 15px 35px rgba(0,0,0,.08);

}

.empty-inventaris i{

    font-size:60px;

    color:#EC4899;

    margin-bottom:18px;

}

.empty-inventaris h3{

    font-size:30px;

    color:#222;

    margin-bottom:12px;

    font-weight:700;

}

.empty-inventaris p{

    color:#6B7280;

    font-size:16px;

}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:1200px){

    .inventaris-grid{

    display:flex;

    overflow-x:auto;

    gap:18px;

    padding-bottom:10px;

    scroll-snap-type:x mandatory;

    scrollbar-width:none;

}

.inventaris-grid::-webkit-scrollbar{

    display:none;

}

.inventaris-card{

    min-width:250px;

    max-width:250px;

    flex:none;

    scroll-snap-align:start;

}

@media(max-width:992px){

    .inventaris-header{

        flex-direction:column;

        align-items:flex-start;

    }

    .inventaris-title{

        font-size:38px;

    }

}

@media(max-width:768px){

    .inventaris-section{

        padding:60px 0;

    }

    .inventaris-grid{

        grid-template-columns:1fr;

    }

    .inventaris-title{

        font-size:32px;

    }

    .inventaris-description{

        font-size:15px;

    }

    .inventaris-image{

        height:220px;

    }

}

@media(max-width:576px){

    .inventaris-body{

        padding:20px;

    }

    .inventaris-name{

        font-size:22px;

    }

    .inventaris-button{

        height:46px;

        font-size:15px;

    }

}

</style>