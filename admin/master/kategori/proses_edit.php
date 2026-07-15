<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

$id_kategori   = (int) $_POST['id_kategori'];
$kode_kategori = trim($_POST['kode_kategori']);
$nama_kategori = trim($_POST['nama_kategori']);
$deskripsi     = trim($_POST['deskripsi']);
$status        = $_POST['status'];

// ==============================
// Simpan Input
// ==============================

$_SESSION['old'] = [
    'kode_kategori' => $kode_kategori,
    'nama_kategori' => $nama_kategori,
    'deskripsi'     => $deskripsi,
    'status'        => $status
];

// ==============================
// Validasi Field
// ==============================

if (
    empty($kode_kategori) ||
    empty($nama_kategori) ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi kecuali deskripsi.";

    header("Location: edit.php?id=$id_kategori");
    exit;
}

// ==============================
// Validasi Format Kode
// ==============================

if (!preg_match('/^KTG\d{3}$/', $kode_kategori)) {

    $_SESSION['error'] = "Kode Kategori harus berformat KTG001.";

    header("Location: edit.php?id=$id_kategori");
    exit;
}

// ==============================
// Cek Kode
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_kategori
    FROM kategori
    WHERE kode_kategori = '$kode_kategori'
    AND id_kategori != '$id_kategori'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode Kategori sudah digunakan.";

    header("Location: edit.php?id=$id_kategori");
    exit;
}

// ==============================
// Cek Nama
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_kategori
    FROM kategori
    WHERE nama_kategori = '$nama_kategori'
    AND id_kategori != '$id_kategori'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama Kategori sudah digunakan.";

    header("Location: edit.php?id=$id_kategori");
    exit;
}

// ==============================
// Update Database
// ==============================

$query = mysqli_query($conn, "
    UPDATE kategori
    SET
        kode_kategori = '$kode_kategori',
        nama_kategori = '$nama_kategori',
        deskripsi = '$deskripsi',
        status = '$status',
        updated_at = NOW()
    WHERE id_kategori = '$id_kategori'
");

// ==============================
// Hasil
// ==============================

if ($query) {

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data Kategori berhasil diperbarui.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data Kategori gagal diperbarui.";

    header("Location: edit.php?id=$id_kategori");
    exit;
}