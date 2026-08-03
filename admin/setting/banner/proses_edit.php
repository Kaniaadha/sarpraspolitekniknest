<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

// ===========================
// Ambil Data
// ===========================

$id_banner = (int) ($_POST['id_banner'] ?? 0);
$judul     = trim($_POST['judul'] ?? '');
$subjudul  = trim($_POST['subjudul'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$status    = $_POST['status'] ?? 'aktif';

// Simpan input jika terjadi error
$_SESSION['old'] = $_POST;

// ===========================
// Validasi
// ===========================

if ($id_banner <= 0) {

    $_SESSION['gagal'] = "Data banner tidak valid.";

    header("Location: index.php");
    exit;
}

if ($judul == "") {

    $_SESSION['gagal'] = "Judul banner wajib diisi.";

    header("Location: edit.php?id=$id_banner");
    exit;
}

if (!in_array($status, ['aktif', 'nonaktif'])) {

    $_SESSION['gagal'] = "Status banner tidak valid.";

    header("Location: edit.php?id=$id_banner");
    exit;
}

// ===========================
// Escape Data
// ===========================

$judul_db     = mysqli_real_escape_string($conn, $judul);
$subjudul_db  = mysqli_real_escape_string($conn, $subjudul);
$deskripsi_db = mysqli_real_escape_string($conn, $deskripsi);
$status_db    = mysqli_real_escape_string($conn, $status);

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
// Cek Judul Duplikat
// ===========================

$cekJudul = mysqli_query($conn, "
    SELECT id_banner
    FROM banner
    WHERE judul = '$judul_db'
    AND id_banner != '$id_banner'
    LIMIT 1
");

if (mysqli_num_rows($cekJudul) > 0) {

    $_SESSION['gagal'] = "Judul banner sudah digunakan.";

    header("Location: edit.php?id=$id_banner");
    exit;
}

// ===========================
// Update Data
// ===========================

$query = mysqli_query($conn, "
    UPDATE banner
    SET
        judul      = '$judul_db',
        subjudul   = '$subjudul_db',
        deskripsi  = '$deskripsi_db',
        status     = '$status_db',
        updated_at = NOW()
    WHERE id_banner = '$id_banner'
");

// ===========================
// Response
// ===========================

if ($query) {

    mysqli_query($conn, "
        INSERT INTO activity_log
        (
            id_admin,
            aktivitas,
            tabel_terkait,
            id_data
        )
        VALUES
        (
            '{$_SESSION['id_admin']}',
            'Mengubah Banner',
            'banner',
            '$id_banner'
        )
    ");

    unset($_SESSION['old']);

    $_SESSION['berhasil'] = "Banner berhasil diperbarui.";

} else {

    $_SESSION['gagal'] = "Banner gagal diperbarui.";

}

header("Location: index.php");
exit;