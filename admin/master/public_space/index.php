<?php
session_start();

$menu = "public_space";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

$query = mysqli_query($conn, "
    SELECT
        public_space.*,
        lantai.nama_lantai,
        lantai.nomor_lantai,
        lokasi.nama_lokasi
    FROM public_space
    INNER JOIN lantai
        ON public_space.id_lantai = lantai.id_lantai
    INNER JOIN lokasi
        ON lantai.id_lokasi = lokasi.id_lokasi
    ORDER BY
        lokasi.nama_lokasi ASC,
        lantai.nomor_lantai ASC,
        public_space.nama_public_space ASC
");

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";
?>

<main class="app-main">

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Data Public Space
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Public Space</li>
                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>
                        Daftar Public Space
                    </h5>

                    <a href="tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Public Space
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle datatable">

                        <thead class="table-secondary">

                            <tr>

                                <th width="5%">No</th>
                                <th>Lokasi</th>
                                <th>Lantai</th>
                                <th>Kode Public Space</th>
                                <th>Nama Public Space</th>
                                <th>Luas</th>
                                <th>Status</th>
                                <th width="15%" class="text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php $no = 1; ?>

                            <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_lokasi']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_lantai']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['kode_public_space']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_public_space']); ?>
                                    </td>

                                    <td>
                                        <?= number_format($row['luas'], 2); ?> m²
                                    </td>

                                    <td>

                                        <?php if ($row['status'] == "Aktif") : ?>

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
                                        <a
                                            href="../foto/index.php?tipe=public_space&id=<?= $row['id_public_space']; ?>"
                                            class="btn btn-info btn-sm me-1"
                                            title="Gallery Foto">

                                            <i class="bi bi-images"></i>
                                        </a>
                                        <a
                                            href="edit.php?id=<?= $row['id_public_space']; ?>"
                                            class="btn btn-warning btn-sm me-1">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a
                                            href="#"
                                            class="btn btn-danger btn-sm"
                                            onclick="hapusPublicSpace(<?= $row['id_public_space']; ?>)">

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

<script>

function hapusPublicSpace(id){

    Swal.fire({

        title: 'Hapus Data?',

        text: 'Data Public Space yang dihapus tidak dapat dikembalikan.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc3545',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Hapus!',

        cancelButtonText: 'Batal'

    }).then((result)=>{

        if(result.isConfirmed){

            window.location.href = "hapus.php?id=" + id;

        }

    });

}

</script>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>