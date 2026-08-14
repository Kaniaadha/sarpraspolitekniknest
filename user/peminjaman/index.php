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
        i.foto,

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
    padding:0 0 70px;
}
.cek-peminjaman-section + .peminjaman-section{
    margin-top:-30px;
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
.barang-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
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
        padding:45px 0 75px;
    }
    .hero-title{
        font-size:36px;
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

/*==================================================
CEK PEMINJAMAN
==================================================*/
.cek-peminjaman-section{
    position:relative;
    margin-top:-130px;
    z-index:4;
}
.cek-peminjaman-card{
    margin-top:0;
    margin-bottom:0;
    background:#fff;
    border-radius:18px;
    padding:25px 30px;
    box-shadow:0 8px 25px rgba(0,0,0,.06);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:25px;
}
.cek-peminjaman-info{
    display:flex;
    align-items:center;
    gap:18px;
}
.cek-peminjaman-icon{
    width:58px;
    height:58px;
    border-radius:15px;
    background:#FFF1F6;
    color:#EC4899;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    flex-shrink:0;
}
.cek-peminjaman-info h5{
    margin:0 0 5px;
    font-size:18px;
    font-weight:700;
    color:#374151;
}
.cek-peminjaman-info p{
    margin:0;
    color:#6B7280;
    font-size:14px;
}
.btn-cek-peminjaman{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:12px 22px;
    border:none;
    border-radius:50px;
    background:linear-gradient(135deg,#EC4899,#FF7A45);
    color:#fff;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
    transition:.3s;
    white-space:nowrap;
}
.btn-cek-peminjaman:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(236,72,153,.2);
}
@media(max-width:768px){

    .statistik-section{
        margin-top:-35px;
    }
    .cek-peminjaman-section{
        margin-top:-100px;
    }

    .cek-peminjaman-card{
        flex-direction:column;
        align-items:flex-start;
        padding:22px;
        gap:18px;
    }

    .cek-peminjaman-info{
        width:100%;
        align-items:flex-start;
    }

    .cek-peminjaman-info h5{
        font-size:17px;
        line-height:1.4;
    }

    .cek-peminjaman-info p{
        font-size:13px;
        line-height:1.6;
    }

    .btn-cek-peminjaman{
        width:100%;
    }
}

/*==================================================
MODAL CEK PEMINJAMAN
==================================================*/
.cek-modal-content{
    border:none;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 20px 60px rgba(0,0,0,.15);
}
.cek-modal-header{
    padding:28px 32px;
    background:
        linear-gradient(
            135deg,
            #FFF1F6,
            #FFF7F2
        );

    display:flex;
    align-items:center;
    justify-content:space-between;
}
.cek-modal-label{
    display:block;
    color:#EC4899;
    font-size:12px;
    font-weight:800;
    letter-spacing:1.5px;
    margin-bottom:6px;
}
.cek-modal-header h4{
    margin:0;
    color:#374151;
    font-size:24px;
    font-weight:800;
}
.cek-modal-description{
    color:#6B7280;
    font-size:14px;
    line-height:1.7;
    margin-bottom:22px;
}
.cek-input{
    height:50px;
    border-radius:12px;
    border:1px solid #E5E7EB;
    padding:0 16px;
}
.cek-input:focus{
    border-color:#EC4899;
    box-shadow:0 0 0 3px rgba(236,72,153,.1);
}
.btn-cek-submit{
    width:100%;
    height:48px;
    margin-top:22px;
    border:none;
    border-radius:50px;

    background:
        linear-gradient(
            135deg,
            #EC4899,
            #FF7A45
        );

    color:#fff;
    font-size:14px;
    font-weight:700;

    transition:.3s;
}
.btn-cek-submit:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(236,72,153,.2);
}
.cek-loading{
    text-align:center;
    padding:30px 0;
}
.cek-loading p{
    margin-top:12px;
    color:#6B7280;
}

/* HASIL PEMINJAMAN */
.hasil-peminjaman{
    margin-top:25px;
}
.peminjaman-result-card{
    background:#FFF8FC;
    border-radius:18px;
    padding:22px;
    margin-bottom:15px;
    border:1px solid #FCE7F3;
}
.peminjaman-result-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:18px;
}
.kode-peminjaman{
    color:#EC4899;
    font-weight:800;
    font-size:14px;
}
.status-peminjaman{
    padding:6px 12px;
    border-radius:50px;
    font-size:12px;
    font-weight:700;
    background:#FFF1F6;
    color:#EC4899;
}
.result-barang{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px;
    background:#fff;
    border-radius:14px;
    margin-bottom:15px;
}
.result-barang-icon{
    width:48px;
    height:48px;
    border-radius:12px;
    background:#FFF1F6;
    color:#EC4899;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}
.result-barang h5{
    margin:0 0 3px;
    font-size:16px;
    font-weight:700;
    color:#374151;
}
.result-barang small{
    color:#6B7280;
}
.result-detail{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
}
.result-detail-item{
    background:#fff;
    border-radius:12px;
    padding:12px 14px;
}
.result-detail-item small{
    display:block;
    color:#9CA3AF;
    font-size:11px;
    margin-bottom:3px;
}
.result-detail-item strong{
    color:#374151;
    font-size:13px;
}
.status-terlambat{
    display:block;
    margin-top:12px;
    padding:10px 14px;
    border-radius:12px;
    background:#FEF2F2;
    color:#DC2626;
    font-size:13px;
    font-weight:700;
}
.status-aman{
    display:block;
    margin-top:12px;
    padding:10px 14px;
    border-radius:12px;
    background:#ECFDF5;
    color:#059669;
    font-size:13px;
    font-weight:700;
}
@media(max-width:576px){
    .cek-modal-header{
        padding:22px;
    }
    .modal-body{
        padding:20px;
    }
    .result-detail{
        grid-template-columns:1fr;
    }
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
                        Macam barang tersedia untuk dipinjam
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- Cek Peminjaman -->
    <section class="container cek-peminjaman-section">
        <div class="cek-peminjaman-card">
            <div class="cek-peminjaman-info">
                <div class="cek-peminjaman-icon">
                    <i class="bi bi-search"></i>
                </div>

                <div>
                    <h5>Sudah Mengajukan Peminjaman?</h5>
                    <p>Cek status peminjaman kamu menggunakan email dan NIM/NIP.</p>
                </div>
            </div>

            <button
                type="button"
                class="btn-cek-peminjaman"
                data-bs-toggle="modal"
                data-bs-target="#modalCekPeminjaman">

                <i class="bi bi-search"></i>
                Cek Peminjaman

            </button>
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

                                <?php if (!empty($barang['foto'])) : ?>

                                    <img
                                        src="../../assets/uploads/inventaris/<?= htmlspecialchars($barang['foto']); ?>"
                                        alt="<?= htmlspecialchars($barang['nama_barang']); ?>">

                                <?php else : ?>

                                    <i class="bi bi-box-seam-fill"></i>

                                <?php endif; ?>

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

     <!-- Modal Cek Peminjaman -->
    <div
        class="modal fade"
        id="modalCekPeminjaman"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content cek-modal-content">

                <!-- Header -->
                <div class="cek-modal-header">

                    <div>

                        <span class="cek-modal-label">
                            CEK PEMINJAMAN
                        </span>

                        <h4>
                            Status Peminjaman
                        </h4>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <!-- Body -->
                <div class="modal-body">

                    <div class="cek-modal-description">

                        Masukkan email dan NIM/NIP yang digunakan
                        saat mengajukan peminjaman.

                    </div>


                    <!-- Form -->
                    <form id="formCekPeminjaman">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control cek-input"
                                    placeholder="Masukkan email"
                                    required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    NIM / NIP
                                </label>

                                <input
                                    type="text"
                                    name="nim_nip"
                                    class="form-control cek-input"
                                    placeholder="Masukkan NIM / NIP"
                                    required>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn-cek-submit">

                            <i class="bi bi-search"></i>

                            Cek Peminjaman

                        </button>

                    </form>


                    <!-- Loading -->
                    <div
                        id="cekLoading"
                        class="cek-loading"
                        style="display:none;">

                        <div
                            class="spinner-border text-danger">
                        </div>

                        <p>
                            Memeriksa data peminjaman...
                        </p>

                    </div>


                    <!-- Hasil -->
                    <div
                        id="hasilPeminjaman"
                        style="display:none;">

                    </div>

                </div>

            </div>

        </div>

    </div>

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

<script>
document
    .getElementById('formCekPeminjaman')
    ?.addEventListener('submit', function(e){
        e.preventDefault();
        const form = this;
        const loading =
            document.getElementById('cekLoading');
        const hasil =
            document.getElementById('hasilPeminjaman');
        const formData =
            new FormData(form);
        loading.style.display = 'block';
        hasil.style.display = 'none';
        hasil.innerHTML = '';
        fetch('cek_peminjaman.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            if (!data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Ditemukan',
                    text: data.message,
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'user-swal-popup',
                        title: 'user-swal-title',
                        htmlContainer: 'user-swal-text',
                        confirmButton: 'user-swal-button'
                    },
                    buttonsStyling: false
                });
                return;
            }
            let html = `
                <div class="hasil-peminjaman">
                    <h5 class="mb-3">
                        Hasil Peminjaman
                    </h5>
            `;
            data.data.forEach(function(item){
                let statusClass =
                    item.terlambat
                        ? 'status-terlambat'
                        : 'status-aman';
                let keterangan =
                    item.terlambat
                        ? item.keterangan_keterlambatan
                        : 'Tidak ada keterlambatan';
                html += `
                    <div class="peminjaman-result-card">
                        <div class="peminjaman-result-header">
                            <span class="kode-peminjaman">
                                ${escapeHtml(item.kode_peminjaman)}
                            </span>
                            <span class="status-peminjaman">
                                ${escapeHtml(item.status)}
                            </span>
                        </div>
                        <div class="result-barang">
                            <div class="result-barang-icon">
                                <i class="bi bi-box-seam-fill"></i>
                            </div>
                            <div>
                                <h5>
                                    ${escapeHtml(item.nama_barang)}
                                </h5>
                                <small>
                                    ${escapeHtml(item.kode_inventaris)}
                                </small>
                            </div>
                        </div>
                        <div class="result-detail">
                            <div class="result-detail-item">
                                <small>
                                    Jumlah
                                </small>
                                <strong>
                                    ${item.jumlah} unit
                                </strong>
                            </div>
                            <div class="result-detail-item">
                                <small>
                                    Tanggal Pinjam
                                </small>
                                <strong>
                                    ${formatTanggal(item.tanggal_pinjam)}
                                </strong>
                            </div>
                            <div class="result-detail-item">
                                <small>
                                    Batas Pengembalian
                                </small>
                                <strong>
                                    ${formatTanggal(item.tanggal_kembali)}
                                </strong>
                            </div>
                            <div class="result-detail-item">
                                <small>
                                    Tanggal Dikembalikan
                                </small>
                                <strong>
                                    ${
                                        item.tanggal_pengembalian
                                            ? formatTanggal(item.tanggal_pengembalian)
                                            : 'Belum dikembalikan'
                                    }
                                </strong>
                            </div>
                        </div>
                        <div class="${statusClass}">
                            <i class="bi bi-info-circle-fill"></i>
                            ${escapeHtml(keterangan)}
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
            hasil.innerHTML = html;
            hasil.style.display = 'block';
        })
        .catch(error => {
            loading.style.display = 'none';
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Tidak dapat mengambil data peminjaman.',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'user-swal-popup',
                    title: 'user-swal-title',
                    htmlContainer: 'user-swal-text',
                    confirmButton: 'user-swal-button'
                },
                buttonsStyling: false
            });
        });
    });
function formatTanggal(tanggal){
    if (!tanggal) {
        return '-';
    }
    const parts =
        tanggal.split('-');
    if (parts.length !== 3) {
        return tanggal;
    }
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}
function escapeHtml(text){
    const div =
        document.createElement('div');
    div.textContent =
        text ?? '';
    return div.innerHTML;
}
</script>

</body>
</html>