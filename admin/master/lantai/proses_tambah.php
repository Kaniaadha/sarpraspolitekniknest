<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

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
// Validasi
// ==============================

if (
    empty($id_lokasi) ||
    empty($kode_lantai) ||
    empty($nama_lantai) ||
    $_POST['nomor_lantai'] === "" ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi kecuali deskripsi.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Kode Lantai
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_lantai
    FROM lantai
    WHERE kode_lantai = '$kode_lantai'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode lantai sudah digunakan.";

    header("Location: tambah.php");
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
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama lantai sudah ada pada lokasi tersebut.";

    header("Location: tambah.php");
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
");

if (mysqli_num_rows($cekNomor) > 0) {

    $_SESSION['error'] = "Nomor lantai sudah digunakan pada lokasi tersebut.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Format Kode Lantai
// Format: LNT001
// ==============================

if (!preg_match('/^LNT\d{3}$/', $kode_lantai)) {

    $_SESSION['error'] = "Kode lantai harus berformat LNT001.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Simpan Database
// ==============================

$query = mysqli_query($conn, "
    INSERT INTO lantai
    (
        id_lokasi,
        kode_lantai,
        nama_lantai,
        nomor_lantai,
        deskripsi,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$id_lokasi',
        '$kode_lantai',
        '$nama_lantai',
        '$nomor_lantai',
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

    $idLantai = mysqli_insert_id($conn);

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
            'Menambah Lantai',
            'lantai',
            '$idLantai'
        )
    ");

    $_SESSION['success'] = "Data lantai berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data lantai gagal ditambahkan.";

    header("Location: tambah.php");
    exit;
}