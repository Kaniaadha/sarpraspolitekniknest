<?php
session_start();

if (!isset($_SESSION['id_admin'])) {

    header("Location: ../../../login.php");
    exit;

}

require_once "../../../config/database.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: index.php");
    exit;

}


$id_peminjaman      = (int) $_POST['id_peminjaman'];

$nama_peminjam      = trim($_POST['nama_peminjam']);
$nim_nip            = trim($_POST['nim_nip']);
$no_hp              = trim($_POST['no_hp']);
$email              = trim($_POST['email']);

$tanggal_pinjam     = $_POST['tanggal_pinjam'];
$tanggal_kembali    = $_POST['tanggal_kembali'];

$tujuan_peminjaman  = trim($_POST['tujuan_peminjaman']);

$id_inventaris      = $_POST['id_inventaris'] ?? [];
$jumlah             = $_POST['jumlah'] ?? [];
$kondisi_sebelum    = $_POST['kondisi_sebelum'] ?? [];
$catatan            = $_POST['catatan'] ?? [];


if (

    empty($id_peminjaman) ||

    empty($nama_peminjam) ||

    empty($nim_nip) ||

    empty($tanggal_pinjam) ||

    empty($tanggal_kembali)

) {

    $_SESSION['error'] = "Data belum lengkap.";

    header("Location: edit.php?id=".$id_peminjaman);
    exit;

}


if ($tanggal_kembali < $tanggal_pinjam) {

    $_SESSION['error'] = "Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.";

    header("Location: edit.php?id=".$id_peminjaman);
    exit;

}


if (

    count($id_inventaris) == 0 ||

    count($id_inventaris) != count($jumlah) ||

    count($jumlah) != count($kondisi_sebelum)

) {

    $_SESSION['error'] = "Data barang tidak valid.";

    header("Location: edit.php?id=".$id_peminjaman);
    exit;

}

if (count($id_inventaris) !== count(array_unique($id_inventaris))) {

    $_SESSION['error'] = "Barang yang sama tidak boleh dipilih lebih dari satu kali.";

    header("Location: edit.php?id=" . $id_peminjaman);
    exit;

}

foreach ($jumlah as $item) {

    if ((int)$item <= 0) {

        $_SESSION['error'] = "Jumlah barang tidak valid.";

        header("Location: edit.php?id=" . $id_peminjaman);
        exit;

    }

}

$queryPeminjaman = mysqli_query($conn, "
    SELECT status
    FROM peminjaman
    WHERE id_peminjaman = '$id_peminjaman'
    LIMIT 1
");

if (!$queryPeminjaman || mysqli_num_rows($queryPeminjaman) == 0) {

    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";

    header("Location: index.php");
    exit;

}

$peminjaman = mysqli_fetch_assoc($queryPeminjaman);

if ($peminjaman['status'] != 'Menunggu') {

    $_SESSION['error'] = "Hanya transaksi dengan status Menunggu yang dapat diedit.";

    header("Location: index.php");
    exit;

}

mysqli_begin_transaction($conn);

try {
    $deleteDetail = mysqli_query($conn, "
        DELETE FROM detail_peminjaman
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    if (!$deleteDetail) {

        throw new Exception("Gagal menghapus detail peminjaman.");

    }

    $nama_peminjam = mysqli_real_escape_string($conn, $nama_peminjam);
    $nim_nip = mysqli_real_escape_string($conn, $nim_nip);
    $no_hp = mysqli_real_escape_string($conn, $no_hp);
    $email = mysqli_real_escape_string($conn, $email);
    $tujuan_peminjaman = mysqli_real_escape_string($conn, $tujuan_peminjaman);


    $updatePeminjaman = mysqli_query($conn, "
        UPDATE peminjaman
        SET
            nama_peminjam = '$nama_peminjam',
            nim_nip = '$nim_nip',
            no_hp = '$no_hp',
            email = '$email',
            tanggal_pinjam = '$tanggal_pinjam',
            tanggal_kembali = '$tanggal_kembali',
            tujuan_peminjaman = '$tujuan_peminjaman',
            updated_at = NOW()
        WHERE id_peminjaman = '$id_peminjaman'
    ");

    if (!$updatePeminjaman) {

        throw new Exception("Gagal memperbarui data peminjaman.");

    }

    for ($i = 0; $i < count($id_inventaris); $i++) {

        $idInventaris = (int) $id_inventaris[$i];
        $jumlahPinjam = (int) $jumlah[$i];

        $kondisi = mysqli_real_escape_string(
            $conn,
            $kondisi_sebelum[$i]
        );

        $catatanBarang = mysqli_real_escape_string(
            $conn,
            $catatan[$i] ?? ''
        );

        $queryInventaris = mysqli_query($conn, "
            SELECT id_inventaris
            FROM inventaris
            WHERE id_inventaris = '$idInventaris'
            LIMIT 1
        ");

        if (
            !$queryInventaris ||
            mysqli_num_rows($queryInventaris) == 0
        ) {

            throw new Exception("Data inventaris tidak ditemukan.");

        }

        $insertDetail = mysqli_query($conn, "
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
                '$idInventaris',
                '$jumlahPinjam',
                '$kondisi',
                '$catatanBarang'
            )
        ");

        if (!$insertDetail) {

            throw new Exception(
                "Gagal menyimpan detail peminjaman."
            );

        }

    }

    mysqli_commit($conn);

    $_SESSION['success'] = "Data peminjaman berhasil diperbarui.";

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

    header("Location: edit.php?id=" . $id_peminjaman);
    exit;

}