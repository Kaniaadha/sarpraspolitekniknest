<?php
session_start();
require_once "../foto/helper.php";
require_once "../foto/config.php";
require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";

// ==============================
// Ambil Data
// ==============================

$id_inventaris    = (int) $_POST['id_inventaris'];

$kode_inventaris  = trim($_POST['kode_inventaris']);
$id_kategori      = (int) $_POST['id_kategori'];
$nama_barang      = trim($_POST['nama_barang']);
$merk             = trim($_POST['merk']);
$spesifikasi      = trim($_POST['spesifikasi']);

$jenis_penempatan = $_POST['jenis_penempatan'];

$id_ruangan = !empty($_POST['id_ruangan'])
    ? (int) $_POST['id_ruangan']
    : NULL;

$id_public_space = !empty($_POST['id_public_space'])
    ? (int) $_POST['id_public_space']
    : NULL;

$jumlah           = (int) $_POST['jumlah'];
$kondisi          = $_POST['kondisi'];
$tahun_perolehan  = trim($_POST['tahun_perolehan']);
$sumber_perolehan = trim($_POST['sumber_perolehan']);
$status           = $_POST['status'];

$currentYear = date('Y');
/*
|--------------------------------------------------------------------------
| Upload
|--------------------------------------------------------------------------
*/

$uploadFolder = "../../../assets/uploads/inventaris/";

ensureUploadDirectory($uploadFolder);

$fotoLama = $_POST['foto_lama'] ?? '';
$fotoBaru = $fotoLama;
// ==============================
// Simpan Old Input
// ==============================

$_SESSION['old'] = $_POST;

// ==============================
// Validasi Field
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

    header("Location: edit.php?id=$id_inventaris");
    exit;
}

// ==============================
// Validasi Format Kode
// ==============================

if (!preg_match('/^\.NBK\..+$/', $kode_inventaris)) {

    $_SESSION['error'] = "Kode Inventaris harus diawali dengan .NBK.";

    header("Location: edit.php?id=$id_inventaris");
    exit;
}

// ==============================
// Validasi Jumlah
// ==============================

if ($jumlah <= 0) {

    $_SESSION['error'] = "Jumlah harus lebih dari 0.";

    header("Location: edit.php?id=$id_inventaris");
    exit;
}

// ==============================
// Validasi Tahun
// ==============================

if (!empty($tahun_perolehan)) {

    if (
        $tahun_perolehan < 1900 ||
        $tahun_perolehan > $currentYear
    ) {

        $_SESSION['error'] = "Tahun Perolehan tidak valid.";

        header("Location: edit.php?id=$id_inventaris");
        exit;
    }
}

// ==============================
// Validasi Penempatan
// ==============================

if ($jenis_penempatan == "ruangan") {

    if (empty($id_ruangan)) {

        $_SESSION['error'] = "Silakan pilih Ruangan.";

        header("Location: edit.php?id=$id_inventaris");
        exit;
    }

    $id_public_space = NULL;

} elseif ($jenis_penempatan == "public") {

    if (empty($id_public_space)) {

        $_SESSION['error'] = "Silakan pilih Public Space.";

        header("Location: edit.php?id=$id_inventaris");
        exit;
    }

    $id_ruangan = NULL;

} else {

    $_SESSION['error'] = "Jenis Penempatan tidak valid.";

    header("Location: edit.php?id=$id_inventaris");
    exit;
}

// ==============================
// Validasi Kode Unik
// ==============================

$cekKode = mysqli_query($conn, "
    SELECT id_inventaris
    FROM inventaris
    WHERE kode_inventaris = '$kode_inventaris'
    AND id_inventaris != '$id_inventaris'
");

if (mysqli_num_rows($cekKode) > 0) {

    $_SESSION['error'] = "Kode Inventaris sudah digunakan.";

    header("Location: edit.php?id=$id_inventaris");
    exit;
}
/*
|--------------------------------------------------------------------------
| Upload Foto Baru
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
) {

    $validation = validateImage($_FILES['foto'], $config);

    if ($validation !== true) {

        $_SESSION['error'] = $validation;

        header("Location: edit.php?id=$id_inventaris");
        exit;

    }

    $extension = pathinfo(
        $_FILES['foto']['name'],
        PATHINFO_EXTENSION
    );

    $fotoBaru = generateUniqueFileName($extension);

    $destination = $uploadFolder . $fotoBaru;

    if (
        !move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            $destination
        )
    ) {

        $_SESSION['error'] = "Upload foto gagal.";

        header("Location: edit.php?id=$id_inventaris");
        exit;

    }

}
// ==============================
// Format NULL
// ==============================

$id_ruangan_sql = is_null($id_ruangan)
    ? "NULL"
    : "'$id_ruangan'";

$id_public_sql = is_null($id_public_space)
    ? "NULL"
    : "'$id_public_space'";

$tahun_sql = ($tahun_perolehan === "")
    ? "NULL"
    : "'$tahun_perolehan'";

// ==============================
// Update Database
// ==============================

$query = mysqli_query($conn, "
    UPDATE inventaris
    SET
        kode_inventaris = '$kode_inventaris',
        id_kategori = '$id_kategori',
        id_ruangan = $id_ruangan_sql,
        id_public_space = $id_public_sql,
        nama_barang = '$nama_barang',
        merk = '$merk',
        spesifikasi = '$spesifikasi',
        jumlah = '$jumlah',
        kondisi = '$kondisi',
        tahun_perolehan = $tahun_sql,
        sumber_perolehan = '$sumber_perolehan',
        status = '$status',
        foto = " . ($fotoBaru ? "'$fotoBaru'" : "NULL") . ",
        updated_at = NOW()
    WHERE id_inventaris = '$id_inventaris'
");

// ==============================
// Hasil
// ==============================

if ($query) {
if (
    $fotoBaru !== $fotoLama &&
    !empty($fotoLama)
) {

    deletePhysicalFile(
        $uploadFolder . $fotoLama
    );

}
    
    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengubah Inventaris",
        "inventaris",
        $id_inventaris
    );

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data Inventaris berhasil diperbarui.";

    header("Location: index.php");
    exit;

} else {
if (
    isset($destination) &&
    file_exists($destination)
) {

    deletePhysicalFile($destination);

}
    $_SESSION['error'] = "Data Inventaris gagal diperbarui.";

    header("Location: edit.php?id=$id_inventaris");
    exit;
}