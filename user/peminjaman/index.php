<?php
session_start();

require_once '../../config/database.php';

$baseUrl = "../../";
$currentPage = 'peminjaman';

// ==============================
// Ambil Data Inventaris
// ==============================

$queryInventaris = mysqli_query($conn, "
    SELECT
        i.id_inventaris,
        i.kode_inventaris,
        i.nama_barang,
        i.jumlah,
        i.kondisi,

        (
            i.jumlah -
            COALESCE(
                (
                    SELECT SUM(dp.jumlah)
                    FROM detail_peminjaman dp
                    INNER JOIN peminjaman p
                        ON dp.id_peminjaman = p.id_peminjaman
                    WHERE dp.id_inventaris = i.id_inventaris
                    AND p.status = 'Dipinjam'
                ),
                0
            )
        ) AS stok_tersedia

    FROM inventaris i

    WHERE i.status = 'Aktif'

    HAVING stok_tersedia > 0

    ORDER BY i.nama_barang ASC
");

$totalBarang = mysqli_num_rows($queryInventaris);
?>

<!DOCTYPE html>
<html lang="id">

<?php include '../../includes/user/header.php'; ?>

<body>

<?php include '../../includes/user/navbar.php'; ?>

<style>

/*==================================================
PAGE
==================================================*/

.peminjaman-page{
    background:#fff8fc;
    min-height:100vh;
}

/*==================================================
HERO
==================================================*/

.page-hero{
    position:relative;
    overflow:hidden;

    background:
        linear-gradient(
            135deg,
            #EC4899 0%,
            #FF7A45 100%
        );

    padding:70px 0 120px;
}

.page-hero::before{
    content:"";
    position:absolute;

    width:420px;
    height:420px;

    border-radius:50%;

    background:rgba(255,255,255,.08);

    top:-180px;
    right:-120px;
}

.page-hero::after{
    content:"";
    position:absolute;

    width:260px;
    height:260px;

    border-radius:50%;

    background:rgba(255,255,255,.06);

    left:-80px;
    bottom:-120px;
}

.page-hero .container{
    position:relative;
    z-index:2;
}

/*==================================================
BREADCRUMB
==================================================*/

.breadcrumb-custom{
    display:flex;
    align-items:center;
    gap:8px;

    margin-bottom:18px;

    font-size:14px;
}

.breadcrumb-custom a{
    color:#fff;
    text-decoration:none;
}

.breadcrumb-custom span{
    color:rgba(255,255,255,.8);
}

/*==================================================
BADGE
==================================================*/

.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;

    padding:10px 18px;

    border-radius:50px;

    background:rgba(255,255,255,.18);

    color:#fff;

    font-size:.9rem;
    font-weight:600;

    margin-bottom:18px;
}

/*==================================================
TITLE
==================================================*/

.hero-title{
    color:#fff;

    font-size:3rem;
    font-weight:800;

    margin-bottom:14px;

    line-height:1.2;
}

.hero-description{
    max-width:650px;

    color:rgba(255,255,255,.92);

    line-height:1.8;

    margin-bottom:30px;
}

/*==================================================
SEARCH
==================================================*/

.search-box{
    position:relative;
    max-width:560px;
}

.search-box i{
    position:absolute;

    left:22px;
    top:50%;

    transform:translateY(-50%);

    color:#EC4899;
}

.search-box input{
    width:100%;
    height:58px;

    border:none;
    outline:none;

    border-radius:50px;

    padding:0 24px 0 55px;

    font-size:14px;

    box-shadow:0 10px 30px rgba(0,0,0,.12);
}

/*==================================================
STATISTIK
==================================================*/

.statistik-section{
    margin-top:-50px;

    position:relative;

    z-index:3;
}

.stat-card{
    background:#fff;

    border-radius:18px;

    padding:25px;

    box-shadow:0 8px 25px rgba(0,0,0,.06);

    display:flex;

    align-items:center;

    gap:16px;
}

.stat-icon{
    width:55px;
    height:55px;

    border-radius:15px;

    display:flex;

    justify-content:center;
    align-items:center;

    background:#FFF1F6;

    color:#EC4899;

    font-size:23px;
}

.stat-card h3{
    margin:0;

    font-size:26px;

    font-weight:800;
}

.stat-card p{
    margin:3px 0 0;

    color:#6B7280;
}

/*==================================================
CONTENT
==================================================*/

.peminjaman-section{
    padding:70px 0;
}

.section-header{
    margin-bottom:30px;
}

.section-badge{
    display:inline-flex;

    align-items:center;

    gap:8px;

    color:#EC4899;

    font-size:14px;

    font-weight:700;

    margin-bottom:8px;
}

.section-header h2{
    font-size:32px;

    font-weight:800;

    margin-bottom:8px;
}

.section-header p{
    color:#6B7280;

    margin-bottom:0;
}

/*==================================================
BARANG GRID
==================================================*/

.barang-grid{
    display:grid;

    grid-template-columns:repeat(3,1fr);

    gap:24px;
}

.barang-card{
    background:#fff;

    border-radius:18px;

    overflow:hidden;

    box-shadow:0 8px 25px rgba(0,0,0,.06);

    transition:.35s;
}

.barang-card:hover{
    transform:translateY(-5px);

    box-shadow:0 15px 35px rgba(0,0,0,.1);
}

.barang-icon{
    height:160px;

    display:flex;

    align-items:center;
    justify-content:center;

    background:
        linear-gradient(
            135deg,
            rgba(236,72,153,.12),
            rgba(255,122,69,.12)
        );

    color:#EC4899;

    font-size:55px;
}

.barang-body{
    padding:22px;
}

.barang-body h5{
    font-size:18px;

    font-weight:700;

    margin-bottom:5px;
}

.barang-code{
    display:block;

    color:#6B7280;

    font-size:13px;

    margin-bottom:18px;
}

.barang-info{
    display:flex;

    flex-direction:column;

    gap:9px;

    margin-bottom:20px;
}

.barang-info div{
    display:flex;

    align-items:center;

    gap:8px;

    color:#4B5563;

    font-size:14px;
}

.barang-info i{
    color:#EC4899;
}

.stok-badge{
    display:inline-flex;

    align-items:center;

    gap:5px;

    background:#ECFDF5;

    color:#059669;

    padding:6px 12px;

    border-radius:50px;

    font-size:12px;

    font-weight:700;

    margin-bottom:15px;
}

/*==================================================
BUTTON
==================================================*/

.btn-pinjam{
    display:flex;

    justify-content:center;
    align-items:center;

    gap:8px;

    width:100%;
    height:42px;

    border-radius:50px;

    background:
        linear-gradient(
            135deg,
            #EC4899,
            #FF7A45
        );

    color:#fff;

    text-decoration:none;

    font-size:14px;

    font-weight:600;

    transition:.35s;
}

.btn-pinjam:hover{
    color:#fff;

    transform:translateY(-2px);
}

.btn-pinjam i{
    transition:.3s;
}

.btn-pinjam:hover i{
    transform:translateX(4px);
}

/*==================================================
EMPTY
==================================================*/

.empty-state{
    background:#fff;

    border-radius:18px;

    padding:55px 20px;

    text-align:center;

    box-shadow:0 8px 25px rgba(0,0,0,.06);
}

.empty-state i{
    font-size:50px;

    color:#EC4899;

    margin-bottom:15px;
}

.empty-state h5{
    font-weight:700;
}

.empty-state p{
    color:#6B7280;

    margin-bottom:0;
}

/*==================================================
RESPONSIVE
==================================================*/

@media(max-width:992px){

    .barang-grid{
        grid-template-columns:repeat(2,1fr);
    }

}

@media(max-width:768px){

    .page-hero{
        padding:55px 0 100px;
    }

    .hero-title{
        font-size:40px;
    }

    .barang-grid{
        grid-template-columns:repeat(2,1fr);

        gap:14px;
    }

    .barang-icon{
        height:120px;
    }

    .barang-body{
        padding:14px;
    }

    .barang-body h5{
        font-size:15px;
    }

    .barang-code{
        font-size:11px;
    }

    .barang-info{
        gap:6px;
    }

    .barang-info div{
        font-size:12px;
    }

    .btn-pinjam{
        height:38px;

        font-size:12px;
    }

    .section-header h2{
        font-size:28px;
    }

}

@media(max-width:576px){

    .barang-grid{
        grid-template-columns:1fr;
    }

}

/*==================================================
SWEETALERT USER
==================================================*/

.user-swal-popup {
    border-radius: 24px !important;
    padding: 2.5rem 2rem 2rem !important;
}

.user-swal-title {
    color: #4B5563 !important;
    font-weight: 700 !important;
}

.user-swal-text {
    color: #6B7280 !important;
    font-size: 16px !important;
}

.user-swal-button {
    border: none !important;
    border-radius: 50px !important;
    padding: 12px 30px !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    color: #fff !important;
    background: linear-gradient(135deg, #EC4899, #FF7A45) !important;
    box-shadow: 0 5px 15px rgba(236, 72, 153, 0.25) !important;
}

.user-swal-button:hover {
    opacity: 0.9;
}

</style>

<main class="peminjaman-page">

    <!-- Hero -->
    <section class="page-hero">

        <div class="container">

            <div class="breadcrumb-custom">

                <a href="<?= $baseUrl ?>index.php">
                    Beranda
                </a>

                <span>/</span>

                <span>Peminjaman</span>

            </div>

            <span class="hero-badge">

                <i class="bi bi-arrow-left-right"></i>

                Layanan Peminjaman

            </span>

            <h1 class="hero-title">
                Peminjaman Sarana & Prasarana
            </h1>

            <p class="hero-description">
                Ajukan peminjaman sarana dan prasarana Politeknik Nest
                dengan mudah. Pilih barang yang tersedia dan ajukan
                peminjaman sesuai kebutuhan.
            </p>

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="searchBarang"
                    placeholder="Cari nama atau kode barang...">

            </div>

        </div>

    </section>

    <!-- Statistik -->
    <section class="statistik-section">

        <div class="container">

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="bi bi-box-seam-fill"></i>

                </div>

                <div>

                    <h3>
                        <?= $totalBarang; ?>
                    </h3>

                    <p>
                        Barang tersedia untuk dipinjam
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- Daftar Barang -->
    <section class="peminjaman-section">

        <div class="container">

            <div class="section-header">

                <span class="section-badge">

                    <i class="bi bi-box-seam-fill"></i>

                    Inventaris Tersedia

                </span>

                <h2>
                    Pilih Barang
                </h2>

                <p>
                    Pilih barang yang ingin kamu pinjam dari daftar
                    inventaris yang tersedia.
                </p>

            </div>

            <?php if ($totalBarang > 0) : ?>

                <div
                    class="barang-grid"
                    id="barangGrid">

                    <?php while ($barang = mysqli_fetch_assoc($queryInventaris)) : ?>

                        <div
                            class="barang-card"
                            data-nama="<?= strtolower(htmlspecialchars($barang['nama_barang'])); ?>"
                            data-kode="<?= strtolower(htmlspecialchars($barang['kode_inventaris'])); ?>">

                            <div class="barang-icon">

                                <i class="bi bi-box-seam-fill"></i>

                            </div>

                            <div class="barang-body">

                                <h5>
                                    <?= htmlspecialchars($barang['nama_barang']); ?>
                                </h5>

                                <span class="barang-code">
                                    <?= htmlspecialchars($barang['kode_inventaris']); ?>
                                </span>

                                <div class="barang-info">

                                    <div>

                                        <i class="bi bi-box-seam-fill"></i>

                                        Stok tersedia:
                                        <?= (int) $barang['stok_tersedia']; ?> unit

                                    </div>

                                    <div>

                                        <i class="bi bi-check-circle-fill"></i>

                                        Kondisi:
                                        <?= htmlspecialchars($barang['kondisi']); ?>

                                    </div>

                                </div>

                                <div class="stok-badge">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Tersedia

                                </div>

                                <a
                                    href="tambah.php?id_inventaris=<?= $barang['id_inventaris']; ?>"
                                    class="btn-pinjam">

                                    Ajukan Peminjaman

                                    <i class="bi bi-arrow-right"></i>

                                </a>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

                <div
                    id="emptySearch"
                    class="empty-state mt-4"
                    style="display:none;">

                    <i class="bi bi-search"></i>

                    <h5>
                        Barang tidak ditemukan
                    </h5>

                    <p>
                        Coba gunakan nama atau kode barang yang berbeda.
                    </p>

                </div>

            <?php else : ?>

                <div class="empty-state">

                    <i class="bi bi-box-seam"></i>

                    <h5>
                        Belum Ada Barang Tersedia
                    </h5>

                    <p>
                        Saat ini belum ada inventaris yang dapat dipinjam.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </section>

</main>

<?php include '../../includes/user/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['user_success'])) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: <?= json_encode($_SESSION['user_success']); ?>,
    confirmButtonText: 'OK',
    customClass: {
        popup: 'user-swal-popup',
        title: 'user-swal-title',
        htmlContainer: 'user-swal-text',
        confirmButton: 'user-swal-button'
    },
    buttonsStyling: false
});
</script>
<?php
unset($_SESSION['user_success']);
endif;
?>

<?php if (isset($_SESSION['user_error'])) : ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: <?= json_encode($_SESSION['user_error']); ?>,
    confirmButtonText: 'OK',
    customClass: {
        popup: 'user-swal-popup',
        title: 'user-swal-title',
        htmlContainer: 'user-swal-text',
        confirmButton: 'user-swal-button'
    },
    buttonsStyling: false
});
</script>
<?php
unset($_SESSION['user_error']);
endif;
?>

<script>

document
    .getElementById('searchBarang')
    ?.addEventListener('input', function () {

        const keyword = this.value.toLowerCase().trim();

        const cards = document.querySelectorAll('.barang-card');

        const emptySearch =
            document.getElementById('emptySearch');

        let ditemukan = 0;

        cards.forEach(function (card) {

            const nama = card.dataset.nama;
            const kode = card.dataset.kode;

            if (
                nama.includes(keyword) ||
                kode.includes(keyword)
            ) {

                card.style.display = '';

                ditemukan++;

            } else {

                card.style.display = 'none';

            }

        });

        if (emptySearch) {

            emptySearch.style.display =
                ditemukan === 0
                    ? 'block'
                    : 'none';

        }

    });

</script>

</body>
</html>