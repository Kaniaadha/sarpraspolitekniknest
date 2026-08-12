<?php
session_start();

$menu = "pelaporan";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


// ==========================================
// AMBIL ID
// ==========================================

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

// ==========================================
// UPDATE STATUS & CATATAN ADMIN
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ubah_status'])) {

    $status_baru = $_POST['status'] ?? '';
    $catatan_admin = trim($_POST['catatan_admin'] ?? '');

    $status_valid = [
        'Menunggu',
        'Diproses',
        'Selesai'
    ];

    if (!in_array($status_baru, $status_valid, true)) {

        $_SESSION['error'] = "Status laporan tidak valid.";

    } else {

        // Ambil status saat ini
        $stmtStatus = mysqli_prepare($conn, "
            SELECT status
            FROM kerusakan
            WHERE id_kerusakan = ?
        ");

        mysqli_stmt_bind_param(
            $stmtStatus,
            "i",
            $id
        );

        mysqli_stmt_execute($stmtStatus);

        $resultStatus = mysqli_stmt_get_result($stmtStatus);
        $status_lama = mysqli_fetch_assoc($resultStatus)['status'] ?? '';

        mysqli_stmt_close($stmtStatus);


        // Urutan status
        $urutan_status = [
            'Menunggu' => 1,
            'Diproses' => 2,
            'Selesai' => 3
        ];


        // Tidak boleh kembali ke status sebelumnya
        if (
            isset($urutan_status[$status_lama]) &&
            isset($urutan_status[$status_baru]) &&
            $urutan_status[$status_baru] < $urutan_status[$status_lama]
        ) {

            $_SESSION['error'] =
                "Status laporan tidak dapat dikembalikan ke status sebelumnya.";

        } else {

            // Update status + catatan admin
            $stmtUpdate = mysqli_prepare($conn, "
                UPDATE kerusakan
                SET
                    status = ?,
                    catatan_admin = ?
                WHERE id_kerusakan = ?
            ");

            mysqli_stmt_bind_param(
                $stmtUpdate,
                "ssi",
                $status_baru,
                $catatan_admin,
                $id
            );

            if (mysqli_stmt_execute($stmtUpdate)) {

                $_SESSION['success'] =
                    "Laporan berhasil diperbarui.";

            } else {

                $_SESSION['error'] =
                    "Laporan gagal diperbarui.";
            }

            mysqli_stmt_close($stmtUpdate);
        }
    }

    header("Location: detail_kerusakan.php?id=" . $id);
    exit;
}

if ($id <= 0) {
    header("Location: kerusakan.php");
    exit;
}


// ==========================================
// AMBIL DETAIL LAPORAN
// ==========================================

$stmt = mysqli_prepare($conn, "
    SELECT

        k.id_kerusakan,
        k.kode_kerusakan,
        k.tanggal_lapor,
        k.nama_pelapor,
        k.status,
        k.catatan_admin,

        dk.id_detail,
        dk.bagian_rusak,
        dk.jenis_kerusakan,
        dk.tingkat_kerusakan,
        dk.kronologi,
        dk.foto,

        i.kode_inventaris,
        i.nama_barang

    FROM kerusakan k

    INNER JOIN detail_kerusakan dk
        ON k.id_kerusakan = dk.id_kerusakan

    INNER JOIN inventaris i
        ON dk.id_inventaris = i.id_inventaris

    WHERE k.id_kerusakan = ?

    LIMIT 1
");


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$data) {

    $_SESSION['error'] =
        "Data laporan kerusakan tidak ditemukan.";

    header("Location: kerusakan.php");
    exit;
}


require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>


<main class="app-main">


    <!-- ==========================================
         HEADER
    ========================================== -->

    <div class="app-content-header">

        <div class="container-fluid">

            <?php if (isset($_SESSION['success'])) : ?>

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="bi bi-check-circle me-2"></i>

                    <?= htmlspecialchars($_SESSION['success']); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php
                unset($_SESSION['success']);
            endif;
            ?>


            <?php if (isset($_SESSION['error'])) : ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    <?= htmlspecialchars($_SESSION['error']); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php
                unset($_SESSION['error']);
            endif;
            ?>

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Detail Laporan Kerusakan
                </h2>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">

                        <a href="<?= BASE_URL; ?>/admin/dashboard.php">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">
                        Transaksi
                    </li>

                    <li class="breadcrumb-item">

                        <a href="<?= BASE_URL; ?>/admin/transaksi/pelaporan/index.php">

                            Pelaporan

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        <a href="kerusakan.php">

                            Kerusakan

                        </a>

                    </li>

                    <li class="breadcrumb-item active">
                        Detail
                    </li>

                </ol>

            </div>

        </div>

    </div>


    <!-- ==========================================
         CONTENT
    ========================================== -->

    <div class="app-content">

        <div class="container-fluid">


            <!-- ======================================
                 INFORMASI LAPORAN
            ====================================== -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-file-earmark-text me-2 text-danger"></i>

                            Informasi Laporan

                        </h5>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        <!-- KODE -->

                        <div class="col-md-4">

                            <label class="text-muted small">
                                Kode Laporan
                            </label>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $data['kode_kerusakan']
                                ); ?>

                            </div>

                        </div>


                        <!-- TANGGAL -->

                        <div class="col-md-4">

                            <label class="text-muted small">
                                Tanggal Laporan
                            </label>

                            <div class="fw-semibold">

                                <?= date(
                                    'd-m-Y',
                                    strtotime($data['tanggal_lapor'])
                                ); ?>

                            </div>

                        </div>


                        <!-- PELAPOR -->

                        <div class="col-md-4">

                            <label class="text-muted small">
                                Nama Pelapor
                            </label>

                            <div class="fw-semibold">

                                <?= htmlspecialchars(
                                    $data['nama_pelapor']
                                ); ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ======================================
                 DETAIL KERUSAKAN
            ====================================== -->

            <div class="row g-4">


                <!-- DATA KERUSAKAN -->

                <div class="col-lg-7">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-tools me-2 text-danger"></i>

                                Detail Kerusakan

                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row g-4">


                                <!-- INVENTARIS -->

                                <div class="col-md-6">

                                    <label class="text-muted small">
                                        Inventaris
                                    </label>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $data['nama_barang']
                                        ); ?>

                                    </div>

                                    <small class="text-muted">

                                        <?= htmlspecialchars(
                                            $data['kode_inventaris']
                                        ); ?>

                                    </small>

                                </div>


                                <!-- BAGIAN -->

                                <div class="col-md-6">

                                    <label class="text-muted small">
                                        Bagian yang Rusak
                                    </label>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $data['bagian_rusak']
                                        ); ?>

                                    </div>

                                </div>


                                <!-- JENIS -->

                                <div class="col-md-6">

                                    <label class="text-muted small">
                                        Jenis Kerusakan
                                    </label>

                                    <div class="fw-semibold">

                                        <?= htmlspecialchars(
                                            $data['jenis_kerusakan']
                                        ); ?>

                                    </div>

                                </div>


                                <!-- TINGKAT -->

                                <div class="col-md-6">

                                    <label class="text-muted small">
                                        Tingkat Kerusakan
                                    </label>

                                    <div>

                                        <?php

                                        if (
                                            $data['tingkat_kerusakan']
                                            === 'Ringan'
                                        ) :

                                        ?>

                                            <span class="badge bg-primary">
                                                Ringan
                                            </span>

                                        <?php

                                        elseif (
                                            $data['tingkat_kerusakan']
                                            === 'Sedang'
                                        ) :

                                        ?>

                                            <span class="badge bg-warning text-dark">
                                                Sedang
                                            </span>

                                        <?php

                                        elseif (
                                            $data['tingkat_kerusakan']
                                            === 'Berat'
                                        ) :

                                        ?>

                                            <span class="badge bg-danger">
                                                Berat
                                            </span>

                                        <?php else : ?>

                                            <span class="badge bg-secondary">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>


                                <!-- KRONOLOGI -->

                                <div class="col-12">

                                    <label class="text-muted small">
                                        Kronologi Kerusakan
                                    </label>

                                    <div class="bg-light rounded p-3">

                                        <?= nl2br(
                                            htmlspecialchars(
                                                $data['kronologi']
                                            )
                                        ); ?>

                                    </div>

                                </div>

                                <!-- CATATAN ADMIN -->

                                <div class="col-12">

                                    <label class="text-muted small">
                                        Catatan Admin
                                    </label>

                                    <textarea
                                        name="catatan_admin"
                                        form="formStatus"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Tambahkan catatan tindak lanjut laporan..."><?= htmlspecialchars($data['catatan_admin'] ?? ''); ?></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- FOTO -->

                <div class="col-lg-5">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white py-3">

                            <h5 class="fw-bold mb-0">

                                <i class="bi bi-image me-2 text-danger"></i>

                                Foto Kerusakan

                            </h5>

                        </div>


                        <div class="card-body d-flex align-items-center justify-content-center">


                            <?php if (!empty($data['foto'])) : ?>

                                <img
                                    src="<?= BASE_URL . '/' . htmlspecialchars($data['foto']); ?>"
                                    alt="Foto Kerusakan"
                                    class="img-fluid rounded shadow-sm"
                                    style="max-height: 400px; object-fit: contain;">

                            <?php else : ?>

                                <div class="text-center text-muted py-5">

                                    <i class="bi bi-image fs-1 d-block mb-3"></i>

                                    Tidak ada foto kerusakan.

                                </div>

                            <?php endif; ?>


                        </div>

                    </div>

                </div>


            </div>


            <!-- ======================================
                BUTTON AKSI
            ====================================== -->

            <div class="mt-4 d-flex justify-content-between align-items-center">

                <!-- KEMBALI -->
                <a
                    href="kerusakan.php"
                    class="btn btn-secondary">

                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali

                </a>


                <!-- STATUS -->
                <form
                    method="POST"
                    id="formStatus"
                    class="d-flex align-items-center gap-2">

                    <select
                        name="status"
                        class="form-select"
                        style="width: 150px;">

                        <option
                            value="Menunggu"
                            <?= $data['status'] === 'Menunggu' ? 'selected' : ''; ?>
                            <?= in_array($data['status'], ['Diproses', 'Selesai'], true) ? 'disabled' : ''; ?>>
                            Menunggu
                        </option>

                        <option
                            value="Diproses"
                            <?= $data['status'] === 'Diproses' ? 'selected' : ''; ?>
                            <?= $data['status'] === 'Selesai' ? 'disabled' : ''; ?>>
                            Diproses
                        </option>

                        <option
                            value="Selesai"
                            <?= $data['status'] === 'Selesai' ? 'selected' : ''; ?>>
                            Selesai
                        </option>

                    </select>

                    <button
                        type="submit"
                        name="ubah_status"
                        class="btn btn-danger">

                        <i class="bi bi-check-lg me-1"></i>
                        Simpan

                    </button>

                </form>

            </div>

        </div>

    </div>

</main>


<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>