<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

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

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Format Kode
// Format : KTG001
// ==============================

if (!preg_match('/^KTG\d{3}$/', $kode_kategori)) {

    $_SESSION['error'] = "Kode Kategori harus berformat KTG001.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Kode
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_kategori
    FROM kategori
    WHERE kode_kategori = '$kode_kategori'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode Kategori sudah digunakan.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Nama
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_kategori
    FROM kategori
    WHERE nama_kategori = '$nama_kategori'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama Kategori sudah digunakan.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Simpan Database
// ==============================

$query = mysqli_query($conn, "
    INSERT INTO kategori
    (
        kode_kategori,
        nama_kategori,
        deskripsi,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$kode_kategori',
        '$nama_kategori',
        '$deskripsi',
        '$status',
        NOW(),
        NOW()
    )
");

// ==============================
// Hasil
// ==============================

if ($query) {

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data Kategori berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data Kategori gagal ditambahkan.";

    header("Location: tambah.php");
    exit;
}