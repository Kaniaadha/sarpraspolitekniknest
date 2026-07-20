<?php
session_start();

require_once "../../../config/database.php";
require_once "../../../helpers/generate_kode.php";

// ==============================
// Ambil Data
// ==============================

$kode_inventaris = generateKode(
    $conn,
    "inventaris",
    "kode_inventaris",
    "INV"
);
$id_kategori       = (int) $_POST['id_kategori'];
$nama_barang       = trim($_POST['nama_barang']);
$merk              = trim($_POST['merk']);
$spesifikasi       = trim($_POST['spesifikasi']);

$jenis_penempatan  = $_POST['jenis_penempatan'];

$id_ruangan        = !empty($_POST['id_ruangan']) ? (int) $_POST['id_ruangan'] : NULL;
$id_public_space   = !empty($_POST['id_public_space']) ? (int) $_POST['id_public_space'] : NULL;

$jumlah            = (int) $_POST['jumlah'];
$kondisi           = $_POST['kondisi'];
$tahun_perolehan   = trim($_POST['tahun_perolehan']);
$sumber_perolehan  = trim($_POST['sumber_perolehan']);
$status            = $_POST['status'];

$currentYear = date('Y');

// ==============================
// Simpan Old Input
// ==============================

$_SESSION['old'] = $_POST;

// ==============================
// Validasi Wajib
// ==============================

if (
    empty($kode_inventaris) ||
    empty($id_kategori) ||
    empty($nama_barang) ||
    empty($jenis_penempatan) ||
    empty($jumlah) ||
    empty($kondisi) ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi kecuali Merk, Spesifikasi, Tahun Perolehan, dan Sumber Perolehan.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Format Kode
// ==============================

if (!preg_match('/^INV\d{3}$/', $kode_inventaris)) {

    $_SESSION['error'] = "Kode Inventaris harus berformat INV001.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Jumlah
// ==============================

if ($jumlah <= 0) {

    $_SESSION['error'] = "Jumlah harus lebih dari 0.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Tahun
// ==============================

if (!empty($tahun_perolehan)) {

    if ($tahun_perolehan < 1900 || $tahun_perolehan > $currentYear) {

        $_SESSION['error'] = "Tahun Perolehan tidak valid.";

        header("Location: tambah.php");
        exit;

    }

}

// ==============================
// Validasi Penempatan
// ==============================

if ($jenis_penempatan == "ruangan") {

    if (empty($id_ruangan)) {

        $_SESSION['error'] = "Silakan pilih Ruangan.";

        header("Location: tambah.php");
        exit;

    }

    $id_public_space = NULL;

}

elseif ($jenis_penempatan == "public") {

    if (empty($id_public_space)) {

        $_SESSION['error'] = "Silakan pilih Public Space.";

        header("Location: tambah.php");
        exit;

    }

    $id_ruangan = NULL;

}

else {

    $_SESSION['error'] = "Jenis Penempatan tidak valid.";

    header("Location: tambah.php");
    exit;

}

// ==============================
// Validasi Kode Unik
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_inventaris
    FROM inventaris
    WHERE kode_inventaris='$kode_inventaris'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode Inventaris sudah digunakan.";

    header("Location: tambah.php");
    exit;

}

// ==============================
// Simpan Database
// ==============================

$id_ruangan_sql = is_null($id_ruangan) ? "NULL" : "'$id_ruangan'";
$id_public_sql  = is_null($id_public_space) ? "NULL" : "'$id_public_space'";
$tahun_sql      = ($tahun_perolehan === "") ? "NULL" : "'$tahun_perolehan'";

$query = mysqli_query($conn, "
    INSERT INTO inventaris
    (
        kode_inventaris,
        id_kategori,
        id_ruangan,
        id_public_space,
        nama_barang,
        merk,
        spesifikasi,
        jumlah,
        kondisi,
        tahun_perolehan,
        sumber_perolehan,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$kode_inventaris',
        '$id_kategori',
        $id_ruangan_sql,
        $id_public_sql,
        '$nama_barang',
        '$merk',
        '$spesifikasi',
        '$jumlah',
        '$kondisi',
        $tahun_sql,
        '$sumber_perolehan',
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

    $_SESSION['success'] = "Data Inventaris berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data Inventaris gagal ditambahkan.";

    header("Location: tambah.php");
    exit;

}