<?php
session_start();

$menu = "admin";

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
    FROM admin
    ORDER BY id_admin DESC
");
?>

<main class="app-main">

<?php
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>


    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">
                    Data Admin
                </h2>

                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Master</li>
                    <li class="breadcrumb-item active">Admin</li>
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
                        <i class="bi bi-people-fill me-2"></i>
                        Daftar Admin
                    </h5>

                    <a href="tambah.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Tambah Admin
                    </a>

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 datatable">

                        <thead class="table-secondary">

                            <tr>

                                <th width="5%" class="text-center">No</th>
                                <th>Nama Admin</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th class="text-center">Status</th>
                                <th width="120" class="text-center">Aksi</th>

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

                                    <td><?= htmlspecialchars($row['nama_admin']); ?></td>

                                    <td><?= htmlspecialchars($row['username']); ?></td>

                                    <td><?= htmlspecialchars($row['email']); ?></td>

                                    <td><?= htmlspecialchars($row['no_hp']); ?></td>

                                    <td class="text-center">

                                        <?php if ($row['status'] == "Aktif") : ?>

                                            <span class="badge rounded-pill bg-success px-3 py-2">
                                                Aktif
                                            </span>

                                        <?php else : ?>

                                            <span class="badge rounded-pill bg-danger px-3 py-2">
                                                Tidak Aktif
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <a href="edit.php?id=<?= $row['id_admin']; ?>"
                                            class="btn btn-warning btn-sm me-1"
                                            title="Edit">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        <a href="#"
                                        class="btn btn-danger btn-sm"
                                        title="Hapus"
                                        onclick="hapusAdmin(<?= $row['id_admin']; ?>)">

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
function hapusAdmin(id) {

    Swal.fire({

        title: 'Hapus Data?',
        text: "Data admin yang dihapus tidak dapat dikembalikan.",
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