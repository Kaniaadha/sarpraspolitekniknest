<?php
session_start();

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

$menu = "stock_opname";

require_once "../../../config/database.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: riwayat.php");
    exit;
}

$id_stock_opname = mysqli_real_escape_string($conn, $_GET['id']);

$queryStockOpname = mysqli_query($conn, "
    SELECT
        so.*,
        a.nama_admin
    FROM stock_opname so
    JOIN admin a
        ON so.id_admin = a.id_admin
    WHERE so.id_stock_opname = '$id_stock_opname'
");

$stockOpname = mysqli_fetch_assoc($queryStockOpname);

if (!$stockOpname) {
    header("Location: riwayat.php");
    exit;
}

$queryDetail = mysqli_query($conn, "
    SELECT
        dso.*,
        i.kode_inventaris,
        i.nama_barang
    FROM detail_stock_opname dso
    JOIN inventaris i
        ON dso.id_inventaris = i.id_inventaris
    WHERE dso.id_stock_opname = '$id_stock_opname'
    ORDER BY i.nama_barang ASC
");

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Detail Stock Opname</h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="riwayat.php">Riwayat Stock Opname</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Detail Stock Opname
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <!-- Informasi Stock Opname -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Informasi Stock Opname</h3>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th width="180">Kode Stock Opname</th>
                                    <td width="20">:</td>
                                    <td><?= htmlspecialchars($stockOpname['kode_stock_opname']); ?></td>
                                </tr>
                                <tr>
                                    <th>Petugas</th>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($stockOpname['nama_admin']); ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th width="180">Tanggal</th>
                                    <td width="20">:</td>
                                    <td><?= date('d F Y', strtotime($stockOpname['tanggal'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>:</td>
                                    <td>
                                        <?php if ($stockOpname['status'] == 'Draft') : ?>
                                            <span class="badge bg-warning">Draft</span>
                                        <?php else : ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Detail Stock Opname -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Detail Stock Opname</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th width="10%">Kode</th>
                                    <th>Nama Barang</th>
                                    <th width="10%">Stok Sistem</th>
                                    <th width="10%">Stok Fisik</th>
                                    <th width="10%">Selisih</th>
                                    <th width="12%">Kondisi</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($queryDetail)) :
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>

                                        <td class="text-center">
                                            <?= htmlspecialchars($row['kode_inventaris']); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_barang']); ?>
                                        </td>

                                        <td class="text-center">
                                            <?= $row['stok_sistem']; ?>
                                        </td>

                                        <td class="text-center">
                                            <?= $row['stok_fisik']; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($row['selisih'] > 0) : ?>
                                                <span class="badge bg-success">
                                                    +<?= $row['selisih']; ?>
                                                </span>
                                            <?php elseif ($row['selisih'] < 0) : ?>
                                                <span class="badge bg-danger">
                                                    <?= $row['selisih']; ?>
                                                </span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">
                                                    0
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?= htmlspecialchars($row['kondisi']); ?>
                                        </td>

                                        <td>
                                            <?= !empty($row['catatan'])
                                                ? htmlspecialchars($row['catatan'])
                                                : '-'; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">

                        <a href="riwayat.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <?php if ($stockOpname['status'] == 'Draft') : ?>
                            <a
                                href="proses_selesai.php?id=<?= $id_stock_opname; ?>"
                                class="btn btn-success"
                                id="btnSelesaikanStockOpname">
                                <i class="bi bi-check-circle me-1"></i>
                                Selesaikan Stock Opname
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const btnSelesaikan = document.getElementById('btnSelesaikanStockOpname');

if (btnSelesaikan) {
    btnSelesaikan.addEventListener('click', function (e) {
        e.preventDefault();

        const url = this.href;

        Swal.fire({
            icon: 'warning',
            title: 'Selesaikan Stock Opname?',
            text: 'Setelah diselesaikan, stok inventaris akan diperbarui sesuai hasil Stock Opname.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
}
</script>

<?php if (isset($_SESSION['success'])) : ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: <?= json_encode($_SESSION['success']); ?>,
    confirmButtonText: 'OK',
    confirmButtonColor: '#0d6efd'
});
</script>
<?php unset($_SESSION['success']); endif; ?>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>