<?php
session_start();

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// ==============================
// Ambil Data
// ==============================

$id_lantai    = (int) $_POST['id_lantai'];
$id_lokasi    = (int) $_POST['id_lokasi'];
$kode_lantai  = trim($_POST['kode_lantai']);
$nama_lantai  = trim($_POST['nama_lantai']);
$nomor_lantai = (int) $_POST['nomor_lantai'];
$deskripsi    = trim($_POST['deskripsi']);
$status       = $_POST['status'];

// ==============================
// Simpan Input
// ==============================

$_SESSION['old'] = [
    'id_lokasi'    => $id_lokasi,
    'kode_lantai'  => $kode_lantai,
    'nama_lantai'  => $nama_lantai,
    'nomor_lantai' => $nomor_lantai,
    'deskripsi'    => $deskripsi,
    'status'       => $status
];

// ==============================
// Validasi Field
// ==============================

if (
    empty($id_lokasi) ||
    empty($kode_lantai) ||
    empty($nama_lantai) ||
    $_POST['nomor_lantai'] === "" ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi kecuali deskripsi.";

    header("Location: edit.php?id=$id_lantai");
    exit;
}

// ==============================
// Validasi Format Kode
// ==============================

if (!preg_match('/^LNT\d{3}$/', $kode_lantai)) {

    $_SESSION['error'] = "Kode lantai harus berformat LNT001.";

    header("Location: edit.php?id=$id_lantai");
    exit;
}

// ==============================
// Cek Kode Lantai
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_lantai
    FROM lantai
    WHERE kode_lantai = '$kode_lantai'
    AND id_lantai != '$id_lantai'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode lantai sudah digunakan.";

    header("Location: edit.php?id=$id_lantai");
    exit;
}

// ==============================
// Cek Nama Lantai
// (unik per lokasi)
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_lantai
    FROM lantai
    WHERE id_lokasi = '$id_lokasi'
    AND nama_lantai = '$nama_lantai'
    AND id_lantai != '$id_lantai'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama lantai sudah ada pada lokasi tersebut.";

    header("Location: edit.php?id=$id_lantai");
    exit;
}

// ==============================
// Cek Nomor Lantai
// (unik per lokasi)
// ==============================

$cekNomor = mysqli_query($conn, "
    SELECT id_lantai
    FROM lantai
    WHERE id_lokasi = '$id_lokasi'
    AND nomor_lantai = '$nomor_lantai'
    AND id_lantai != '$id_lantai'
");

if (mysqli_num_rows($cekNomor) > 0) {

    $_SESSION['error'] = "Nomor lantai sudah digunakan pada lokasi tersebut.";

    header("Location: edit.php?id=$id_lantai");
    exit;
}

// ==============================
// Update Database
// ==============================

$query = mysqli_query($conn, "
    UPDATE lantai
    SET
        id_lokasi = '$id_lokasi',
        kode_lantai = '$kode_lantai',
        nama_lantai = '$nama_lantai',
        nomor_lantai = '$nomor_lantai',
        deskripsi = '$deskripsi',
        status = '$status',
        updated_at = NOW()
    WHERE id_lantai = '$id_lantai'
");

// ==============================
// Hasil
// ==============================

if ($query) {
    
    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengubah Lantai",
        "lantai",
        $id_lantai
    );
    unset($_SESSION['old']);

    $_SESSION['success'] = "Data lantai berhasil diperbarui.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data lantai gagal diperbarui.";

    header("Location: edit.php?id=$id_lantai");
    exit;
}