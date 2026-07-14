<?php
session_start();

$menu = "lokasi";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";
require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";

$query = mysqli_query($conn, "
    SELECT *
    FROM lokasi
    ORDER BY id_lokasi DESC
");
?>

<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="mb-0 fw-bold">
                    Data Lokasi
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Lokasi</li>
                </ol>

            </div>

        </div>
    </div>

    <!-- Content -->
    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        Daftar Lokasi
                    </h5>

                    <a href="tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Lokasi
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle datatable">

                        <thead class="table-secondary">

                            <tr>

                                <th width="5%" class="text-center">
                                    No
                                </th>

                                <th width="15%">
                                    Kode Lokasi
                                </th>

                                <th>
                                    Nama Lokasi
                                </th>

                                <th>
                                    Alamat
                                </th>

                                <th width="12%">
                                    Status
                                </th>

                                <th width="15%" class="text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($query)) :
                            ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['kode_lokasi']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_lokasi']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['alamat']); ?>
                                    </td>

                                    <td>

                                        <?php if ($row['status'] == "Aktif") : ?>

                                            <span class="badge rounded-pill bg-success">
                                                Aktif
                                            </span>

                                        <?php else : ?>

                                            <span class="badge rounded-pill bg-danger">
                                                Tidak Aktif
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="edit.php?id=<?= $row['id_lokasi']; ?>"
                                            class="btn btn-warning btn-sm me-1"
                                            title="Edit">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a
                                            href="#"
                                            class="btn btn-danger btn-sm"
                                            title="Hapus"
                                            onclick="hapusLokasi(<?= $row['id_lokasi']; ?>)">

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

function hapusLokasi(id){

    Swal.fire({

        title: 'Hapus Data?',

        text: 'Data lokasi yang dihapus tidak dapat dikembalikan.',

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