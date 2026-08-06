<?php
session_start();

$menu = "banner";

// Cek login admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../login.php");
    exit;
}

require_once "../../../config/database.php";

// Mengambil data banner
$query = mysqli_query($conn, "
    SELECT
        b.*,
        (
            SELECT COUNT(*)
            FROM foto_banner fb
            WHERE fb.id_banner = b.id_banner
        ) AS total_foto,
        (
            SELECT nama_file
            FROM foto_banner fb
            WHERE fb.id_banner = b.id_banner
            AND fb.is_cover = 1
            LIMIT 1
        ) AS cover
    FROM banner b
    ORDER BY b.created_at DESC
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
                    Banner Website
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active">Banner</li>
                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-image-fill me-2"></i>
                        Daftar Banner
                    </h5>

                    <a href="tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Banner
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle datatable">

                        <thead class="table-secondary">

                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Cover</th>
                                <th>Judul Banner</th>
                                <th width="12%">Status</th>
                                <th width="12%">Jumlah Slide</th>
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

                                    <td class="text-center">

                                        <?php if (!empty($row['cover'])) : ?>

                                            <img
                                                src="../../../assets/uploads/banner/<?= htmlspecialchars($row['cover']); ?>"
                                                class="img-thumbnail"
                                                style="width:90px; height:60px; object-fit:cover; cursor:pointer;"
                                                onclick="previewBanner(this.src)">

                                        <?php else : ?>

                                            <div
                                                class="border rounded d-flex align-items-center justify-content-center mx-auto"
                                                style="width:90px; height:60px; font-size:12px; color:#999;">

                                                Belum Ada

                                            </div>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <strong>
                                            <?= htmlspecialchars($row['judul']); ?>
                                        </strong>

                                        <?php if (!empty($row['subjudul'])) : ?>

                                            <br>

                                            <small class="text-muted">
                                                <?= htmlspecialchars($row['subjudul']); ?>
                                            </small>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <?php if ($row['status'] == "aktif") : ?>

                                            <span class="badge bg-success rounded-pill">
                                                Aktif
                                            </span>

                                        <?php else : ?>

                                            <span class="badge bg-danger rounded-pill">
                                                Nonaktif
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <?php if ($row['total_foto'] > 0) : ?>

                                            <span class="badge bg-primary rounded-pill">
                                                <?= $row['total_foto']; ?> Slide
                                            </span>

                                        <?php else : ?>

                                            <span class="badge bg-secondary rounded-pill">
                                                Belum Ada
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="edit.php?id=<?= $row['id_banner']; ?>"
                                            class="btn btn-warning btn-sm me-1"
                                            title="Edit Banner">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a
                                            href="foto/index.php?id=<?= $row['id_banner']; ?>"
                                            class="btn btn-info btn-sm me-1"
                                            title="Kelola Foto">

                                            <i class="bi bi-images"></i>

                                        </a>

                                        <a
                                            href="#"
                                            class="btn btn-danger btn-sm"
                                            title="Hapus Banner"
                                            onclick="hapusBanner(<?= $row['id_banner']; ?>)">

                                            <i class="bi bi-trash"></i>

                                        </a>

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

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>

<?php if (isset($_SESSION['berhasil'])) : ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "<?= $_SESSION['berhasil']; ?>",
        timer: 2000,
        showConfirmButton: false
    });

});
</script>

<?php
unset($_SESSION['berhasil']);
endif;
?>

<?php if (isset($_SESSION['gagal'])) : ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({
        icon: "error",
        title: "Gagal",
        text: "<?= $_SESSION['gagal']; ?>",
        confirmButtonColor: "#0d6efd"
    });

});
</script>

<?php
unset($_SESSION['gagal']);
endif;
?>

<script>

// Preview gambar banner
function previewBanner(src) {

    Swal.fire({
        imageUrl: src,
        imageAlt: 'Preview Banner',
        showConfirmButton: false,
        showCloseButton: true,
        width: 900
    });

}

// Konfirmasi hapus banner
function hapusBanner(id) {

    Swal.fire({
        title: 'Hapus Banner?',
        text: 'Data banner yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = "hapus.php?id=" + id;
        }

    });

}
</script>