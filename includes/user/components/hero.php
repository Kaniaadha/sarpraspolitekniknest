<style>
/*==================================================
HERO
==================================================*/

.hero{
    position: relative;
    width: 100%;
    min-height: 700px;
    height: 700px;
    overflow: hidden;
    margin-top: 0;
}

/*==================================================
CAROUSEL
==================================================*/

.hero .carousel,
.hero .carousel-inner,
.hero .carousel-item{
    width:100%;
    height:100%;
}

.hero-image{
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
    transition:transform 8s ease;
}

.carousel-item.active .hero-image{
    transform:scale(1.05);
}

/*==================================================
OVERLAY
==================================================*/

.hero-overlay{
    position:absolute;
    inset:0;
    z-index:2;

    background:
    linear-gradient(
        135deg,
        rgba(236,72,153,.58),
        rgba(255,122,72,.48)
    ),
    linear-gradient(
        rgba(0,0,0,.15),
        rgba(0,0,0,.10)
    );
}

/*==================================================
CONTENT
==================================================*/

.hero-content{
    position:absolute;
    inset:0;
    z-index:5;

    display:flex;
    align-items:center;

    width:100%;
    max-width:1320px;

    margin:auto;
    padding:0 40px;
}

.hero-wrapper{
    max-width:620px;
}

/*==================================================
BADGE
==================================================*/

.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;

    padding:9px 18px;

    border-radius:50px;

    background:rgba(255,255,255,.18);

    backdrop-filter:blur(10px);

    color:#fff;

    font-size:.82rem;
    font-weight:600;

    margin-bottom:20px;

    box-shadow:
    0 10px 30px rgba(0,0,0,.08);
}

.hero-badge i{
    color:#FFD166;
    font-size:.95rem;
}

/*==================================================
TITLE
==================================================*/

.hero-title{
    color:#fff;
    font-size:3.5rem;
    font-weight:800;
    line-height:1.15;
    letter-spacing:-1.5px;
    margin-bottom:20px;
    text-shadow:0 5px 18px rgba(0,0,0,.15);
}

/*==================================================
DESCRIPTION
==================================================*/

.hero-description{
    color:rgba(255,255,255,.96);
    font-size:1.1rem;
    line-height:1.9;
    max-width:600px;
    margin-bottom:35px;
}

/*==================================================
BUTTON GROUP
==================================================*/

.hero-button-group{
    display:flex;
    gap:18px;
    align-items:center;
    flex-wrap:wrap;
}

/*==================================================
BUTTON
==================================================*/

.hero-btn{

    width:245px;
    height:60px;

    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;

    border-radius:50px;

    text-decoration:none;

    font-size:16px;
    font-weight:700;

    color:#fff;

    border:2px solid rgba(255,255,255,.9);

    background:rgba(255,255,255,.08);

    backdrop-filter:blur(10px);

    transition:.35s ease;

}

.hero-btn i{

    font-size:18px;

}

/*==================================================
HOVER
==================================================*/

.hero-btn:hover{

    background:linear-gradient(
        135deg,
        #EC4899,
        #FF7A48
    );

    color:#fff;

    border-color:transparent;

    transform:translateY(-4px);

    box-shadow:0 18px 35px rgba(236,72,153,.35);

}

/*==================================================
ACTIVE
==================================================*/

.hero-btn:active{

    transform:scale(.97);

}

/*==================================================
CAROUSEL INDICATOR
==================================================*/

.hero .carousel-indicators{

    bottom:28px;

    z-index:20;

}

.hero .carousel-indicators button{

    width:12px;
    height:12px;

    margin:0 6px;

    border:none;

    border-radius:50px;

    background:rgba(255,255,255,.45);

    opacity:1;

    transition:.35s;

}

.hero .carousel-indicators .active{

    width:42px;

    background:#fff;

    border-radius:20px;

}

/*==================================================
ANIMATION
==================================================*/

.hero-badge,
.hero-title,
.hero-description,
.hero-button-group{

    opacity:0;

    transform:translateY(35px);

    animation:heroFade .9s ease forwards;

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

    from{

        opacity:0;

        transform:translateY(35px);

    }

    to{

        opacity:1;

        transform:translateY(0);

    }

}


/*==================================================
RESPONSIVE DESKTOP
==================================================*/

@media(max-width:1200px){

    .hero{

        height:620px;

        min-height:620px;

    }

    .hero-title{

        font-size:3rem;

    }

    .hero-description{

        font-size:1rem;

    }

}


/*==================================================
RESPONSIVE TABLET
==================================================*/

@media(max-width:992px){

    .hero{

        height:560px;

        min-height:560px;

    }

    .hero-content{

        padding:0 30px;

    }

    .hero-wrapper{

        max-width:560px;

    }

    .hero-title{

        font-size:2.5rem;

    }

    .hero-description{

        font-size:1rem;

        margin-bottom:28px;

    }

    .hero-btn-primary,
    .hero-btn-outline{

        min-width:210px;

        height:54px;

    }

}


/*==================================================
RESPONSIVE MOBILE
==================================================*/

@media(max-width:768px){

    .hero{

        height:600px;

        min-height:600px;

    }

    .hero-image{

        object-position: center;

    }

    .hero-content{

        padding:0 22px;

        position:left;

    }

    .hero-wrapper{

        max-width:100%;

        text-align:center;

    }

    .hero-badge{

        font-size:.72rem;

        padding:8px 16px;

        margin-bottom:16px;

    }

    .hero-title{

        font-size:2rem;

        line-height:1.25;

        margin-bottom:16px;

    }

    .hero-description{

        font-size:.95rem;

        line-height:1.75;

        margin:0 auto 24px;

        max-width:100%;

    }

    .hero-button-group{

        width:100%;

        display:flex;

        flex-direction:column;

        gap:14px;

        align-items:center;

    }

    .hero-btn-primary,
    .hero-btn-outline{

        width:100%;

        max-width:320px;

        height:52px;

        font-size:15px;

    }

    .hero .carousel-indicators{

        bottom:18px;

    }

}


/*==================================================
RESPONSIVE SMALL MOBILE
==================================================*/

@media(max-width:576px){

    .hero{

        height:560px;

        min-height:560px;

    }

    .hero-content{

        padding:0 18px;

    }

    .hero-title{

        font-size:1.8rem;

    }

    .hero-description{

        font-size:.9rem;

        line-height:1.7;

    }

    .hero-btn-primary,
    .hero-btn-outline{

        height:50px;

        font-size:.9rem;

    }

    .hero-badge{

        font-size:.68rem;

        padding:7px 14px;

    }

}
</style>

<section class="hero">

    <!-- ==========================================
         CAROUSEL
    =========================================== -->

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
                        class="<?= $index == 0 ? 'active' : '' ?>"
                        aria-label="Slide <?= $index + 1 ?>">
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
                            alt="<?= htmlspecialchars($heroTitle) ?>">

                    </div>

                <?php endforeach; ?>

            <?php else : ?>

                <div class="carousel-item active">

                    <img
                        src="<?= $baseUrl ?>assets/images/default-banner.jpg"
                        class="hero-image"
                        alt="Banner SISARPRAS">

                </div>

            <?php endif; ?>

        </div>

    </div>


    <!-- ==========================================
         OVERLAY
    =========================================== -->

    <div class="hero-overlay"></div>


    <!-- ==========================================
         CONTENT
    =========================================== -->

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

                <a href="#gedung" class="hero-btn hero-btn-primary">
                    <i class="bi bi-building"></i>
                    <span>Jelajahi Fasilitas</span>
                </a>

                <a href="#inventaris" class="hero-btn hero-btn-outline">
                    <i class="bi bi-box-seam"></i>
                    <span>Lihat Inventaris</span>
                </a>

            </div>

        </div>

    </div>

</section>