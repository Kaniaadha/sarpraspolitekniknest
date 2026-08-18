<?php

session_start();

$menu = "inventaris";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


// =====================================================
// DATA INVENTARIS
// =====================================================

$query = mysqli_query($conn, "
    SELECT
        i.*,
        k.nama_kategori,
        r.nama_ruangan,
        ps.nama_public_space

    FROM inventaris i

    LEFT JOIN kategori k
        ON i.id_kategori = k.id_kategori

    LEFT JOIN ruangan r
        ON i.id_ruangan = r.id_ruangan

    LEFT JOIN public_space ps
        ON i.id_public_space = ps.id_public_space

    ORDER BY
        i.nama_barang ASC
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

                    Data Inventaris

                </h2>


                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">

                        Dashboard

                    </li>

                    <li class="breadcrumb-item">

                        Master

                    </li>

                    <li class="breadcrumb-item active">

                        Inventaris

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

                        <i class="bi bi-box-seam-fill me-2"></i>

                        Daftar Inventaris

                    </h5>


                    <a
                        href="tambah.php"
                        class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>

                        Tambah Inventaris

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

                                        Nama Barang

                                    </th>

                                    <th>

                                        Kategori

                                    </th>

                                    <th>

                                        Jumlah

                                    </th>

                                    <th>

                                        Harga

                                    </th>

                                    <th>

                                        Kondisi

                                    </th>

                                    <th>

                                        Status

                                    </th>

                                    <th
                                        width="17%"
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
                                                $row['kode_inventaris']
                                            ); ?>

                                        </td>



                                        <!-- NAMA BARANG -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_barang']
                                            ); ?>

                                        </td>



                                        <!-- KATEGORI -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_kategori'] ?? '-'
                                            ); ?>

                                        </td>



                                        <!-- JUMLAH -->

                                        <td>

                                            <?= number_format(
                                                $row['jumlah']
                                            ); ?>

                                        </td>



                                        <!-- HARGA -->

                                        <td>

                                            <?php if (
                                                $row['harga'] !== null
                                                && $row['harga'] !== ''
                                            ) : ?>

                                                Rp
                                                <?= number_format(
                                                    $row['harga'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ); ?>

                                            <?php else : ?>

                                                -

                                            <?php endif; ?>

                                        </td>



                                        <!-- KONDISI -->

                                        <td>

                                            <?php

                                            $kondisi =
                                                $row['kondisi'] ?? '';

                                            ?>


                                            <?php if (
                                                $kondisi == 'Baik'
                                            ) : ?>

                                                <span
                                                    class="badge rounded-pill bg-success">

                                                    Baik

                                                </span>


                                            <?php elseif (
                                                $kondisi == 'Rusak Ringan'
                                            ) : ?>

                                                <span
                                                    class="badge rounded-pill bg-warning text-dark">

                                                    Rusak Ringan

                                                </span>


                                            <?php elseif (
                                                $kondisi == 'Rusak Berat'
                                            ) : ?>

                                                <span
                                                    class="badge rounded-pill bg-danger">

                                                    Rusak Berat

                                                </span>


                                            <?php else : ?>

                                                <span
                                                    class="badge rounded-pill bg-secondary">

                                                    <?= htmlspecialchars(
                                                        $kondisi ?: '-'
                                                    ); ?>

                                                </span>

                                            <?php endif; ?>

                                        </td>



                                        <!-- STATUS -->

                                        <td>

                                            <?php if (
                                                $row['status'] == 'Aktif'
                                            ) : ?>

                                                <span
                                                    class="badge rounded-pill bg-success">

                                                    Aktif

                                                </span>

                                            <?php else : ?>

                                                <span
                                                    class="badge rounded-pill bg-secondary">

                                                    Nonaktif

                                                </span>

                                            <?php endif; ?>

                                        </td>



                                        <!-- AKSI -->

                                        <td class="text-center">


                                            <!-- FOTO -->

                                            <a
                                                href="../foto/index.php?tipe=inventaris&id=<?= $row['id_inventaris']; ?>"
                                                class="btn btn-info btn-sm me-1"
                                                title="Gallery Foto">

                                                <i class="bi bi-images"></i>

                                            </a>



                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_inventaris']; ?>"
                                                class="btn btn-warning btn-sm me-1"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusInventaris(
                                                    <?= $row['id_inventaris']; ?>
                                                )">

                                                <i class="bi bi-trash"></i>

                                            </button>


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
                                id="searchInventarisMobile"
                                class="form-control"
                                placeholder="Cari barang, kode, kategori...">

                        </div>

                    </div>



                    <!-- =================================================
                         MOBILE LIST
                    ================================================== -->

                    <div
                        class="row g-3"
                        id="inventarisMobileList">


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
                                        class="inventaris-mobile-card h-100"
                                        data-search="<?= htmlspecialchars(
                                            strtolower(
                                                $row['kode_inventaris'] . ' ' .
                                                $row['nama_barang'] . ' ' .
                                                ($row['nama_kategori'] ?? '') . ' ' .
                                                ($row['merk'] ?? '') . ' ' .
                                                ($row['kondisi'] ?? '') . ' ' .
                                                ($row['nama_ruangan'] ?? '') . ' ' .
                                                ($row['nama_public_space'] ?? '')
                                            )
                                        ); ?>">


                                        <!-- ==========================
                                             TOP
                                        =========================== -->

                                        <div class="inventaris-mobile-top">


                                            <div class="inventaris-mobile-icon">

                                                <i class="bi bi-box-seam-fill"></i>

                                            </div>


                                            <?php if (
                                                $row['status'] == 'Aktif'
                                            ) : ?>

                                                <span
                                                    class="badge bg-success rounded-pill">

                                                    Aktif

                                                </span>

                                            <?php else : ?>

                                                <span
                                                    class="badge bg-secondary rounded-pill">

                                                    Nonaktif

                                                </span>

                                            <?php endif; ?>


                                        </div>



                                        <!-- ==========================
                                             NAMA BARANG
                                        =========================== -->

                                        <h6 class="inventaris-mobile-name">

                                            <?= htmlspecialchars(
                                                $row['nama_barang']
                                            ); ?>

                                        </h6>



                                        <!-- ==========================
                                             KODE
                                        =========================== -->

                                        <div class="inventaris-mobile-info">

                                            <span>

                                                <i class="bi bi-upc-scan"></i>

                                                Kode

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['kode_inventaris']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             KATEGORI
                                        =========================== -->

                                        <div class="inventaris-mobile-info">

                                            <span>

                                                <i class="bi bi-tags-fill"></i>

                                                Kategori

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['nama_kategori'] ?? '-'
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             MERK
                                        =========================== -->

                                        <?php if (
                                            !empty($row['merk'])
                                        ) : ?>

                                            <div class="inventaris-mobile-info">

                                                <span>

                                                    <i class="bi bi-bookmark-fill"></i>

                                                    Merk

                                                </span>


                                                <strong>

                                                    <?= htmlspecialchars(
                                                        $row['merk']
                                                    ); ?>

                                                </strong>

                                            </div>

                                        <?php endif; ?>



                                        <!-- ==========================
                                             JUMLAH
                                        =========================== -->

                                        <div class="inventaris-mobile-info">

                                            <span>

                                                <i class="bi bi-boxes"></i>

                                                Jumlah

                                            </span>


                                            <strong>

                                                <?= number_format(
                                                    $row['jumlah']
                                                ); ?>

                                                unit

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             HARGA
                                        =========================== -->

                                        <div class="inventaris-mobile-info">

                                            <span>

                                                <i class="bi bi-cash-stack"></i>

                                                Harga

                                            </span>


                                            <strong>

                                                <?php if (
                                                    $row['harga'] !== null
                                                    && $row['harga'] !== ''
                                                ) : ?>

                                                    Rp
                                                    <?= number_format(
                                                        $row['harga'],
                                                        0,
                                                        ',',
                                                        '.'
                                                    ); ?>

                                                <?php else : ?>

                                                    -

                                                <?php endif; ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             KONDISI
                                        =========================== -->

                                        <div class="inventaris-mobile-info">

                                            <span>

                                                <i class="bi bi-clipboard-check"></i>

                                                Kondisi

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['kondisi'] ?? '-'
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             PENEMPATAN
                                        =========================== -->

                                        <?php

                                        if (
                                            !empty($row['nama_ruangan'])
                                        ) {

                                            $penempatan =
                                                $row['nama_ruangan'];

                                        } elseif (
                                            !empty($row['nama_public_space'])
                                        ) {

                                            $penempatan =
                                                $row['nama_public_space'];

                                        } else {

                                            $penempatan = '-';

                                        }

                                        ?>


                                        <div class="inventaris-mobile-info">

                                            <span>

                                                <i class="bi bi-geo-alt-fill"></i>

                                                Penempatan

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $penempatan
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             ACTION
                                        =========================== -->

                                        <div class="inventaris-mobile-actions">


                                            <!-- GALLERY -->

                                            <a
                                                href="../foto/index.php?tipe=inventaris&id=<?= $row['id_inventaris']; ?>"
                                                class="btn btn-info btn-sm"
                                                title="Gallery">

                                                <i class="bi bi-images"></i>

                                            </a>



                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_inventaris']; ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusInventaris(
                                                    <?= $row['id_inventaris']; ?>
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


                                <div class="inventaris-empty">


                                    <div class="inventaris-empty-icon">

                                        <i class="bi bi-box-seam"></i>

                                    </div>


                                    <h5>

                                        Belum Ada Data Inventaris

                                    </h5>


                                    <p>

                                        Belum ada data inventaris
                                        yang ditambahkan.

                                    </p>


                                    <a
                                        href="tambah.php"
                                        class="btn btn-primary">

                                        <i class="bi bi-plus-circle me-1"></i>

                                        Tambah Inventaris

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


.mobile-cards {

    display: none;

}


@media (max-width: 767.98px) {


    /* =================================================
       DESKTOP TABLE
    ================================================== */

    .desktop-table {

        display: none;

    }


    /* =================================================
       MOBILE
    ================================================== */

    .mobile-cards {

        display: block;

    }


    /* =================================================
       HEADER
    ================================================== */

    .app-content-header h2 {

        font-size: 20px;

    }


    .app-content-header .breadcrumb {

        display: none;

    }


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
       CARD
    ================================================== */

    .inventaris-mobile-card {

        background: #fff;

        border: 1px solid #eeeeee;

        border-radius: 14px;

        padding: 12px;

        box-shadow:
            0 3px 12px rgba(0, 0, 0, .06);

        transition: .25s ease;

        overflow: hidden;

    }


    .inventaris-mobile-card:active {

        transform: scale(.98);

    }


    /* =================================================
       TOP
    ================================================== */

    .inventaris-mobile-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 5px;

        margin-bottom: 10px;

    }


    .inventaris-mobile-icon {

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


    .inventaris-mobile-top .badge {

        font-size: 9px;

        padding: 5px 7px;

        white-space: nowrap;

    }


    /* =================================================
       NAME
    ================================================== */

    .inventaris-mobile-name {

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

    .inventaris-mobile-info {

        margin-bottom: 8px;

        line-height: 1.3;

    }


    .inventaris-mobile-info span {

        display: block;

        color: #999;

        font-size: 9px;

        margin-bottom: 2px;

    }


    .inventaris-mobile-info span i {

        margin-right: 2px;

    }


    .inventaris-mobile-info strong {

        display: block;

        color: #444;

        font-size: 10px;

        font-weight: 500;

        word-break: break-word;

    }


    /* =================================================
       ACTION
    ================================================== */

    .inventaris-mobile-actions {

        display: flex;

        gap: 5px;

        margin-top: 10px;

        padding-top: 9px;

        border-top: 1px solid #eeeeee;

    }


    .inventaris-mobile-actions .btn {

        flex: 1;

        padding: 6px 4px;

        font-size: 11px;

        border-radius: 7px;

    }


    /* =================================================
       SEARCH EMPTY
    ================================================== */

    .inventaris-search-empty {

        text-align: center;

        padding: 35px 15px;

        border: 1px dashed #ddd;

        border-radius: 12px;

        background: #fafafa;

    }


    .inventaris-search-empty i {

        display: block;

        font-size: 28px;

        color: #ff8a00;

        margin-bottom: 10px;

    }


    .inventaris-search-empty h6 {

        font-weight: 600;

        margin-bottom: 5px;

    }


    .inventaris-search-empty p {

        margin: 0;

        font-size: 12px;

        color: #999;

    }


    /* =================================================
       EMPTY
    ================================================== */

    .inventaris-empty {

        text-align: center;

        padding: 45px 20px;

        border: 2px dashed #e5e5e5;

        border-radius: 14px;

        background: #fafafa;

    }


    .inventaris-empty-icon {

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


    .inventaris-empty h5 {

        margin-bottom: 7px;

        font-weight: 700;

    }


    .inventaris-empty p {

        color: #888;

        font-size: 13px;

        margin-bottom: 18px;

    }

}


/* =====================================================
   EXTRA SMALL MOBILE
===================================================== */

@media (max-width: 380px) {


    .inventaris-mobile-card {

        padding: 10px;

    }


    .inventaris-mobile-name {

        font-size: 12px;

    }


    .inventaris-mobile-info strong {

        font-size: 9px;

    }


    .inventaris-mobile-actions .btn {

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
   SEARCH INVENTARIS MOBILE
===================================================== */

const searchInventarisMobile =
    document.getElementById(
        "searchInventarisMobile"
    );


if (searchInventarisMobile) {

    searchInventarisMobile.addEventListener(
        "input",
        function () {


            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            const cards =
                document.querySelectorAll(
                    "#inventarisMobileList > .col-6"
                );


            let jumlahHasil = 0;


            cards.forEach(function (card) {


                const mobileCard =
                    card.querySelector(
                        ".inventaris-mobile-card"
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
                    "inventarisSearchEmpty"
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
                        "inventarisSearchEmpty";


                    empty.className =
                        "col-12";


                    empty.innerHTML = `

                        <div class="inventaris-search-empty">

                            <i class="bi bi-search"></i>

                            <h6>
                                Inventaris tidak ditemukan
                            </h6>

                            <p>
                                Coba gunakan kata kunci lain.
                            </p>

                        </div>

                    `;


                    document
                        .getElementById(
                            "inventarisMobileList"
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
   HAPUS INVENTARIS
===================================================== */

function hapusInventaris(id) {


    Swal.fire({

        title: 'Hapus Data?',

        text: 'Data Inventaris yang dihapus tidak dapat dikembalikan.',

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