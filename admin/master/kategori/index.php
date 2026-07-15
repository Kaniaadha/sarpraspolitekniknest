<?php
session_start();

$menu = "kategori";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

$query = mysqli_query($conn, "
    SELECT *
    FROM kategori
    ORDER BY nama_kategori ASC
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
                    Data Kategori
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Kategori</li>
                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-tags me-2"></i>
                        Daftar Kategori
                    </h5>

                    <a href="tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Kategori
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle datatable">

                        <thead class="table-secondary">

                            <tr>

                                <th width="5%">No</th>
                                <th>Kode Kategori</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th width="15%" class="text-center">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php $no = 1; ?>

                            <?php while($row = mysqli_fetch_assoc($query)) : ?>

                                <tr>

                                    <td><?= $no++; ?></td>

                                    <td><?= htmlspecialchars($row['kode_kategori']); ?></td>

                                    <td><?= htmlspecialchars($row['nama_kategori']); ?></td>

                                    <td>
                                        <?= !empty($row['deskripsi']) ? htmlspecialchars($row['deskripsi']) : '-'; ?>
                                    </td>

                                    <td>

                                        <?php if($row['status']=="Aktif") : ?>

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
                                            href="edit.php?id=<?= $row['id_kategori']; ?>"
                                            class="btn btn-warning btn-sm me-1">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a
                                            href="#"
                                            class="btn btn-danger btn-sm"
                                            onclick="hapusKategori(<?= $row['id_kategori']; ?>)">

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

function hapusKategori(id){

    Swal.fire({

        title: 'Hapus Data?',

        text: 'Data kategori yang dihapus tidak dapat dikembalikan.',

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