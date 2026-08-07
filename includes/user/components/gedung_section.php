<style>

.gedung-section{

    padding:110px 0;

    background:#fff;

}

.gedung-header{

    display:flex;

    justify-content:space-between;

    align-items:end;

    margin-bottom:55px;

    flex-wrap:wrap;

    gap:20px;

}

.gedung-subtitle{

    display:inline-block;

    padding:10px 22px;

    border-radius:50px;

    background:rgba(236,72,153,.10);

    color:#EC4899;

    font-weight:600;

    margin-bottom:18px;

}

.gedung-title{

    font-size:46px;

    font-weight:800;

    color:#222;

    margin-bottom:12px;

}

.gedung-description{

    max-width:620px;

    color:#6b7280;

    font-size:18px;

    line-height:1.8;

}

.gedung-link{

    text-decoration:none;

    font-weight:700;

    color:#EC4899;

    transition:.3s;

}

.gedung-link:hover{

    color:#FF7A48;

}

.gedung-grid{

    display:grid;

    grid-template-columns:repeat(4,1fr);

    gap:28px;

}

.gedung-card{

    background:#fff;

    border-radius:26px;

    overflow:hidden;

    box-shadow:0 12px 35px rgba(0,0,0,.08);

    transition:.35s;

    text-decoration:none;

    color:inherit;

}

.gedung-card:hover{

    transform:translateY(-10px);

    box-shadow:0 20px 45px rgba(236,72,153,.20);

}

.gedung-image{

    position:relative;

    overflow:hidden;

    height:240px;

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

        rgba(0,0,0,.55)

    );

}

.gedung-body{

    padding:26px;

}

.gedung-name{

    font-size:24px;

    font-weight:700;

    margin-bottom:22px;

    color:#222;

}

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
        #FF7A48
    );
    flex-shrink:0;

}

.gedung-button{

    display:flex;

    justify-content:center;

    align-items:center;

    height:52px;

    border-radius:14px;

    background:linear-gradient(

        135deg,

        #EC4899,

        #FF7A48

    );

    color:#fff;

    font-weight:700;

    transition:.3s;

}

.gedung-card:hover .gedung-button{

    letter-spacing:.5px;

}
/*==================================
RESPONSIVE
==================================*/

@media(max-width:768px){

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

    .gedung-title{

        font-size:32px;

    }

    .gedung-description{

        font-size:15px;

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

        font-size:34px;

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

        gap:14px;

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

            <a href="gedung.php" class="gedung-link">

                Lihat Semua

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="gedung-grid">

            <?php if(!empty($lokasiList)): ?>

                <?php foreach($lokasiList as $lokasi): ?>

                    <a
                        href="detail_gedung.php?id=<?= $lokasi['id_lokasi']; ?>"
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