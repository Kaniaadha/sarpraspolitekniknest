<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

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

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Kode Lokasi
// ==============================

$cek = mysqli_query($conn, "
    SELECT id_lokasi
    FROM lokasi
    WHERE kode_lokasi = '$kode_lokasi'
");

if (mysqli_num_rows($cek) > 0) {

    $_SESSION['error'] = "Kode lokasi sudah digunakan.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Nama Lokasi
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_lokasi
    FROM lokasi
    WHERE nama_lokasi = '$nama_lokasi'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama lokasi sudah digunakan.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Simpan Database
// ==============================

$query = mysqli_query($conn, "
    INSERT INTO lokasi
    (
        kode_lokasi,
        nama_lokasi,
        alamat,
        deskripsi,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$kode_lokasi',
        '$nama_lokasi',
        '$alamat',
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

    $idLokasi = mysqli_insert_id($conn);

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
            'Menambah Lokasi',
            'lokasi',
            '$idLokasi'
        )
    ");

    $_SESSION['success'] = "Lokasi berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Lokasi gagal ditambahkan.";

    header("Location: tambah.php");
    exit;
}