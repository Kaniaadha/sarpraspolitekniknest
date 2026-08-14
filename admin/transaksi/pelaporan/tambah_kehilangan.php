<?php
session_start();

$menu = "pelaporan";

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";


// ==========================================
// PROSES SIMPAN LAPORAN
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_pelapor      = trim($_POST['nama_pelapor'] ?? '');
    $id_inventaris     = (int) ($_POST['id_inventaris'] ?? 0);
    $lokasi_kehilangan = trim($_POST['lokasi_kehilangan'] ?? '');
    $kronologi         = trim($_POST['kronologi'] ?? '');


    // ==========================================
    // VALIDASI DATA
    // ==========================================

    if (
        $nama_pelapor === '' ||
        $id_inventaris <= 0 ||
        $lokasi_kehilangan === '' ||
        $kronologi === ''
    ) {
        $_SESSION['error'] = "Semua data wajib diisi.";
        header("Location: tambah_kehilangan.php");
        exit;
    }


    // ==========================================
    // VALIDASI INVENTARIS
    // ==========================================

    $stmtInventaris = mysqli_prepare($conn, "
        SELECT id_inventaris
        FROM inventaris
        WHERE id_inventaris = ?
        AND status = 'Aktif'
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmtInventaris,
        "i",
        $id_inventaris
    );

    mysqli_stmt_execute($stmtInventaris);

    $resultInventaris = mysqli_stmt_get_result($stmtInventaris);

    if (mysqli_num_rows($resultInventaris) === 0) {

        mysqli_stmt_close($stmtInventaris);

        $_SESSION['error'] =
            "Inventaris tidak ditemukan atau tidak aktif.";

        header("Location: tambah_kehilangan.php");
        exit;
    }

    mysqli_stmt_close($stmtInventaris);


    // ==========================================
    // SIMPAN DATABASE
    // ==========================================

    mysqli_begin_transaction($conn);

    try {

        // ======================================
        // GENERATE KODE LAPORAN
        // Format: KHL-YYMMDD-001
        // ======================================

        $tanggalKode = date('ymd');

        $stmtKode = mysqli_prepare($conn, "
            SELECT MAX(
                CAST(SUBSTRING_INDEX(kode_kehilangan, '-', -1) AS UNSIGNED)
            ) AS nomor_terakhir
            FROM kehilangan
            WHERE tanggal_lapor = CURDATE()
            AND kode_kehilangan LIKE ?
        ");

        if (!$stmtKode) {
            throw new Exception(
                "Gagal membuat kode laporan kehilangan."
            );
        }

        $patternKode = 'KHL-' . $tanggalKode . '-%';

        mysqli_stmt_bind_param(
            $stmtKode,
            "s",
            $patternKode
        );

        mysqli_stmt_execute($stmtKode);

        $resultKode = mysqli_stmt_get_result($stmtKode);
        $rowKode = mysqli_fetch_assoc($resultKode);

        $nomorBerikutnya =
            ($rowKode['nomor_terakhir'] ?? 0) + 1;

        mysqli_stmt_close($stmtKode);

        $kodeKehilangan =
            'KHL-' .
            $tanggalKode .
            '-' .
            str_pad(
                $nomorBerikutnya,
                3,
                '0',
                STR_PAD_LEFT
            );


        // ======================================
        // SIMPAN DATA UTAMA
        // ======================================

        $stmtKehilangan = mysqli_prepare($conn, "
            INSERT INTO kehilangan
            (
                kode_kehilangan,
                tanggal_lapor,
                nama_pelapor
            )
            VALUES
            (
                ?,
                CURDATE(),
                ?
            )
        ");

        if (!$stmtKehilangan) {
            throw new Exception(
                "Gagal menyiapkan data laporan."
            );
        }


        mysqli_stmt_bind_param(
            $stmtKehilangan,
            "ss",
            $kodeKehilangan,
            $nama_pelapor
        );


        if (!mysqli_stmt_execute($stmtKehilangan)) {
            throw new Exception(
                "Gagal menyimpan laporan kehilangan."
            );
        }


        $idKehilangan = mysqli_insert_id($conn);

        mysqli_stmt_close($stmtKehilangan);


        // ======================================
        // SIMPAN DETAIL KEHILANGAN
        // ======================================

        $stmtDetail = mysqli_prepare($conn, "
            INSERT INTO detail_kehilangan
            (
                id_kehilangan,
                id_inventaris,
                lokasi_kehilangan,
                kronologi
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?
            )
        ");

        if (!$stmtDetail) {
            throw new Exception(
                "Gagal menyiapkan detail laporan."
            );
        }


        mysqli_stmt_bind_param(
            $stmtDetail,
            "iiss",
            $idKehilangan,
            $id_inventaris,
            $lokasi_kehilangan,
            $kronologi
        );


        if (!mysqli_stmt_execute($stmtDetail)) {
            throw new Exception(
                "Gagal menyimpan detail kehilangan."
            );
        }


        mysqli_stmt_close($stmtDetail);


        // ======================================
        // COMMIT
        // ======================================

        mysqli_commit($conn);

        simpanActivityLog(
            $conn,
            $_SESSION['id_admin'],
            "Menambah Laporan Kehilangan",
            "kehilangan",
            $idKehilangan
        );

        $_SESSION['success'] =
            "Laporan kehilangan berhasil ditambahkan. Kode laporan: "
            . $kodeKehilangan;


        header("Location: kehilangan.php");
        exit;


    } catch (Exception $e) {

        mysqli_rollback($conn);

        $_SESSION['error'] =
            $e->getMessage();

        header("Location: tambah_kehilangan.php");
        exit;
    }
}


// ==========================================
// AMBIL DATA INVENTARIS
// ==========================================

$queryInventaris = mysqli_query($conn, "
    SELECT
        id_inventaris,
        kode_inventaris,
        nama_barang
    FROM inventaris
    WHERE status = 'Aktif'
    ORDER BY nama_barang ASC
");


// ==========================================
// TEMPLATE
// ==========================================

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>


<main class="app-main">


    <!-- HEADER -->

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Tambah Laporan Kehilangan
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

                        <a href="kehilangan.php">
                            Kehilangan
                        </a>

                    </li>


                    <li class="breadcrumb-item active">
                        Tambah
                    </li>

                </ol>

            </div>

        </div>

    </div>



    <!-- CONTENT -->

    <div class="app-content">

        <div class="container-fluid">


            <div class="card border-0 shadow-sm">


                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>

                        Form Laporan Kehilangan

                    </h5>

                </div>



                <div class="card-body p-4">


                    <?php if (isset($_SESSION['error'])) : ?>

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-circle me-2"></i>

                            <?= htmlspecialchars($_SESSION['error']); ?>

                        </div>

                    <?php
                        unset($_SESSION['error']);
                    endif;
                    ?>


                    <form
                        method="POST">


                        <div class="row g-4">


                            <!-- PELAPOR -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Nama Pelapor
                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="text"
                                    name="nama_pelapor"
                                    class="form-control"
                                    placeholder="Masukkan nama pelapor"
                                    required>

                            </div>



                            <!-- INVENTARIS -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Inventaris
                                    <span class="text-danger">*</span>

                                </label>


                                <select
                                    name="id_inventaris"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Pilih inventaris
                                    </option>


                                    <?php while (
                                        $inventaris =
                                        mysqli_fetch_assoc(
                                            $queryInventaris
                                        )
                                    ) : ?>

                                        <option
                                            value="<?= $inventaris['id_inventaris']; ?>">

                                            <?= htmlspecialchars(
                                                $inventaris['nama_barang']
                                            ); ?>

                                            -

                                            <?= htmlspecialchars(
                                                $inventaris['kode_inventaris']
                                            ); ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>

                            </div>



                            <!-- LOKASI KEHILANGAN -->

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Lokasi Kehilangan
                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="text"
                                    name="lokasi_kehilangan"
                                    class="form-control"
                                    placeholder="Contoh: Ruang 201, Gedung A"
                                    required>

                            </div>



                            <!-- KRONOLOGI -->

                            <div class="col-12">

                                <label class="form-label fw-semibold">

                                    Kronologi Kehilangan
                                    <span class="text-danger">*</span>

                                </label>


                                <textarea
                                    name="kronologi"
                                    class="form-control"
                                    rows="5"
                                    maxlength="2000"
                                    placeholder="Jelaskan kronologi terjadinya kehilangan..."
                                    required></textarea>

                            </div>



                            <!-- BUTTON -->

                            <div class="col-12">

                                <hr>


                                <div class="d-flex justify-content-end gap-2">


                                    <a
                                        href="kehilangan.php"
                                        class="btn btn-secondary">

                                        <i class="bi bi-arrow-left me-1"></i>

                                        Batal

                                    </a>


                                    <button
                                        type="submit"
                                        class="btn btn-danger">

                                        <i class="bi bi-save me-1"></i>

                                        Simpan Laporan

                                    </button>


                                </div>

                            </div>


                        </div>

                    </form>

                </div>

            </div>


        </div>

    </div>

</main>


<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>