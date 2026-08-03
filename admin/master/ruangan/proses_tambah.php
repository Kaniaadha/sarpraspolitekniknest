<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

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

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Format Kode
// Format : RNG001
// ==============================

if (!preg_match('/^RNG\d{3}$/', $kode_ruangan)) {

    $_SESSION['error'] = "Kode ruangan harus berformat RNG001.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Luas
// ==============================

if ($luas <= 0) {

    $_SESSION['error'] = "Luas ruangan harus lebih dari 0.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Kapasitas
// ==============================

if ($kapasitas <= 0) {

    $_SESSION['error'] = "Kapasitas harus lebih dari 0.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Kode Ruangan
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_ruangan
    FROM ruangan
    WHERE kode_ruangan = '$kode_ruangan'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode ruangan sudah digunakan.";

    header("Location: tambah.php");
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
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama ruangan sudah ada pada lantai tersebut.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Simpan Database
// ==============================

$query = mysqli_query($conn, "
    INSERT INTO ruangan
    (
        id_lantai,
        kode_ruangan,
        nama_ruangan,
        luas,
        kapasitas,
        deskripsi,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$id_lantai',
        '$kode_ruangan',
        '$nama_ruangan',
        '$luas',
        '$kapasitas',
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

    $idRuangan = mysqli_insert_id($conn);

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
            'Menambah Ruangan',
            'ruangan',
            '$idRuangan'
        )
    ");

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data ruangan berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data ruangan gagal ditambahkan.";

    header("Location: tambah.php");
    exit;
}