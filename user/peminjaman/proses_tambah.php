<?php
session_start();

require_once '../../config/database.php';

// ==============================
// Ambil Data
// ==============================

$nama_peminjam     = trim($_POST['nama_peminjam'] ?? '');
$nim_nip           = trim($_POST['nim_nip'] ?? '');
$no_hp             = trim($_POST['no_hp'] ?? '');
$email             = trim($_POST['email'] ?? '');
$tanggal_pinjam    = $_POST['tanggal_pinjam'] ?? '';
$tanggal_kembali   = $_POST['tanggal_kembali'] ?? '';
$tujuan_peminjaman = trim($_POST['tujuan_peminjaman'] ?? '');

$id_inventaris     = (int) ($_POST['id_inventaris'] ?? 0);
$jumlah            = (int) ($_POST['jumlah'] ?? 0);
$kondisi_sebelum   = trim($_POST['kondisi_sebelum'] ?? '');
$catatan           = trim($_POST['catatan'] ?? '');

$status = "Menunggu";

// ==============================
// Simpan Input Sementara
// ==============================

$_SESSION['old'] = $_POST;

// ==============================
// Validasi Field Wajib
// ==============================

if (
    empty($nama_peminjam) ||
    empty($nim_nip) ||
    empty($tanggal_pinjam) ||
    empty($tanggal_kembali) ||
    empty($tujuan_peminjaman) ||
    $id_inventaris <= 0 ||
    $jumlah <= 0 ||
    empty($kondisi_sebelum)
) {
    $_SESSION['error'] = "Semua data wajib diisi.";
    header("Location: tambah.php?id_inventaris=$id_inventaris");
    exit;
}

// ==============================
// Validasi Tanggal
// ==============================

$hariIni = date('Y-m-d');

if ($tanggal_pinjam < $hariIni) {
    $_SESSION['error'] = "Tanggal pinjam tidak boleh sebelum hari ini.";
    header("Location: tambah.php?id_inventaris=$id_inventaris");
    exit;
}

if ($tanggal_kembali < $hariIni) {
    $_SESSION['error'] = "Tanggal kembali tidak boleh sebelum hari ini.";
    header("Location: tambah.php?id_inventaris=$id_inventaris");
    exit;
}

if ($tanggal_kembali < $tanggal_pinjam) {
    $_SESSION['error'] = "Tanggal kembali tidak boleh lebih awal dari tanggal pinjam.";
    header("Location: tambah.php?id_inventaris=$id_inventaris");
    exit;
}

// ==============================
// Cek Data Inventaris
// ==============================

$queryInventaris = mysqli_query($conn, "
    SELECT
        i.id_inventaris,
        i.jumlah,
        (
            i.jumlah -
            COALESCE((
                SELECT SUM(dp.jumlah)
                FROM detail_peminjaman dp
                INNER JOIN peminjaman p
                    ON dp.id_peminjaman = p.id_peminjaman
                WHERE dp.id_inventaris = i.id_inventaris
                AND p.status = 'Dipinjam'
            ), 0)
        ) AS stok_tersedia
    FROM inventaris i
    WHERE i.id_inventaris = '$id_inventaris'
    AND i.status = 'Aktif'
    LIMIT 1
");

if (!$queryInventaris || mysqli_num_rows($queryInventaris) == 0) {
    $_SESSION['error'] = "Data barang tidak ditemukan.";
    header("Location: tambah.php?id_inventaris=$id_inventaris");
    exit;
}

$inventaris = mysqli_fetch_assoc($queryInventaris);

// ==============================
// Validasi Stok
// ==============================

if ($jumlah > (int) $inventaris['stok_tersedia']) {
    $_SESSION['error'] = "Jumlah barang melebihi stok yang tersedia.";
    header("Location: tambah.php?id_inventaris=$id_inventaris");
    exit;
}

// ==============================
// Buat Kode Peminjaman
// ==============================

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
        $nomor = (int) $angka + 1;
    }
}

$kode_peminjaman = "PJM" . str_pad($nomor, 4, "0", STR_PAD_LEFT);

// ==============================
// Simpan Data
// ==============================

mysqli_begin_transaction($conn);

try {
    $nama_peminjam = mysqli_real_escape_string($conn, $nama_peminjam);
    $nim_nip = mysqli_real_escape_string($conn, $nim_nip);
    $no_hp = mysqli_real_escape_string($conn, $no_hp);
    $email = mysqli_real_escape_string($conn, $email);
    $tujuan_peminjaman = mysqli_real_escape_string($conn, $tujuan_peminjaman);
    $kondisi_sebelum = mysqli_real_escape_string($conn, $kondisi_sebelum);
    $catatan = mysqli_real_escape_string($conn, $catatan);

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
            NULL,
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

    // ==============================
    // Simpan Detail Peminjaman
    // ==============================

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
            '$id_inventaris',
            '$jumlah',
            '$kondisi_sebelum',
            '$catatan'
        )
    ");

    if (!$queryDetail) {
        throw new Exception("Gagal menyimpan detail peminjaman.");
    }

    mysqli_commit($conn);

    // ==============================
    // Hapus Input Sementara
    // ==============================

    unset($_SESSION['old']);

    $_SESSION['user_success'] = "Pengajuan peminjaman berhasil dikirim.";

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['user_error'] = $e->getMessage();

    header("Location: tambah.php?id_inventaris=$id_inventaris");
    exit;
}
?>