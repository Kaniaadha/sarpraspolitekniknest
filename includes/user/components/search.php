<style>
/* ==========================================================
   SEARCH SECTION
========================================================== */

.search-section{

    position:relative;

    margin-top:-70px;

    z-index:50;

}

.search-card{

    background:#fff;

    border-radius:30px;

    padding:50px;

    box-shadow:0 20px 50px rgba(0,0,0,.08);

    overflow:hidden;

}

.search-card::before{

    content:'';

    position:absolute;

    top:0;

    right:0;

    width:220px;

    height:220px;

    background:linear-gradient(
        135deg,
        rgba(244,91,141,.08),
        rgba(255,138,61,.08)
    );

    border-radius:50%;

    transform:translate(40%,-40%);

}

.search-content{

    position:relative;

    z-index:2;

}

.search-badge{

    display:inline-block;

    background:#FFE7F0;

    color:#F45B8D;

    padding:8px 18px;

    border-radius:50px;

    font-size:14px;

    font-weight:600;

    margin-bottom:18px;

}

.search-title{

    color:#2D2D2D;

    font-size:38px;

    font-weight:700;

    margin-bottom:12px;

}

.search-description{

    color:#777;

    font-size:17px;

    margin-bottom:35px;

    max-width:650px;

}

.search-box{

    display:flex;

    align-items:center;

    border:1px solid #eee;

    border-radius:60px;

    overflow:hidden;

    background:#fff;

    box-shadow:0 10px 25px rgba(0,0,0,.04);

}

.search-box input{

    flex:1;

    height:70px;

    border:none;

    padding:0 28px;

    font-size:16px;

    color:#444;

}

.search-box input:focus{

    outline:none;

}

.search-box input::placeholder{

    color:#999;

}

.search-box button{

    width:85px;

    height:70px;

    border:none;

    cursor:pointer;

    background:linear-gradient(
        135deg,
        #F45B8D,
        #FF8A3D
    );

    color:#fff;

    font-size:20px;

    transition:.3s;

}

.search-box button:hover{

    opacity:.9;

}


/* ==========================================================
   RESPONSIVE
========================================================== */

@media(max-width:768px){

    .search-section{

        margin-top:-40px;

    }

    .search-card{

        padding:30px;

    }

    .search-title{

        font-size:28px;

    }

    .search-description{

        font-size:15px;

    }

    .search-box{

        flex-direction:column;

        border-radius:20px;

    }

    .search-box input{

        width:100%;

        height:60px;

    }

    .search-box button{

        width:100%;

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

                    Temukan informasi gedung, ruangan, public space maupun inventaris Politeknik Nest secara cepat dan mudah.

                </p>

                <form action="pencarian.php" method="GET">

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