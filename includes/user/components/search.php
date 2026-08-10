<style>
/*==================================================
SEARCH SECTION
==================================================*/

.search-section{

    position:relative;

    margin-top:-70px;

    z-index:30;

}

.search-card{

    position:relative;

    overflow:hidden;

    background:#fff;

    border-radius:32px;

    padding:55px;

    box-shadow:0 20px 45px rgba(0,0,0,.08);

}

.search-card::before{

    content:'';

    position:absolute;

    width:260px;

    height:260px;

    border-radius:50%;

    background:linear-gradient(
        135deg,
        rgba(244,91,141,.10),
        rgba(255,138,61,.10)
    );

    right:-100px;

    top:-120px;

}

.search-content{

    position:relative;

    z-index:2;

}

.search-badge{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:10px 20px;

    border-radius:50px;

    background:#FFE8F2;

    color:#F45B8D;

    font-weight:600;

    font-size:14px;

    margin-bottom:20px;

}

.search-title{

    font-size:40px;

    font-weight:700;

    color:#2D2D2D;

    margin-bottom:15px;

}

.search-description{

    max-width:650px;

    color:#777;

    font-size:17px;

    line-height:1.8;

    margin-bottom:35px;

}

/*=========================
SEARCH BOX
=========================*/

.search-box{

    display:flex;

    align-items:center;

    overflow:hidden;

    border-radius:60px;

    background:#fff;

    border:1px solid #eee;

    box-shadow:0 10px 25px rgba(0,0,0,.05);

    transition:.3s;

}

.search-box:focus-within{

    border-color:#F45B8D;

    box-shadow:
    0 0 0 5px rgba(244,91,141,.10);

}

.search-box input{

    flex:1;

    height:72px;

    border:none;

    outline:none;

    padding:0 28px;

    font-size:16px;

    color:#444;

    background:transparent;

}

.search-box input::placeholder{

    color:#999;

}

.search-box button{

    width:85px;

    height:72px;

    border:none;

    cursor:pointer;

    color:#fff;

    font-size:22px;

    background:linear-gradient(
        135deg,
        #F45B8D,
        #FF8A3D
    );

    transition:.35s;

}

.search-box button:hover{

    transform:scale(1.05);

}

/*=========================
RESPONSIVE
=========================*/

@media(max-width:992px){

    .search-card{

        padding:40px;

    }

}

@media(max-width:768px){

    .search-section{

        margin-top:-30px;

    }

    .search-card{

        padding:28px;

        border-radius:25px;

    }

    .search-title{

        font-size:28px;

    }

    .search-description{

        font-size:15px;

    }

    .search-box{

        border-radius:20px;

    }

    .search-box input{

        height:60px;

    }

    .search-box button{

        width:70px;

        height:60px;

    }

}
</style>

<section class="search-section">

    <div class="container">

        <div class="search-card">

            <div class="search-content">

                <span class="search-badge">

                    <i class="fas fa-search"></i>

                    Temukan Sarana & Prasarana

                </span>

                <h2 class="search-title">

                    Cari Fasilitas Kampus

                </h2>

                <p class="search-description">

                    Temukan gedung, ruangan, public space, maupun inventaris Politeknik Nest hanya dengan sekali pencarian.

                </p>

                <form action="user/pencarian.php" method="GET">

                    <div class="search-box">

                        <input
                            type="text"
                            name="keyword"
                            placeholder="Cari gedung, ruangan, public space atau inventaris...">

                        <button type="submit">

                            <i class="fas fa-search"></i>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>