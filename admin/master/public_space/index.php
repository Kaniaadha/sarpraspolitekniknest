<?php

session_start();

$menu = "public_space";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


// =====================================================
// DATA PUBLIC SPACE
// =====================================================

$query = mysqli_query($conn, "
    SELECT
        ps.*,
        l.nama_lantai,
        l.nomor_lantai,
        lok.nama_lokasi

    FROM public_space ps

    INNER JOIN lantai l
        ON ps.id_lantai = l.id_lantai

    INNER JOIN lokasi lok
        ON l.id_lokasi = lok.id_lokasi

    ORDER BY
        lok.nama_lokasi ASC,
        l.nomor_lantai ASC,
        ps.nama_public_space ASC
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

                    Data Public Space

                </h2>


                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">

                        Dashboard

                    </li>

                    <li class="breadcrumb-item">

                        Master

                    </li>

                    <li class="breadcrumb-item active">

                        Public Space

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

                        <i class="bi bi-tree-fill me-2"></i>

                        Daftar Public Space

                    </h5>


                    <a
                        href="tambah.php"
                        class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>

                        Tambah Public Space

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

                                        Kode

                                    </th>


                                    <th>

                                        Nama Public Space

                                    </th>


                                    <th>

                                        Gedung

                                    </th>


                                    <th>

                                        Lantai

                                    </th>


                                    <th>

                                        Luas

                                    </th>


                                    <th>

                                        Status

                                    </th>


                                    <th
                                        width="18%"
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
                                                $row['kode_public_space']
                                            ); ?>

                                        </td>



                                        <!-- NAMA -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_public_space']
                                            ); ?>

                                        </td>



                                        <!-- GEDUNG -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_lokasi']
                                            ); ?>

                                        </td>



                                        <!-- LANTAI -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_lantai']
                                            ); ?>

                                        </td>



                                        <!-- LUAS -->

                                        <td>

                                            <?php if (
                                                $row['luas'] !== null &&
                                                $row['luas'] !== ''
                                            ) : ?>

                                                <?= number_format(
                                                    $row['luas'],
                                                    2,
                                                    ',',
                                                    '.'
                                                ); ?>

                                                m²

                                            <?php else : ?>

                                                -

                                            <?php endif; ?>

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


                                            <!-- GALLERY -->

                                            <a
                                                href="../foto/index.php?tipe=public_space&id=<?= $row['id_public_space']; ?>"
                                                class="btn btn-info btn-sm me-1"
                                                title="Gallery Foto">

                                                <i class="bi bi-images"></i>

                                            </a>



                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_public_space']; ?>"
                                                class="btn btn-warning btn-sm me-1"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <a
                                                href="#"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusPublicSpace(
                                                    <?= $row['id_public_space']; ?>
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
                         MOBILE SEARCH
                    ================================================== -->

                    <div class="mobile-search mb-3">

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-search"></i>

                            </span>


                            <input
                                type="text"
                                id="searchPublicSpaceMobile"
                                class="form-control"
                                placeholder="Cari public space, kode, gedung, lantai...">

                        </div>

                    </div>



                    <!-- =================================================
                         MOBILE LIST
                    ================================================== -->

                    <div
                        class="row g-3"
                        id="publicSpaceMobileList">


                        <?php

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
                                        class="public-space-mobile-card h-100"
                                        data-search="<?= htmlspecialchars(
                                            strtolower(
                                                $row['kode_public_space'] . ' ' .
                                                $row['nama_public_space'] . ' ' .
                                                $row['nama_lokasi'] . ' ' .
                                                $row['nama_lantai']
                                            )
                                        ); ?>">


                                        <!-- ==========================
                                             TOP
                                        =========================== -->

                                        <div class="public-space-mobile-top">


                                            <div class="public-space-mobile-icon">

                                                <i class="bi bi-tree-fill"></i>

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

                                        <h6 class="public-space-mobile-name">

                                            <?= htmlspecialchars(
                                                $row['nama_public_space']
                                            ); ?>

                                        </h6>



                                        <!-- ==========================
                                             KODE
                                        =========================== -->

                                        <div class="public-space-mobile-info">

                                            <span>

                                                <i class="bi bi-upc-scan"></i>

                                                Kode

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['kode_public_space']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             GEDUNG
                                        =========================== -->

                                        <div class="public-space-mobile-info">

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
                                             LANTAI
                                        =========================== -->

                                        <div class="public-space-mobile-info">

                                            <span>

                                                <i class="bi bi-layers-fill"></i>

                                                Lantai

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['nama_lantai']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             LUAS
                                        =========================== -->

                                        <div class="public-space-mobile-info">

                                            <span>

                                                <i class="bi bi-rulers"></i>

                                                Luas

                                            </span>


                                            <strong>

                                                <?php if (
                                                    $row['luas'] !== null &&
                                                    $row['luas'] !== ''
                                                ) : ?>

                                                    <?= number_format(
                                                        $row['luas'],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ); ?>

                                                    m²

                                                <?php else : ?>

                                                    -

                                                <?php endif; ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             ACTION
                                        =========================== -->

                                        <div class="public-space-mobile-actions">


                                            <!-- GALLERY -->

                                            <a
                                                href="../foto/index.php?tipe=public_space&id=<?= $row['id_public_space']; ?>"
                                                class="btn btn-info btn-sm"
                                                title="Gallery">

                                                <i class="bi bi-images"></i>

                                            </a>



                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_public_space']; ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusPublicSpace(
                                                    <?= $row['id_public_space']; ?>
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


                                <div class="public-space-empty">


                                    <div class="public-space-empty-icon">

                                        <i class="bi bi-tree"></i>

                                    </div>


                                    <h5>

                                        Belum Ada Data Public Space

                                    </h5>


                                    <p>

                                        Belum ada public space
                                        yang ditambahkan.

                                    </p>


                                    <a
                                        href="tambah.php"
                                        class="btn btn-primary">

                                        <i class="bi bi-plus-circle me-1"></i>

                                        Tambah Public Space

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

    .public-space-mobile-card {

        background: #fff;

        border: 1px solid #eeeeee;

        border-radius: 14px;

        padding: 12px;

        box-shadow:
            0 3px 12px rgba(0, 0, 0, .06);

        transition: .25s ease;

        overflow: hidden;

    }


    .public-space-mobile-card:active {

        transform: scale(.98);

    }


    /* =================================================
       TOP
    ================================================== */

    .public-space-mobile-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 5px;

        margin-bottom: 10px;

    }


    .public-space-mobile-icon {

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


    .public-space-mobile-top .badge {

        font-size: 9px;

        padding: 5px 7px;

        white-space: nowrap;

    }


    /* =================================================
       NAME
    ================================================== */

    .public-space-mobile-name {

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

    .public-space-mobile-info {

        margin-bottom: 8px;

        line-height: 1.3;

    }


    .public-space-mobile-info span {

        display: block;

        color: #999;

        font-size: 9px;

        margin-bottom: 2px;

    }


    .public-space-mobile-info span i {

        margin-right: 2px;

    }


    .public-space-mobile-info strong {

        display: block;

        color: #444;

        font-size: 10px;

        font-weight: 500;

        word-break: break-word;

    }


    /* =================================================
       ACTION
    ================================================== */

    .public-space-mobile-actions {

        display: flex;

        gap: 5px;

        margin-top: 10px;

        padding-top: 9px;

        border-top: 1px solid #eeeeee;

    }


    .public-space-mobile-actions .btn {

        flex: 1;

        padding: 6px 4px;

        font-size: 11px;

        border-radius: 7px;

    }


    /* =================================================
       SEARCH EMPTY
    ================================================== */

    .public-space-search-empty {

        text-align: center;

        padding: 35px 15px;

        border: 1px dashed #ddd;

        border-radius: 12px;

        background: #fafafa;

    }


    .public-space-search-empty i {

        display: block;

        font-size: 28px;

        color: #ff8a00;

        margin-bottom: 10px;

    }


    .public-space-search-empty h6 {

        font-weight: 600;

        margin-bottom: 5px;

    }


    .public-space-search-empty p {

        margin: 0;

        font-size: 12px;

        color: #999;

    }


    /* =================================================
       EMPTY DATA
    ================================================== */

    .public-space-empty {

        text-align: center;

        padding: 45px 20px;

        border: 2px dashed #e5e5e5;

        border-radius: 14px;

        background: #fafafa;

    }


    .public-space-empty-icon {

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


    .public-space-empty h5 {

        margin-bottom: 7px;

        font-weight: 700;

    }


    .public-space-empty p {

        color: #888;

        font-size: 13px;

        margin-bottom: 18px;

    }

}



/* =====================================================
   EXTRA SMALL MOBILE
===================================================== */

@media (max-width: 380px) {


    .public-space-mobile-card {

        padding: 10px;

    }


    .public-space-mobile-name {

        font-size: 12px;

    }


    .public-space-mobile-info strong {

        font-size: 9px;

    }


    .public-space-mobile-actions .btn {

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
   SEARCH PUBLIC SPACE MOBILE
===================================================== */

const searchPublicSpaceMobile =
    document.getElementById(
        "searchPublicSpaceMobile"
    );


if (searchPublicSpaceMobile) {

    searchPublicSpaceMobile.addEventListener(
        "input",
        function () {


            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            const cards =
                document.querySelectorAll(
                    "#publicSpaceMobileList > .col-6"
                );


            let jumlahHasil = 0;


            cards.forEach(function (card) {


                const mobileCard =
                    card.querySelector(
                        ".public-space-mobile-card"
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
                    "publicSpaceSearchEmpty"
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
                        "publicSpaceSearchEmpty";


                    empty.className =
                        "col-12";


                    empty.innerHTML = `

                        <div class="public-space-search-empty">

                            <i class="bi bi-search"></i>

                            <h6>
                                Public Space tidak ditemukan
                            </h6>

                            <p>
                                Coba gunakan kata kunci lain.
                            </p>

                        </div>

                    `;


                    document
                        .getElementById(
                            "publicSpaceMobileList"
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
   HAPUS PUBLIC SPACE
===================================================== */

function hapusPublicSpace(id) {


    Swal.fire({

        title: 'Hapus Data?',

        text: 'Data Public Space yang dihapus tidak dapat dikembalikan.',

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