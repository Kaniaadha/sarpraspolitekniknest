<style>

/*==================================================
HERO
==================================================*/

.hero{

    position:relative;

    width:100%;

    height:700px;

    overflow:hidden;

    margin-top:10px;

}

/*==============================
CAROUSEL
==============================*/

.hero .carousel,

.hero .carousel-inner,

.hero .carousel-item{

    height:100%;

}

.hero-image{

    width:100%;

    height:700px;

    object-fit:cover;

    object-position:center;

    transition:transform 8s ease;

}

.carousel-item.active .hero-image{

    transform:scale(1.06);

}

/*==============================
OVERLAY
==============================*/

.hero-overlay{

    position:absolute;

    inset:0;

    z-index:2;

    background:
    linear-gradient(
        135deg,
        rgba(236,72,153,.68),
        rgba(255,122,72,.55)
    ),
    linear-gradient(
        rgba(0,0,0,.28),
        rgba(0,0,0,.18)
    );

}

/*==============================
CONTENT
==============================*/

.hero-content{

    position:absolute;

    z-index:5;

    top:50%;

    left:50%;

    transform:translate(-50%,-50%);

    width:100%;

    max-width:1320px;

    padding:0 40px;

}

.hero-wrapper{

    max-width:640px;

}

.hero-badge{

    display:inline-flex;

    align-items:center;

    gap:10px;

    padding:12px 24px;

    border-radius:50px;

    background:rgba(255,255,255,.18);

    backdrop-filter:blur(12px);

    color:#fff;

    font-size:15px;

    font-weight:600;

    margin-bottom:24px;

}

.hero-badge i{

    color:#FFD166;

}

/*==============================
TITLE
==============================*/

.hero-title{

    color:#fff;

    font-size:62px;

    line-height:1.08;

    font-weight:800;

    margin-bottom:28px;

    letter-spacing:-1px;

}

/*==============================
DESCRIPTION
==============================*/

.hero-description{

    color:rgba(255,255,255,.95);

    font-size:22px;

    line-height:1.8;

    margin-bottom:42px;

    max-width:620px;

}

/*==============================
BUTTONS
==============================*/

.hero-button-group{

    display:flex;

    align-items:center;

    gap:18px;

    flex-wrap:wrap;

}

.hero-btn-primary{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:10px;

    padding:16px 34px;

    border-radius:60px;

    background:#ffffff;

    color:#EC4899;

    text-decoration:none;

    font-size:17px;

    font-weight:700;

    transition:.35s;

    box-shadow:0 18px 35px rgba(0,0,0,.18);

}

.hero-btn-primary:hover{

    transform:translateY(-4px);

    background:linear-gradient(
        135deg,
        #EC4899,
        #FF7A48
    );

    color:#fff;

}

.hero-btn-outline{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:10px;

    padding:16px 34px;

    border-radius:60px;

    border:2px solid rgba(255,255,255,.8);

    color:#fff;

    text-decoration:none;

    font-size:17px;

    font-weight:700;

    transition:.35s;

    backdrop-filter:blur(8px);

}

.hero-btn-outline:hover{

    background:#fff;

    color:#EC4899;

    transform:translateY(-4px);

}

/*==============================
INDICATOR
==============================*/

.hero .carousel-indicators{

    bottom:34px;

    z-index:20;

}

.hero .carousel-indicators button{

    width:12px;

    height:12px;

    border-radius:50%;

    border:none;

    margin:0 6px;

    background:rgba(255,255,255,.55);

    opacity:1;

    transition:.35s;

}

.hero .carousel-indicators .active{

    width:42px;

    border-radius:20px;

    background:#ffffff;

}

/*==============================
ANIMATION
==============================*/

.hero-badge,

.hero-title,

.hero-description,

.hero-button-group{

    opacity:0;

    transform:translateY(35px);

    animation:heroFade .8s forwards;

}

.hero-title{

    animation-delay:.2s;

}

.hero-description{

    animation-delay:.45s;

}

.hero-button-group{

    animation-delay:.7s;

}

@keyframes heroFade{

    to{

        opacity:1;

        transform:translateY(0);

    }

}

/*==============================
RESPONSIVE
==============================*/

@media(max-width:1200px){

    .hero{

        height:620px;

    }

    .hero-image{

        height:620px;

    }

    .hero-title{

        font-size:52px;

    }

}

@media(max-width:992px){

    .hero{

        height:560px;

    }

    .hero-image{

        height:560px;

    }

    .hero-content{

        padding:0 28px;

    }

    .hero-title{

        font-size:44px;

    }

    .hero-description{

        font-size:18px;

    }

}

@media(max-width:768px){

    .hero{

        height:520px;

    }

    .hero-image{

        height:520px;

    }

    .hero-wrapper{

        max-width:100%;

    }

    .hero-title{

        font-size:34px;

    }

    .hero-description{

        font-size:16px;

        line-height:1.7;

    }

    .hero-button-group{

        flex-direction:column;

        align-items:flex-start;

        width:100%;

    }

    .hero-btn-primary,

    .hero-btn-outline{

        width:100%;

        justify-content:center;

    }

}

</style>

<section class="hero">

    <!-- =========================
         CAROUSEL
    ========================== -->

    <div id="heroCarousel"
        class="carousel slide carousel-fade"
        data-bs-ride="carousel"
        data-bs-interval="5000">

        <?php if (!empty($bannerPhotos)) : ?>

            <div class="carousel-indicators">

                <?php foreach ($bannerPhotos as $index => $foto) : ?>

                    <button
                        type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="<?= $index ?>"
                        class="<?= $index == 0 ? 'active' : '' ?>">
                    </button>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <div class="carousel-inner">

            <?php if (!empty($bannerPhotos)) : ?>

                <?php foreach ($bannerPhotos as $index => $foto) : ?>

                    <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">

                        <img
                            src="<?= $baseUrl ?>assets/uploads/banner/<?= htmlspecialchars($foto['nama_file']) ?>"
                            class="hero-image"
                            alt="Banner Politeknik Nest">

                    </div>

                <?php endforeach; ?>

            <?php else : ?>

                <div class="carousel-item active">

                    <img
                        src="<?= $baseUrl ?>assets/images/default-banner.jpg"
                        class="hero-image"
                        alt="Default Banner">

                </div>

            <?php endif; ?>

        </div>

    </div>

    <!-- =========================
         OVERLAY
    ========================== -->

    <div class="hero-overlay"></div>

    <!-- =========================
         CONTENT
    ========================== -->

    <div class="hero-content">

        <div class="hero-wrapper">

            <span class="hero-badge">
                Sistem Informasi Sarana & Prasarana
            </span>

            <h1 class="hero-title">

                <?= htmlspecialchars($heroTitle) ?>

            </h1>

            <p class="hero-description">

                <?= htmlspecialchars($heroDescription) ?>

            </p>

            <div class="hero-button-group">

                <a
                    href="#gedung"
                    class="hero-btn-primary">

                    <i class="bi bi-building"></i>

                    Jelajahi Fasilitas

                </a>

                <a
                    href="#inventaris"
                    class="hero-btn-outline">

                    <i class="bi bi-box-seam"></i>

                    Lihat Inventaris

                </a>

            </div>

        </div>

    </div>

</section>