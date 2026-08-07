<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// ===========================
// Ambil ID
// ===========================

$id_banner = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_banner <= 0) {

    $_SESSION['gagal'] = "Data banner tidak valid.";

    header("Location: index.php");
    exit;
}

// ===========================
// Cek Banner
// ===========================

$cekBanner = mysqli_query($conn, "
    SELECT id_banner
    FROM banner
    WHERE id_banner = '$id_banner'
    LIMIT 1
");

if (mysqli_num_rows($cekBanner) == 0) {

    $_SESSION['gagal'] = "Data banner tidak ditemukan.";

    header("Location: index.php");
    exit;
}

// ===========================
// Hapus Semua File Foto Banner
// ===========================

$foto = mysqli_query($conn, "
    SELECT nama_file
    FROM foto_banner
    WHERE id_banner='$id_banner'
");

while ($row = mysqli_fetch_assoc($foto)) {

    $path = "../../../assets/uploads/banner/" . $row['nama_file'];

    if (file_exists($path)) {

        unlink($path);

    }
}

// ===========================
// Hapus Data Foto Banner
// ===========================

mysqli_query($conn, "
    DELETE FROM foto_banner
    WHERE id_banner='$id_banner'
");

// ===========================
// Hapus Banner
// ===========================

$query = mysqli_query($conn, "
    DELETE FROM banner
    WHERE id_banner='$id_banner'
");

// ===========================
// Response
// ===========================

if ($query) {

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Menghapus Banner",
        "banner",
        $id_banner
    );

    $_SESSION['berhasil'] = "Banner berhasil dihapus.";

} else {

    $_SESSION['gagal'] = "Banner gagal dihapus.";

}

header("Location: index.php");
exit;