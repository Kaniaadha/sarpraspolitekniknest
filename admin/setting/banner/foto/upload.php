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
require_once "../../../../helpers/activity_log.php";
require_once "config.php";
require_once "helper.php";
require_once "service.php";

// ======================================================
// Validasi Banner
// ======================================================

$idBanner = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$idBanner) {

    $_SESSION['gagal'] = "Data banner tidak valid.";

    header("Location: index.php");
    exit;

}

$banner = getBanner($idBanner);

if (!$banner) {

    $_SESSION['gagal'] = "Data banner tidak ditemukan.";

    header("Location: ../index.php");
    exit;

}

// ======================================================
// Validasi Upload
// ======================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php?id={$idBanner}");
    exit;

}

if (
    !isset($_FILES['foto']) ||
    $_FILES['foto']['error'] !== UPLOAD_ERR_OK
) {

    $_SESSION['gagal'] = "Silakan pilih foto terlebih dahulu.";

    header("Location: index.php?id={$idBanner}");
    exit;

}

$file = $_FILES['foto'];

$extension = getExtension($file['name']);

if (!isAllowedExtension($extension)) {

    $_SESSION['gagal'] = "Format file tidak didukung.";

    header("Location: index.php?id={$idBanner}");
    exit;

}

if (!isAllowedSize($file['size'])) {

    $_SESSION['gagal'] = "Ukuran foto maksimal 5 MB.";

    header("Location: index.php?id={$idBanner}");
    exit;

}

// ======================================================
// Upload File
// ======================================================

$fileName = generateFileName($extension);

$destination = uploadPath($fileName);

if (!move_uploaded_file($file['tmp_name'], $destination)) {

    $_SESSION['gagal'] = "Upload foto gagal.";

    header("Location: index.php?id={$idBanner}");
    exit;

}

// ======================================================
// Cover Pertama
// ======================================================

$isCover = countBannerPhotos($idBanner) == 0 ? 1 : 0;

$urutan = countBannerPhotos($idBanner) + 1;

// ======================================================
// Simpan Database
// ======================================================

$sql = "
INSERT INTO foto_banner
(
    id_banner,
    nama_file,
    is_cover,
    urutan,
    created_at,
    updated_at
)
VALUES
(
    ?,
    ?,
    ?,
    ?,
    NOW(),
    NOW()
)
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "isii",

    $idBanner,
    $fileName,
    $isCover,
    $urutan

);

if (mysqli_stmt_execute($stmt)) {

    $id_foto_banner = mysqli_insert_id($conn);

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengupload Foto Banner",
        "foto_banner",
        $id_foto_banner
    );

    $_SESSION['berhasil'] = "Foto banner berhasil diupload.";

} else {

    deleteFile($fileName);

    $_SESSION['gagal'] = "Gagal menyimpan data foto.";

}

mysqli_stmt_close($stmt);

// ======================================================
// Redirect
// ======================================================

header("Location: index.php?id={$idBanner}");
exit;