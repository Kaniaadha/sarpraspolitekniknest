<?php
session_start();

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// ==============================
// Ambil Data
// ==============================

$id_lokasi   = (int) $_POST['id_lokasi'];
$kode_lokasi = trim($_POST['kode_lokasi']);
$nama_lokasi = trim($_POST['nama_lokasi']);
$alamat      = trim($_POST['alamat']);
$deskripsi   = trim($_POST['deskripsi']);
$status      = $_POST['status'];

// ==============================
// Simpan Input
// ==============================

$_SESSION['old'] = [
    'kode_lokasi' => $kode_lokasi,
    'nama_lokasi' => $nama_lokasi,
    'alamat'      => $alamat,
    'deskripsi'   => $deskripsi,
    'status'      => $status
];

// ==============================
// Validasi
// ==============================

if (
    empty($kode_lokasi) ||
    empty($nama_lokasi) ||
    empty($alamat) ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi kecuali deskripsi.";

    header("Location: edit.php?id=$id_lokasi");
    exit;
}

// ==============================
// Cek Kode Lokasi
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_lokasi
    FROM lokasi
    WHERE kode_lokasi = '$kode_lokasi'
    AND id_lokasi != '$id_lokasi'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode lokasi sudah digunakan.";

    header("Location: edit.php?id=$id_lokasi");
    exit;
}

// ==============================
// Cek Nama Lokasi
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_lokasi
    FROM lokasi
    WHERE nama_lokasi = '$nama_lokasi'
    AND id_lokasi != '$id_lokasi'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama lokasi sudah digunakan.";

    header("Location: edit.php?id=$id_lokasi");
    exit;
}

// ==============================
// Update Database
// ==============================

$query = mysqli_query($conn, "
    UPDATE lokasi
    SET
        kode_lokasi = '$kode_lokasi',
        nama_lokasi = '$nama_lokasi',
        alamat = '$alamat',
        deskripsi = '$deskripsi',
        status = '$status',
        updated_at = NOW()
    WHERE id_lokasi = '$id_lokasi'
");

// ==============================
// Hasil
// ==============================

if ($query) {
    
    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengubah Lokasi",
        "lokasi",
        $id_lokasi
    );
    
    unset($_SESSION['old']);

    $_SESSION['success'] = "Data lokasi berhasil diperbarui.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data lokasi gagal diperbarui.";

    header("Location: edit.php?id=$id_lokasi");
    exit;
}