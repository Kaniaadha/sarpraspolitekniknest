<?php
session_start();

$menu = "peminjaman";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {

    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$id_peminjaman = (int) $_GET['id'];

$queryPeminjaman = mysqli_query($conn, "
    SELECT *
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

$queryDetail = mysqli_query($conn, "
    SELECT
        dp.*,
        i.kode_inventaris,
        i.nama_barang
    FROM detail_peminjaman dp
    INNER JOIN inventaris i
        ON dp.id_inventaris = i.id_inventaris
    WHERE dp.id_peminjaman = '$id_peminjaman'
    ORDER BY dp.id_detail ASC
");

switch ($peminjaman['status']) {

    case "Menunggu":
        $badge = '<span class="badge bg-warning text-dark">Menunggu</span>';
        break;

    case "Dipinjam":
        $badge = '<span class="badge bg-primary">Dipinjam</span>';
        break;
    
    case "Menunggu Pengembalian":
        $badge = '<span class="badge bg-info">Menunggu Pengembalian</span>';
        break;

    case "Selesai":
        $badge = '<span class="badge bg-success">Selesai</span>';
        break;

    case "Ditolak":
        $badge = '<span class="badge bg-danger">Ditolak</span>';
        break;

    default:
        $badge = '<span class="badge bg-secondary">'
                . htmlspecialchars($peminjaman['status']) .
                '</span>';
        break;
}

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Detail Peminjaman
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item">Peminjaman</li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <!-- Informasi Peminjaman -->
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="bi bi-person-vcard me-2"></i>
                    Informasi Peminjaman
                </h5>

                <?= $badge; ?>

            </div>

            <div class="card-body">

                <div class="row">

                    <!-- Data Peminjam -->
                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>
                                <th width="35%">Kode Peminjaman</th>
                                <td>
                                    <strong>
                                        <?= htmlspecialchars($peminjaman['kode_peminjaman']); ?>
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th>Nama Peminjam</th>
                                <td><?= htmlspecialchars($peminjaman['nama_peminjam']); ?></td>
                            </tr>

                            <tr>
                                <th>NIM / NIP</th>
                                <td><?= htmlspecialchars($peminjaman['nim_nip']); ?></td>
                            </tr>

                            <tr>
                                <th>No. HP</th>
                                <td><?= htmlspecialchars($peminjaman['no_hp']); ?></td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td><?= htmlspecialchars($peminjaman['email']); ?></td>
                            </tr>

                        </table>

                    </div>

                    <!-- Informasi Peminjaman -->
                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>
                                <th width="35%">Tanggal Pinjam</th>
                                <td>
                                    <?= date('d-m-Y', strtotime($peminjaman['tanggal_pinjam'])); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Tanggal Kembali</th>
                                <td>
                                    <?= date('d-m-Y', strtotime($peminjaman['tanggal_kembali'])); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Tujuan Peminjaman</th>
                                <td>
                                    <?= nl2br(htmlspecialchars($peminjaman['tujuan_peminjaman'])); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Catatan Admin</th>
                                <td>
                                    <?= !empty($peminjaman['catatan_admin'])
                                        ? nl2br(htmlspecialchars($peminjaman['catatan_admin']))
                                        : '-'; ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Dibuat</th>
                                <td>
                                    <?= date('d-m-Y H:i', strtotime($peminjaman['created_at'])); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Terakhir Diubah</th>
                                <td>
                                    <?= date('d-m-Y H:i', strtotime($peminjaman['updated_at'])); ?>
                                </td>
                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>

                <!-- Data Barang -->
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="bi bi-box-seam me-2"></i>

                    Data Barang yang Dipinjam

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light text-center">

                            <tr>

                                <th width="5%">No</th>

                                <th width="15%">Kode Barang</th>

                                <th>Nama Barang</th>

                                <th width="10%">Jumlah</th>

                                <th width="15%">Kondisi Sebelum</th>

                                <th width="15%">Kondisi Sesudah</th>

                                <th width="20%">Catatan</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (mysqli_num_rows($queryDetail) > 0) : ?>

                                <?php
                                $no = 1;

                                while ($detail = mysqli_fetch_assoc($queryDetail)) :
                                ?>

                                    <tr>

                                        <td class="text-center">

                                            <?= $no++; ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($detail['kode_inventaris']); ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($detail['nama_barang']); ?>

                                        </td>

                                        <td class="text-center">

                                            <?= $detail['jumlah']; ?>

                                        </td>

                                        <td class="text-center">

                                            <?= htmlspecialchars($detail['kondisi_sebelum']); ?>

                                        </td>

                                        <td class="text-center">

                                            <?= !empty($detail['kondisi_sesudah'])
                                                ? htmlspecialchars($detail['kondisi_sesudah'])
                                                : '-'; ?>

                                        </td>

                                        <td>

                                            <?= !empty($detail['catatan'])
                                                ? nl2br(htmlspecialchars($detail['catatan']))
                                                : '-'; ?>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else : ?>

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-4">

                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>

                                        Tidak ada data barang yang dipinjam.

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

                <!-- Aksi -->
        <div class="card border-0 shadow-sm">

            <div class="card-body d-flex justify-content-between align-items-center">

                <!-- Tombol Kembali -->
                <a href="index.php" class="btn btn-secondary">

                    <i class="bi bi-arrow-left-circle me-1"></i>

                    Kembali

                </a>

                <!-- Tombol Aksi -->
                <div>

                    <?php if ($peminjaman['status'] == 'Menunggu') : ?>

                        <a href="edit.php?id=<?= $peminjaman['id_peminjaman']; ?>"
                           class="btn btn-warning">

                            <i class="bi bi-pencil-square me-1"></i>

                            Edit

                        </a>

                        <a href="proses_setujui.php?id=<?= $peminjaman['id_peminjaman']; ?>"
                           class="btn btn-success"
                           onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')">

                            <i class="bi bi-check-circle me-1"></i>

                            Setujui

                        </a>

                        <a href="proses_tolak.php?id=<?= $peminjaman['id_peminjaman']; ?>"
                           class="btn btn-danger"
                           onclick="return confirm('Apakah Anda yakin ingin menolak peminjaman ini?')">

                            <i class="bi bi-x-circle me-1"></i>

                            Tolak

                        </a>

                    <?php elseif ($peminjaman['status'] == 'Dipinjam') : ?>

                        <button class="btn btn-secondary" disabled>
                            <i class="bi bi-hourglass-split me-1"></i>
                            Menunggu Pengajuan Pengembalian
                        </button>

                    <?php elseif ($peminjaman['status'] == 'Menunggu Pengembalian') : ?>

                        <a href="proses_selesai.php?id=<?= $peminjaman['id_peminjaman']; ?>"
                        class="btn btn-success"
                        onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pengembalian barang ini?')">

                            <i class="bi bi-check-circle me-1"></i>
                            Konfirmasi Pengembalian

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</main>

<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>