<?php

session_start();

$menu = "lantai";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


// =====================================================
// DATA LANTAI
// =====================================================

$query = mysqli_query($conn, "
    SELECT
        l.*,
        lok.nama_lokasi

    FROM lantai l

    INNER JOIN lokasi lok
        ON l.id_lokasi = lok.id_lokasi

    ORDER BY
        lok.nama_lokasi ASC,
        l.nomor_lantai ASC
");


require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";

?>

<main class="app-main">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="mb-0 fw-bold">

                    Data Lantai

                </h2>


                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">

                        Dashboard

                    </li>

                    <li class="breadcrumb-item">

                        Master

                    </li>

                    <li class="breadcrumb-item active">

                        Lantai

                    </li>

                </ol>

            </div>

        </div>

    </div>



    <!-- =====================================================
         CONTENT
    ====================================================== -->

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">


            <!-- =================================================
                 CARD HEADER
            ================================================== -->

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">

                        <i class="bi bi-layers-fill me-2"></i>

                        Daftar Lantai

                    </h5>


                    <a
                        href="tambah.php"
                        class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>

                        Tambah Lantai

                    </a>

                </div>

            </div>



            <div class="card-body">


                <!-- =================================================
                     DESKTOP TABLE
                ================================================== -->

                <div class="desktop-table">

                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-hover align-middle datatable">


                            <thead class="table-secondary">

                                <tr>

                                    <th
                                        width="5%"
                                        class="text-center">

                                        No

                                    </th>


                                    <th>

                                        Kode Lantai

                                    </th>


                                    <th>

                                        Nama Lantai

                                    </th>


                                    <th>

                                        Nomor Lantai

                                    </th>


                                    <th>

                                        Gedung

                                    </th>


                                    <th>

                                        Status

                                    </th>


                                    <th
                                        width="15%"
                                        class="text-center">

                                        Aksi

                                    </th>

                                </tr>

                            </thead>



                            <tbody>


                                <?php

                                $no = 1;

                                ?>


                                <?php while (
                                    $row = mysqli_fetch_assoc($query)
                                ) : ?>


                                    <tr>


                                        <!-- NO -->

                                        <td class="text-center">

                                            <?= $no++; ?>

                                        </td>



                                        <!-- KODE -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['kode_lantai']
                                            ); ?>

                                        </td>



                                        <!-- NAMA -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_lantai']
                                            ); ?>

                                        </td>



                                        <!-- NOMOR -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nomor_lantai']
                                            ); ?>

                                        </td>



                                        <!-- GEDUNG -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_lokasi']
                                            ); ?>

                                        </td>



                                        <!-- STATUS -->

                                        <td>


                                            <?php if (
                                                $row['status'] == "Aktif"
                                            ) : ?>


                                                <span
                                                    class="badge rounded-pill bg-success">

                                                    Aktif

                                                </span>


                                            <?php else : ?>


                                                <span
                                                    class="badge rounded-pill bg-danger">

                                                    Tidak Aktif

                                                </span>


                                            <?php endif; ?>


                                        </td>



                                        <!-- AKSI -->

                                        <td class="text-center">


                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_lantai']; ?>"
                                                class="btn btn-warning btn-sm me-1"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <a
                                                href="#"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusLantai(
                                                    <?= $row['id_lantai']; ?>
                                                )">

                                                <i class="bi bi-trash"></i>

                                            </a>


                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            </tbody>

                        </table>

                    </div>

                </div>



                <!-- =================================================
                     MOBILE CARDS
                ================================================== -->

                <div class="mobile-cards">


                    <!-- =================================================
                         SEARCH MOBILE
                    ================================================== -->

                    <div class="mobile-search mb-3">

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-search"></i>

                            </span>


                            <input
                                type="text"
                                id="searchLantaiMobile"
                                class="form-control"
                                placeholder="Cari lantai, kode, atau gedung...">

                        </div>

                    </div>



                    <!-- =================================================
                         MOBILE LIST
                    ================================================== -->

                    <div
                        class="row g-3"
                        id="lantaiMobileList">


                        <?php

                        /*
                         * Kembalikan pointer query
                         * untuk menampilkan data kembali
                         * pada card mobile.
                         */

                        mysqli_data_seek($query, 0);

                        ?>


                        <?php if (
                            mysqli_num_rows($query) > 0
                        ) : ?>


                            <?php while (
                                $row = mysqli_fetch_assoc($query)
                            ) : ?>


                                <div class="col-6">


                                    <div
                                        class="lantai-mobile-card h-100"
                                        data-search="<?= htmlspecialchars(
                                            strtolower(
                                                $row['kode_lantai'] . ' ' .
                                                $row['nama_lantai'] . ' ' .
                                                $row['nomor_lantai'] . ' ' .
                                                $row['nama_lokasi']
                                            )
                                        ); ?>">


                                        <!-- ==========================
                                             TOP
                                        =========================== -->

                                        <div class="lantai-mobile-top">


                                            <div class="lantai-mobile-icon">

                                                <i class="bi bi-layers-fill"></i>

                                            </div>


                                            <?php if (
                                                $row['status'] == "Aktif"
                                            ) : ?>


                                                <span
                                                    class="badge bg-success rounded-pill">

                                                    Aktif

                                                </span>


                                            <?php else : ?>


                                                <span
                                                    class="badge bg-danger rounded-pill">

                                                    Tidak Aktif

                                                </span>


                                            <?php endif; ?>


                                        </div>



                                        <!-- ==========================
                                             NAMA
                                        =========================== -->

                                        <h6 class="lantai-mobile-name">

                                            <?= htmlspecialchars(
                                                $row['nama_lantai']
                                            ); ?>

                                        </h6>



                                        <!-- ==========================
                                             KODE
                                        =========================== -->

                                        <div class="lantai-mobile-info">

                                            <span>

                                                <i class="bi bi-upc-scan"></i>

                                                Kode

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['kode_lantai']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             NOMOR LANTAI
                                        =========================== -->

                                        <div class="lantai-mobile-info">

                                            <span>

                                                <i class="bi bi-sort-numeric-up"></i>

                                                Nomor Lantai

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['nomor_lantai']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             GEDUNG
                                        =========================== -->

                                        <div class="lantai-mobile-info">

                                            <span>

                                                <i class="bi bi-building"></i>

                                                Gedung

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['nama_lokasi']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             ACTION
                                        =========================== -->

                                        <div class="lantai-mobile-actions">


                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_lantai']; ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusLantai(
                                                    <?= $row['id_lantai']; ?>
                                                )">

                                                <i class="bi bi-trash"></i>

                                            </button>


                                        </div>


                                    </div>

                                </div>


                            <?php endwhile; ?>


                        <?php else : ?>


                            <!-- =================================================
                                 EMPTY STATE
                            ================================================== -->

                            <div class="col-12">


                                <div class="lantai-empty">


                                    <div class="lantai-empty-icon">

                                        <i class="bi bi-layers"></i>

                                    </div>


                                    <h5>

                                        Belum Ada Data Lantai

                                    </h5>


                                    <p>

                                        Belum ada data lantai
                                        yang ditambahkan.

                                    </p>


                                    <a
                                        href="tambah.php"
                                        class="btn btn-primary">

                                        <i class="bi bi-plus-circle me-1"></i>

                                        Tambah Lantai

                                    </a>


                                </div>


                            </div>


                        <?php endif; ?>


                    </div>

                </div>


            </div>

        </div>

    </div>


</main>



<!-- =====================================================
     MOBILE CARD STYLE
====================================================== -->

<style>


/* =====================================================
   DEFAULT
===================================================== */

.mobile-cards {

    display: none;

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767.98px) {


    /* =================================================
       HIDE DESKTOP TABLE
    ================================================== */

    .desktop-table {

        display: none;

    }


    /* =================================================
       SHOW MOBILE CARDS
    ================================================== */

    .mobile-cards {

        display: block;

    }


    /* =================================================
       PAGE HEADER
    ================================================== */

    .app-content-header h2 {

        font-size: 20px;

    }


    .app-content-header .breadcrumb {

        display: none;

    }


    /* =================================================
       CARD HEADER
    ================================================== */

    .card-header {

        padding: 14px !important;

    }


    .card-header h5 {

        font-size: 15px;

    }


    .card-header .btn {

        padding: 7px 10px;

        font-size: 12px;

    }


    /* =================================================
       SEARCH
    ================================================== */

    .mobile-search .input-group {

        border-radius: 10px;

        overflow: hidden;

        box-shadow:
            0 2px 8px rgba(0, 0, 0, .05);

    }


    .mobile-search .input-group-text {

        background: #fff;

        border-right: none;

        color: #999;

    }


    .mobile-search .form-control {

        border-left: none;

        font-size: 13px;

        padding: 10px 12px;

    }


    .mobile-search .form-control:focus {

        box-shadow: none;

        border-color: #dee2e6;

    }


    /* =================================================
       MOBILE CARD
    ================================================== */

    .lantai-mobile-card {

        background: #fff;

        border: 1px solid #eeeeee;

        border-radius: 14px;

        padding: 12px;

        box-shadow:
            0 3px 12px rgba(0, 0, 0, .06);

        transition: .25s ease;

        overflow: hidden;

    }


    .lantai-mobile-card:active {

        transform: scale(.98);

    }


    /* =================================================
       TOP
    ================================================== */

    .lantai-mobile-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 5px;

        margin-bottom: 10px;

    }


    .lantai-mobile-icon {

        width: 34px;

        height: 34px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 9px;

        background:
            linear-gradient(
                135deg,
                #ff8a00,
                #ff5e78
            );

        color: #fff;

        font-size: 15px;

    }


    .lantai-mobile-top .badge {

        font-size: 9px;

        padding: 5px 7px;

        white-space: nowrap;

    }


    /* =================================================
       NAME
    ================================================== */

    .lantai-mobile-name {

        font-size: 13px;

        font-weight: 700;

        line-height: 1.35;

        color: #2d3436;

        margin: 0 0 10px;

        word-break: break-word;

    }


    /* =================================================
       INFO
    ================================================== */

    .lantai-mobile-info {

        margin-bottom: 8px;

        line-height: 1.3;

    }


    .lantai-mobile-info span {

        display: block;

        color: #999;

        font-size: 9px;

        margin-bottom: 2px;

    }


    .lantai-mobile-info span i {

        margin-right: 2px;

    }


    .lantai-mobile-info strong {

        display: block;

        color: #444;

        font-size: 10px;

        font-weight: 500;

        word-break: break-word;

    }


    /* =================================================
       ACTION
    ================================================== */

    .lantai-mobile-actions {

        display: flex;

        gap: 5px;

        margin-top: 10px;

        padding-top: 9px;

        border-top: 1px solid #eeeeee;

    }


    .lantai-mobile-actions .btn {

        flex: 1;

        padding: 6px 4px;

        font-size: 11px;

        border-radius: 7px;

    }


    /* =================================================
       SEARCH EMPTY
    ================================================== */

    .lantai-search-empty {

        text-align: center;

        padding: 35px 15px;

        border: 1px dashed #ddd;

        border-radius: 12px;

        background: #fafafa;

    }


    .lantai-search-empty i {

        display: block;

        font-size: 28px;

        color: #ff8a00;

        margin-bottom: 10px;

    }


    .lantai-search-empty h6 {

        font-weight: 600;

        margin-bottom: 5px;

    }


    .lantai-search-empty p {

        margin: 0;

        font-size: 12px;

        color: #999;

    }


    /* =================================================
       EMPTY DATA
    ================================================== */

    .lantai-empty {

        text-align: center;

        padding: 45px 20px;

        border: 2px dashed #e5e5e5;

        border-radius: 14px;

        background: #fafafa;

    }


    .lantai-empty-icon {

        width: 60px;

        height: 60px;

        margin: 0 auto 14px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        background:
            linear-gradient(
                135deg,
                rgba(255,138,0,.12),
                rgba(255,94,120,.12)
            );

        color: #ff8a00;

        font-size: 26px;

    }


    .lantai-empty h5 {

        margin-bottom: 7px;

        font-weight: 700;

    }


    .lantai-empty p {

        color: #888;

        font-size: 13px;

        margin-bottom: 18px;

    }

}


/* =====================================================
   EXTRA SMALL MOBILE
===================================================== */

@media (max-width: 380px) {


    .lantai-mobile-card {

        padding: 10px;

    }


    .lantai-mobile-name {

        font-size: 12px;

    }


    .lantai-mobile-info strong {

        font-size: 9px;

    }


    .lantai-mobile-actions .btn {

        padding: 5px 3px;

        font-size: 10px;

    }


    .mobile-search .form-control {

        font-size: 11px;

    }

}

</style>



<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>


/* =====================================================
   SEARCH LANTAI MOBILE
===================================================== */

const searchLantaiMobile =
    document.getElementById(
        "searchLantaiMobile"
    );


if (searchLantaiMobile) {

    searchLantaiMobile.addEventListener(
        "input",
        function () {


            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            const cards =
                document.querySelectorAll(
                    "#lantaiMobileList > .col-6"
                );


            let jumlahHasil = 0;


            cards.forEach(function (card) {


                const mobileCard =
                    card.querySelector(
                        ".lantai-mobile-card"
                    );


                if (!mobileCard) {

                    return;

                }


                const data =
                    mobileCard.getAttribute(
                        "data-search"
                    ) || "";


                if (
                    data.includes(keyword)
                ) {

                    card.style.display = "";

                    jumlahHasil++;

                } else {

                    card.style.display = "none";

                }

            });


            let noResult =
                document.getElementById(
                    "lantaiSearchEmpty"
                );


            if (
                jumlahHasil === 0 &&
                keyword !== ""
            ) {


                if (!noResult) {


                    const empty =
                        document.createElement(
                            "div"
                        );


                    empty.id =
                        "lantaiSearchEmpty";


                    empty.className =
                        "col-12";


                    empty.innerHTML = `

                        <div class="lantai-search-empty">

                            <i class="bi bi-search"></i>

                            <h6>
                                Lantai tidak ditemukan
                            </h6>

                            <p>
                                Coba gunakan kata kunci lain.
                            </p>

                        </div>

                    `;


                    document
                        .getElementById(
                            "lantaiMobileList"
                        )
                        .appendChild(
                            empty
                        );

                }

            } else {


                if (noResult) {

                    noResult.remove();

                }

            }

        }
    );

}



/* =====================================================
   HAPUS LANTAI
===================================================== */

function hapusLantai(id) {


    Swal.fire({

        title: 'Hapus Data?',

        text: 'Data Lantai yang dihapus tidak dapat dikembalikan.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc3545',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Hapus!',

        cancelButtonText: 'Batal'

    }).then((result) => {


        if (result.isConfirmed) {

            window.location.href =
                "hapus.php?id=" + id;

        }

    });

}

</script>



<?php

require_once "../../../includes/footer.php";

require_once "../../../includes/scripts.php";

?>