<?php
session_start();

if (!isset($_SESSION['id_admin'])) {

    header("Location: ../../../login.php");
    exit;

}

require_once "../../../config/database.php";

$id_admin = $_SESSION['id_admin'];

$nama_peminjam     = trim($_POST['nama_peminjam']);
$nim_nip           = trim($_POST['nim_nip']);
$no_hp             = trim($_POST['no_hp']);
$email             = trim($_POST['email']);
$tanggal_pinjam    = $_POST['tanggal_pinjam'];
$tanggal_kembali   = $_POST['tanggal_kembali'];
$tujuan_peminjaman = trim($_POST['tujuan_peminjaman']);

$id_inventaris     = $_POST['id_inventaris'] ?? [];
$jumlah            = $_POST['jumlah'] ?? [];
$kondisi_sebelum   = $_POST['kondisi_sebelum'] ?? [];
$catatan           = $_POST['catatan'] ?? [];

$status = "Menunggu";

$_SESSION['old'] = $_POST;


if (

    empty($nama_peminjam) ||
    empty($nim_nip) ||
    empty($tanggal_pinjam) ||
    empty($tanggal_kembali) ||
    empty($tujuan_peminjaman)

){

    $_SESSION['error'] = "Semua data wajib diisi.";

    header("Location: tambah.php");
    exit;

}

if (count($id_inventaris) == 0) {

    $_SESSION['error'] = "Minimal pilih satu barang.";

    header("Location: tambah.php");
    exit;

}

$hariIni = date('Y-m-d');

if ($tanggal_pinjam < $hariIni) {

    $_SESSION['error'] =
        "Tanggal pinjam tidak boleh sebelum hari ini.";

    header("Location: tambah.php");
    exit;

}

if ($tanggal_kembali < $hariIni) {

    $_SESSION['error'] =
        "Tanggal kembali tidak boleh sebelum hari ini.";

    header("Location: tambah.php");
    exit;

}

if ($tanggal_kembali < $tanggal_pinjam) {

    $_SESSION['error'] = "Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.";

    header("Location: tambah.php");
    exit;

}


$barangDipilih = [];

for ($i = 0; $i < count($id_inventaris); $i++) {

    $idBarang = (int)$id_inventaris[$i];
    $qty      = (int)$jumlah[$i];

    if ($idBarang <= 0) {

        $_SESSION['error'] = "Barang belum dipilih.";

        header("Location: tambah.php");
        exit;

    }

    if ($qty <= 0) {

        $_SESSION['error'] = "Jumlah barang tidak valid.";

        header("Location: tambah.php");
        exit;

    }

    if (in_array($idBarang, $barangDipilih)) {

        $_SESSION['error'] = "Barang yang sama tidak boleh dipilih lebih dari satu kali.";

        header("Location: tambah.php");
        exit;

    }

    $barangDipilih[] = $idBarang;

}

for ($i = 0; $i < count($id_inventaris); $i++) {

    $idBarang = (int)$id_inventaris[$i];

    $cek = mysqli_query($conn, "
        SELECT id_inventaris
        FROM inventaris
        WHERE id_inventaris = '$idBarang'
    ");

    if (!$cek || mysqli_num_rows($cek) == 0) {

        $_SESSION['error'] = "Data barang tidak ditemukan.";

        header("Location: tambah.php");
        exit;

    }

}

$queryKode = mysqli_query($conn, "
    SELECT kode_peminjaman
    FROM peminjaman
    ORDER BY id_peminjaman DESC
    LIMIT 1
");

$nomor = 1;

if ($queryKode && mysqli_num_rows($queryKode) > 0) {

    $dataKode = mysqli_fetch_assoc($queryKode);

    $angka = substr($dataKode['kode_peminjaman'], 3);

    if (is_numeric($angka)) {

        $nomor = (int)$angka + 1;

    }

}

$kode_peminjaman = "PJM" . str_pad($nomor, 4, "0", STR_PAD_LEFT);


mysqli_begin_transaction($conn);

try {

    $nama_peminjam = mysqli_real_escape_string($conn, $nama_peminjam);
    $nim_nip = mysqli_real_escape_string($conn, $nim_nip);
    $no_hp = mysqli_real_escape_string($conn, $no_hp);
    $email = mysqli_real_escape_string($conn, $email);
    $tujuan_peminjaman = mysqli_real_escape_string($conn, $tujuan_peminjaman);

    $queryPeminjaman = mysqli_query($conn, "
        INSERT INTO peminjaman
        (
            kode_peminjaman,
            id_admin,
            nama_peminjam,
            nim_nip,
            no_hp,
            email,
            tanggal_pinjam,
            tanggal_kembali,
            tujuan_peminjaman,
            status
        )
        VALUES
        (
            '$kode_peminjaman',
            '$id_admin',
            '$nama_peminjam',
            '$nim_nip',
            '$no_hp',
            '$email',
            '$tanggal_pinjam',
            '$tanggal_kembali',
            '$tujuan_peminjaman',
            '$status'
        )
    ");

    if (!$queryPeminjaman) {

        throw new Exception("Gagal menyimpan data peminjaman.");

    }

    $id_peminjaman = mysqli_insert_id($conn);

    for ($i = 0; $i < count($id_inventaris); $i++) {

        $idBarang = (int)$id_inventaris[$i];
        $qty = (int)$jumlah[$i];

        $kondisi = mysqli_real_escape_string(
            $conn,
            $kondisi_sebelum[$i]
        );

        $catatanBarang = mysqli_real_escape_string(
            $conn,
            trim($catatan[$i])
        );

        $queryDetail = mysqli_query($conn, "
            INSERT INTO detail_peminjaman
            (
                id_peminjaman,
                id_inventaris,
                jumlah,
                kondisi_sebelum,
                catatan
            )
            VALUES
            (
                '$id_peminjaman',
                '$idBarang',
                '$qty',
                '$kondisi',
                '$catatanBarang'
            )
        ");

        if (!$queryDetail) {

            throw new Exception("Gagal menyimpan detail peminjaman.");
        }

    }

    mysqli_commit($conn);

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data peminjaman berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

    header("Location: tambah.php");
    exit;

}

?>