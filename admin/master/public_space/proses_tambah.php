<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

$id_lantai          = (int) $_POST['id_lantai'];
$kode_public_space  = trim($_POST['kode_public_space']);
$nama_public_space  = trim($_POST['nama_public_space']);
$luas               = trim($_POST['luas']);
$deskripsi          = trim($_POST['deskripsi']);
$status             = $_POST['status'];

// ==============================
// Simpan Input
// ==============================

$_SESSION['old'] = [
    'id_lantai'          => $id_lantai,
    'kode_public_space'  => $kode_public_space,
    'nama_public_space'  => $nama_public_space,
    'luas'               => $luas,
    'deskripsi'          => $deskripsi,
    'status'             => $status
];

// ==============================
// Validasi Field
// ==============================

if (
    empty($id_lantai) ||
    empty($kode_public_space) ||
    empty($nama_public_space) ||
    $_POST['luas'] === "" ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi kecuali deskripsi.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Format Kode
// ==============================

if (!preg_match('/^PSP\d{3}$/', $kode_public_space)) {

    $_SESSION['error'] = "Kode Public Space harus berformat PSP001.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Luas
// ==============================

if ($luas <= 0) {

    $_SESSION['error'] = "Luas Public Space harus lebih dari 0.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Kode
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_public_space
    FROM public_space
    WHERE kode_public_space = '$kode_public_space'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode Public Space sudah digunakan.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Cek Nama
// Unik per lantai
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_public_space
    FROM public_space
    WHERE id_lantai = '$id_lantai'
    AND nama_public_space = '$nama_public_space'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama Public Space sudah ada pada lantai tersebut.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Simpan Database
// ==============================

$query = mysqli_query($conn, "
    INSERT INTO public_space
    (
        id_lantai,
        kode_public_space,
        nama_public_space,
        luas,
        deskripsi,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$id_lantai',
        '$kode_public_space',
        '$nama_public_space',
        '$luas',
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

    $idPublicSpace = mysqli_insert_id($conn);

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
            'Menambah Public Space',
            'public_space',
            '$idPublicSpace'
        )
    ");

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data Public Space berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data Public Space gagal ditambahkan.";

    header("Location: tambah.php");
    exit;
}