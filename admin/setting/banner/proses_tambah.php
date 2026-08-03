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

$id_admin  = $_SESSION['id_admin'];
$judul     = trim($_POST['judul'] ?? '');
$subjudul  = trim($_POST['subjudul'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$status    = $_POST['status'] ?? 'aktif';

// Simpan input jika terjadi error
$_SESSION['old'] = $_POST;

// ===========================
// Validasi
// ===========================

if ($judul == "") {

    $_SESSION['gagal'] = "Judul banner wajib diisi.";

    header("Location: tambah.php");
    exit;
}

if (!in_array($status, ['aktif', 'nonaktif'])) {

    $_SESSION['gagal'] = "Status banner tidak valid.";

    header("Location: tambah.php");
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
// Cek Duplikat
// ===========================

$cek = mysqli_query($conn, "
    SELECT id_banner
    FROM banner
    WHERE judul = '$judul_db'
    LIMIT 1
");

if (mysqli_num_rows($cek) > 0) {

    $_SESSION['gagal'] = "Judul banner sudah digunakan.";

    header("Location: tambah.php");
    exit;
}

// ===========================
// Simpan Data
// ===========================

$query = mysqli_query($conn, "
    INSERT INTO banner
    (
        id_admin,
        judul,
        subjudul,
        deskripsi,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$id_admin',
        '$judul_db',
        '$subjudul_db',
        '$deskripsi_db',
        '$status_db',
        NOW(),
        NOW()
    )
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
            'Menambah Banner',
            'banner',
            '$idBanner'
        )
    ");

    unset($_SESSION['old']);

    $_SESSION['berhasil'] = "Banner berhasil ditambahkan.";

} else {

    $_SESSION['gagal'] = "Banner gagal ditambahkan.";

}

header("Location: index.php");
exit;

