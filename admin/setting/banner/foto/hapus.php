<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======================================================
// Cek Login
// ======================================================

if (!isset($_SESSION['id_admin'])) {

    header("Location: ../../../login.php");
    exit;

}

// ======================================================
// Load File
// ======================================================

require_once "../../../../config/database.php";
require_once "config.php";
require_once "helper.php";
require_once "service.php";

// ======================================================
// Validasi Request
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    header("Location: ../index.php");
    exit;

}

// ======================================================
// Validasi Parameter
// ======================================================

$idBanner = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

$idFoto = filter_input(
    INPUT_GET,
    'foto',
    FILTER_VALIDATE_INT
);

if (!$idBanner || !$idFoto) {

    $_SESSION['gagal'] = "Data tidak valid.";

    header("Location: ../index.php");
    exit;

}

// ======================================================
// Validasi Banner
// ======================================================

$banner = getBanner($idBanner);

if (!$banner) {

    $_SESSION['gagal'] = "Data banner tidak ditemukan.";

    header("Location: ../index.php");
    exit;

}

// ======================================================
// Validasi Foto
// ======================================================

$photo = getPhoto($idFoto);

if (
    !$photo ||
    (int) $photo['id_banner'] !== (int) $idBanner
) {

    $_SESSION['gagal'] = "Foto tidak ditemukan.";

    header("Location: index.php?id={$idBanner}");
    exit;

}

// ======================================================
// Database Transaction
// ======================================================

mysqli_begin_transaction($conn);

try {

    // Simpan status cover
    $isCover = (int) $photo['is_cover'];

    // Hapus file fisik
    deleteFile($photo['nama_file']);

    // Hapus database
    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM foto_banner
         WHERE id_foto_banner = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $idFoto
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal menghapus foto.");
    }

    mysqli_stmt_close($stmt);

    // Rapikan urutan
    reorderBannerGallery(
        $conn,
        $idBanner
    );

    // Jika cover dihapus,
    // jadikan foto pertama sebagai cover
    if ($isCover === 1) {

        mysqli_query($conn, "
            UPDATE foto_banner
            SET is_cover = 0
            WHERE id_banner = '{$idBanner}'
        ");

        setFirstCover(
            $conn,
            $idBanner
        );

    }

    mysqli_commit($conn);

    $_SESSION['berhasil'] =
        "Foto banner berhasil dihapus.";

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['gagal'] =
        $e->getMessage();

}

// ======================================================
// Redirect
// ======================================================

header("Location: index.php?id={$idBanner}");
exit;