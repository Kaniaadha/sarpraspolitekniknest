<?php

session_start();

$menu = "admin";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


// =====================================================
// DATA ADMIN
// =====================================================

$query = mysqli_query($conn, "
    SELECT *
    FROM admin
    ORDER BY id_admin DESC
");


require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";

?>

<main class="app-main">


    <!-- =====================================================
         ALERT
    ====================================================== -->

    <?php if (isset($_SESSION['success'])) : ?>

        <div class="container-fluid mt-3">

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle-fill me-2"></i>

                <?= htmlspecialchars($_SESSION['success']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <?php if (isset($_SESSION['error'])) : ?>

        <div class="container-fluid mt-3">

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-circle-fill me-2"></i>

                <?= htmlspecialchars($_SESSION['error']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>



    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">

                    Data Admin

                </h2>


                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">

                        Dashboard

                    </li>

                    <li class="breadcrumb-item">

                        Master

                    </li>

                    <li class="breadcrumb-item active">

                        Admin

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

                        <i class="bi bi-people-fill me-2"></i>

                        Daftar Admin

                    </h5>


                    <a
                        href="tambah.php"
                        class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>

                        Tambah Admin

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
                            class="table table-hover align-middle mb-0 datatable">


                            <thead class="table-secondary">

                                <tr>

                                    <th
                                        width="5%"
                                        class="text-center">

                                        No

                                    </th>


                                    <th>

                                        Nama Admin

                                    </th>


                                    <th>

                                        Username

                                    </th>


                                    <th>

                                        Email

                                    </th>


                                    <th>

                                        No HP

                                    </th>


                                    <th
                                        class="text-center">

                                        Status

                                    </th>


                                    <th
                                        width="120"
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



                                        <!-- NAMA -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['nama_admin']
                                            ); ?>

                                        </td>



                                        <!-- USERNAME -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['username']
                                            ); ?>

                                        </td>



                                        <!-- EMAIL -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['email']
                                            ); ?>

                                        </td>



                                        <!-- NO HP -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $row['no_hp']
                                            ); ?>

                                        </td>



                                        <!-- STATUS -->

                                        <td class="text-center">


                                            <?php if (
                                                $row['status'] == "Aktif"
                                            ) : ?>


                                                <span
                                                    class="badge rounded-pill bg-success px-3 py-2">

                                                    Aktif

                                                </span>


                                            <?php else : ?>


                                                <span
                                                    class="badge rounded-pill bg-danger px-3 py-2">

                                                    Tidak Aktif

                                                </span>


                                            <?php endif; ?>


                                        </td>



                                        <!-- AKSI -->

                                        <td class="text-center">


                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_admin']; ?>"
                                                class="btn btn-warning btn-sm me-1"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusAdmin(
                                                    <?= $row['id_admin']; ?>
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
                                id="searchAdminMobile"
                                class="form-control"
                                placeholder="Cari nama, username, email...">

                        </div>

                    </div>



                    <!-- =================================================
                         MOBILE LIST
                    ================================================== -->

                    <div
                        class="row g-3"
                        id="adminMobileList">


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
                                        class="admin-mobile-card h-100"
                                        data-search="<?= htmlspecialchars(
                                            strtolower(
                                                $row['nama_admin'] . ' ' .
                                                $row['username'] . ' ' .
                                                $row['email'] . ' ' .
                                                $row['no_hp'] . ' ' .
                                                $row['status']
                                            )
                                        ); ?>">


                                        <!-- ==========================
                                             TOP
                                        =========================== -->

                                        <div class="admin-mobile-top">


                                            <div class="admin-mobile-icon">

                                                <i class="bi bi-person-fill"></i>

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

                                        <h6 class="admin-mobile-name">

                                            <?= htmlspecialchars(
                                                $row['nama_admin']
                                            ); ?>

                                        </h6>



                                        <!-- ==========================
                                             USERNAME
                                        =========================== -->

                                        <div class="admin-mobile-info">

                                            <span>

                                                <i class="bi bi-person-circle"></i>

                                                Username

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['username']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             EMAIL
                                        =========================== -->

                                        <div class="admin-mobile-info">

                                            <span>

                                                <i class="bi bi-envelope-fill"></i>

                                                Email

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['email']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             NO HP
                                        =========================== -->

                                        <div class="admin-mobile-info">

                                            <span>

                                                <i class="bi bi-telephone-fill"></i>

                                                No HP

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $row['no_hp']
                                                ); ?>

                                            </strong>

                                        </div>



                                        <!-- ==========================
                                             ACTION
                                        =========================== -->

                                        <div class="admin-mobile-actions">


                                            <!-- EDIT -->

                                            <a
                                                href="edit.php?id=<?= $row['id_admin']; ?>"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>



                                            <!-- HAPUS -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusAdmin(
                                                    <?= $row['id_admin']; ?>
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


                                <div class="admin-empty">


                                    <div class="admin-empty-icon">

                                        <i class="bi bi-people"></i>

                                    </div>


                                    <h5>

                                        Belum Ada Data Admin

                                    </h5>


                                    <p>

                                        Belum ada admin yang
                                        ditambahkan.

                                    </p>


                                    <a
                                        href="tambah.php"
                                        class="btn btn-primary">

                                        <i class="bi bi-plus-circle me-1"></i>

                                        Tambah Admin

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
       SHOW MOBILE
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
       ADMIN CARD
    ================================================== */

    .admin-mobile-card {

        background: #fff;

        border: 1px solid #eeeeee;

        border-radius: 14px;

        padding: 12px;

        box-shadow:
            0 3px 12px rgba(0, 0, 0, .06);

        transition: .25s ease;

        overflow: hidden;

    }


    .admin-mobile-card:active {

        transform: scale(.98);

    }


    /* =================================================
       TOP
    ================================================== */

    .admin-mobile-top {

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 5px;

        margin-bottom: 10px;

    }


    .admin-mobile-icon {

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


    .admin-mobile-top .badge {

        font-size: 9px;

        padding: 5px 7px;

        white-space: nowrap;

    }


    /* =================================================
       NAME
    ================================================== */

    .admin-mobile-name {

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

    .admin-mobile-info {

        margin-bottom: 8px;

        line-height: 1.3;

    }


    .admin-mobile-info span {

        display: block;

        color: #999;

        font-size: 9px;

        margin-bottom: 2px;

    }


    .admin-mobile-info span i {

        margin-right: 2px;

    }


    .admin-mobile-info strong {

        display: block;

        color: #444;

        font-size: 10px;

        font-weight: 500;

        word-break: break-word;

    }


    /* =================================================
       ACTION
    ================================================== */

    .admin-mobile-actions {

        display: flex;

        gap: 5px;

        margin-top: 10px;

        padding-top: 9px;

        border-top: 1px solid #eeeeee;

    }


    .admin-mobile-actions .btn {

        flex: 1;

        padding: 6px 4px;

        font-size: 11px;

        border-radius: 7px;

    }


    /* =================================================
       SEARCH EMPTY
    ================================================== */

    .admin-search-empty {

        text-align: center;

        padding: 35px 15px;

        border: 1px dashed #ddd;

        border-radius: 12px;

        background: #fafafa;

    }


    .admin-search-empty i {

        display: block;

        font-size: 28px;

        color: #ff8a00;

        margin-bottom: 10px;

    }


    .admin-search-empty h6 {

        font-weight: 600;

        margin-bottom: 5px;

    }


    .admin-search-empty p {

        margin: 0;

        font-size: 12px;

        color: #999;

    }


    /* =================================================
       EMPTY DATA
    ================================================== */

    .admin-empty {

        text-align: center;

        padding: 45px 20px;

        border: 2px dashed #e5e5e5;

        border-radius: 14px;

        background: #fafafa;

    }


    .admin-empty-icon {

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


    .admin-empty h5 {

        margin-bottom: 7px;

        font-weight: 700;

    }


    .admin-empty p {

        color: #888;

        font-size: 13px;

        margin-bottom: 18px;

    }

}



/* =====================================================
   EXTRA SMALL MOBILE
===================================================== */

@media (max-width: 380px) {


    .admin-mobile-card {

        padding: 10px;

    }


    .admin-mobile-name {

        font-size: 12px;

    }


    .admin-mobile-info strong {

        font-size: 9px;

    }


    .admin-mobile-actions .btn {

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
   SEARCH ADMIN MOBILE
===================================================== */

const searchAdminMobile =
    document.getElementById(
        "searchAdminMobile"
    );


if (searchAdminMobile) {

    searchAdminMobile.addEventListener(
        "input",
        function () {


            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            const cards =
                document.querySelectorAll(
                    "#adminMobileList > .col-6"
                );


            let jumlahHasil = 0;


            cards.forEach(function (card) {


                const mobileCard =
                    card.querySelector(
                        ".admin-mobile-card"
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
                    "adminSearchEmpty"
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
                        "adminSearchEmpty";


                    empty.className =
                        "col-12";


                    empty.innerHTML = `

                        <div class="admin-search-empty">

                            <i class="bi bi-search"></i>

                            <h6>
                                Admin tidak ditemukan
                            </h6>

                            <p>
                                Coba gunakan kata kunci lain.
                            </p>

                        </div>

                    `;


                    document
                        .getElementById(
                            "adminMobileList"
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
   HAPUS ADMIN
===================================================== */

function hapusAdmin(id) {


    Swal.fire({

        title: 'Hapus Data?',

        text: 'Data admin yang dihapus tidak dapat dikembalikan.',

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