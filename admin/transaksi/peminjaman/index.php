<?php
session_start();

$menu = "peminjaman";

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

// Filter status peminjaman
$status = $_GET['status'] ?? '';
$where = "";

if ($status == "Menunggu") {
    $where = "WHERE p.status = 'Menunggu'";
} elseif ($status == "Dipinjam") {
    $where = "WHERE p.status = 'Dipinjam'";
} elseif ($status == "Selesai") {
    $where = "WHERE p.status = 'Selesai'";
} elseif ($status == "Ditolak") {
    $where = "WHERE p.status = 'Ditolak'";
} elseif ($status == "Menunggu Pengembalian") {
    $where = "WHERE p.status = 'Menunggu Pengembalian'";
} elseif ($status == "Terlambat") {
    $where = "
        WHERE
            p.status = 'Dipinjam'
        AND
            p.tanggal_kembali < CURDATE()
    ";
}

// Mengambil data peminjaman
$query = mysqli_query($conn, "
    SELECT
        p.*
    FROM peminjaman p
    $where
    ORDER BY p.created_at DESC
");

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Data Peminjaman
                </h2>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
                    </li>

                    <li class="breadcrumb-item">Transaksi</li>

                    <li class="breadcrumb-item active">
                        Peminjaman
                    </li>

                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <!-- Tabel Peminjaman -->
        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-arrow-left-right me-2"></i>
                        Daftar Peminjaman
                    </h5>

                    <a href="tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Peminjaman
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle datatable">

                        <thead class="table-secondary">

                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Peminjaman</th>
                                <th>Nama Peminjam</th>
                                <th>NIM / NIP</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th width="18%" class="text-center">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($query)) :
                            ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td>
                                        <strong><?= htmlspecialchars($row['kode_peminjaman']); ?></strong>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_peminjam']); ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($row['email']); ?>
                                        </small>
                                    </td>

                                    <td><?= htmlspecialchars($row['nim_nip']); ?></td>

                                    <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?></td>

                                    <td><?= date('d-m-Y', strtotime($row['tanggal_kembali'])); ?></td>

                                    <td>

                                        <?php
                                        switch ($row['status']) {

                                            case "Menunggu":
                                                echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                                break;

                                            case "Dipinjam":
                                                echo '<span class="badge bg-primary">Dipinjam</span>';
                                                break;

                                            case "Menunggu Pengembalian":
                                                echo '<span class="badge bg-info">Menunggu Pengembalian</span>';
                                                break;

                                            case "Selesai":
                                                echo '<span class="badge bg-success">Selesai</span>';
                                                break;

                                            case "Ditolak":
                                                echo '<span class="badge bg-danger">Ditolak</span>';
                                                break;

                                            default:
                                                echo '<span class="badge bg-secondary">'
                                                    . htmlspecialchars($row['status']) .
                                                    '</span>';
                                                break;
                                        }

                                        if (
                                            $row['status'] == "Dipinjam" &&
                                            strtotime($row['tanggal_kembali']) < strtotime(date('Y-m-d'))
                                        ) {

                                            $hariTerlambat = floor(
                                                (time() - strtotime($row['tanggal_kembali'])) / 86400
                                            );

                                            echo '<br>';
                                            echo '<span class="badge bg-danger mt-1">
                                                    Terlambat ' . $hariTerlambat . ' Hari
                                                  </span>';
                                        }
                                        ?>

                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="detail.php?id=<?= $row['id_peminjaman']; ?>"
                                            class="btn btn-info btn-sm me-1"
                                            title="Detail">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <?php if ($row['status'] == 'Menunggu') : ?>

                                            <a
                                                href="edit.php?id=<?= $row['id_peminjaman']; ?>"
                                                class="btn btn-warning btn-sm me-1"
                                                title="Edit">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>

                                            <a
                                                href="#"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus"
                                                onclick="hapusPeminjaman(<?= $row['id_peminjaman']; ?>)">

                                                <i class="bi bi-trash"></i>

                                            </a>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</main>

<script>
function hapusPeminjaman(id) {

    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data peminjaman yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = "hapus.php?id=" + id;
        }

    });

}
</script>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>