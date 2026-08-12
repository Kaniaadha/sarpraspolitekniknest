<?php
session_start();

require_once '../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$email   = trim($_POST['email'] ?? '');
$nim_nip = trim($_POST['nim_nip'] ?? '');

// ==========================================
// VALIDASI INPUT
// ==========================================
if ($email === '' || $nim_nip === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Email dan NIM/NIP wajib diisi.'
    ]);
    exit;
}

// ==========================================
// CARI DATA PEMINJAMAN
// ==========================================
$sql = "
    SELECT
        p.id_peminjaman,
        p.kode_peminjaman,
        p.nama_peminjam,
        p.email,
        p.nim_nip,
        p.tanggal_pinjam,
        p.tanggal_kembali,
        p.tanggal_pengembalian,
        p.tujuan_peminjaman,
        p.status,
        dp.jumlah,
        dp.kondisi_sebelum,
        dp.kondisi_sesudah,
        dp.catatan,
        i.kode_inventaris,
        i.nama_barang
    FROM peminjaman p
    INNER JOIN detail_peminjaman dp
        ON p.id_peminjaman = dp.id_peminjaman
    INNER JOIN inventaris i
        ON dp.id_inventaris = i.id_inventaris
    WHERE p.email = ?
      AND p.nim_nip = ?
    ORDER BY p.created_at DESC
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada sistem.'
    ]);
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $email,
    $nim_nip
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// ==========================================
// DATA TIDAK DITEMUKAN
// ==========================================
if (mysqli_num_rows($result) === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Data peminjaman tidak ditemukan. Pastikan email dan NIM/NIP sesuai dengan data saat pengajuan.'
    ]);
    exit;
}

// ==========================================
// AMBIL SEMUA DATA
// ==========================================
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// ==========================================
// PROSES STATUS KETERLAMBATAN
// ==========================================

$hariIni = date('Y-m-d');
foreach ($data as &$row) {
    $row['keterangan_keterlambatan'] = '';
    $row['terlambat'] = false;

    // Belum dikembalikan dan sudah melewati
    // tanggal kembali
    if (
        empty($row['tanggal_pengembalian']) &&
        $hariIni > $row['tanggal_kembali']
    ) {
        $row['terlambat'] = true;
        $row['keterangan_keterlambatan'] =
            'Terlambat - barang belum dikembalikan';
    }

    // Sudah dikembalikan tetapi melewati
    // tanggal kembali
    elseif (
        !empty($row['tanggal_pengembalian']) &&
        $row['tanggal_pengembalian'] > $row['tanggal_kembali']
    ) {
        $row['terlambat'] = true;
        $row['keterangan_keterlambatan'] =
            'Terlambat - barang sudah dikembalikan';
    }
}
unset($row);

// ==========================================
// KIRIM DATA
// ==========================================

echo json_encode([
    'success' => true,
    'data' => $data
]);

exit;