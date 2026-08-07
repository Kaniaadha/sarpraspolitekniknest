<?php
session_start();

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// ==============================
// Ambil Data
// ==============================

$id_ruangan    = (int) $_POST['id_ruangan'];
$id_lantai     = (int) $_POST['id_lantai'];
$kode_ruangan  = trim($_POST['kode_ruangan']);
$nama_ruangan  = trim($_POST['nama_ruangan']);
$luas          = trim($_POST['luas']);
$kapasitas     = (int) $_POST['kapasitas'];
$deskripsi     = trim($_POST['deskripsi']);
$status        = $_POST['status'];

// ==============================
// Simpan Input
// ==============================

$_SESSION['old'] = [
    'id_lantai'    => $id_lantai,
    'kode_ruangan' => $kode_ruangan,
    'nama_ruangan' => $nama_ruangan,
    'luas'         => $luas,
    'kapasitas'    => $kapasitas,
    'deskripsi'    => $deskripsi,
    'status'       => $status
];

// ==============================
// Validasi Field
// ==============================

if (
    empty($id_lantai) ||
    empty($kode_ruangan) ||
    empty($nama_ruangan) ||
    $_POST['luas'] === "" ||
    $_POST['kapasitas'] === "" ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi kecuali deskripsi.";

    header("Location: edit.php?id=$id_ruangan");
    exit;
}

// ==============================
// Validasi Format Kode
// ==============================

if (!preg_match('/^RNG\d{3}$/', $kode_ruangan)) {

    $_SESSION['error'] = "Kode ruangan harus berformat RNG001.";

    header("Location: edit.php?id=$id_ruangan");
    exit;
}

// ==============================
// Validasi Luas
// ==============================

if ($luas <= 0) {

    $_SESSION['error'] = "Luas ruangan harus lebih dari 0.";

    header("Location: edit.php?id=$id_ruangan");
    exit;
}

// ==============================
// Validasi Kapasitas
// ==============================

if ($kapasitas <= 0) {

    $_SESSION['error'] = "Kapasitas harus lebih dari 0.";

    header("Location: edit.php?id=$id_ruangan");
    exit;
}

// ==============================
// Cek Kode Ruangan
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_ruangan
    FROM ruangan
    WHERE kode_ruangan = '$kode_ruangan'
    AND id_ruangan != '$id_ruangan'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode ruangan sudah digunakan.";

    header("Location: edit.php?id=$id_ruangan");
    exit;
}

// ==============================
// Cek Nama Ruangan
// Unik per lantai
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_ruangan
    FROM ruangan
    WHERE id_lantai = '$id_lantai'
    AND nama_ruangan = '$nama_ruangan'
    AND id_ruangan != '$id_ruangan'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama ruangan sudah ada pada lantai tersebut.";

    header("Location: edit.php?id=$id_ruangan");
    exit;
}

// ==============================
// Update Database
// ==============================

$query = mysqli_query($conn, "
    UPDATE ruangan
    SET
        id_lantai = '$id_lantai',
        kode_ruangan = '$kode_ruangan',
        nama_ruangan = '$nama_ruangan',
        luas = '$luas',
        kapasitas = '$kapasitas',
        deskripsi = '$deskripsi',
        status = '$status',
        updated_at = NOW()
    WHERE id_ruangan = '$id_ruangan'
");

// ==============================
// Hasil
// ==============================

if ($query) {

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengubah Ruangan",
        "ruangan",
        $id_ruangan
    );

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data ruangan berhasil diperbarui.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data ruangan gagal diperbarui.";

    header("Location: edit.php?id=$id_ruangan");
    exit;
}