<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

$id_public_space    = (int) $_POST['id_public_space'];
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

    header("Location: edit.php?id=$id_public_space");
    exit;
}

// ==============================
// Validasi Format Kode
// ==============================

if (!preg_match('/^PSP\d{3}$/', $kode_public_space)) {

    $_SESSION['error'] = "Kode Public Space harus berformat PSP001.";

    header("Location: edit.php?id=$id_public_space");
    exit;
}

// ==============================
// Validasi Luas
// ==============================

if ($luas <= 0) {

    $_SESSION['error'] = "Luas Public Space harus lebih dari 0.";

    header("Location: edit.php?id=$id_public_space");
    exit;
}

// ==============================
// Cek Kode
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_public_space
    FROM public_space
    WHERE kode_public_space = '$kode_public_space'
    AND id_public_space != '$id_public_space'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode Public Space sudah digunakan.";

    header("Location: edit.php?id=$id_public_space");
    exit;
}

// ==============================
// Cek Nama
// ==============================

$cekNama = mysqli_query($conn, "
    SELECT id_public_space
    FROM public_space
    WHERE id_lantai = '$id_lantai'
    AND nama_public_space = '$nama_public_space'
    AND id_public_space != '$id_public_space'
");

if (mysqli_num_rows($cekNama) > 0) {

    $_SESSION['error'] = "Nama Public Space sudah ada pada lantai tersebut.";

    header("Location: edit.php?id=$id_public_space");
    exit;
}

// ==============================
// Update Database
// ==============================

$query = mysqli_query($conn, "
    UPDATE public_space
    SET
        id_lantai = '$id_lantai',
        kode_public_space = '$kode_public_space',
        nama_public_space = '$nama_public_space',
        luas = '$luas',
        deskripsi = '$deskripsi',
        status = '$status',
        updated_at = NOW()
    WHERE id_public_space = '$id_public_space'
");

// ==============================
// Hasil
// ==============================

if ($query) {

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
            'Mengubah Public Space',
            'public_space',
            '$id_public_space'
        )
    ");

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data Public Space berhasil diperbarui.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data Public Space gagal diperbarui.";

    header("Location: edit.php?id=$id_public_space");
    exit;
}