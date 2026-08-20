<?php

declare(strict_types=1);

session_start();

require_once "../../../config/database.php";
require_once "service.php";
require_once "config.php";
require_once "helper.php";

$menu = $module;

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";

/*
|--------------------------------------------------------------------------
| Validasi Parameter
|--------------------------------------------------------------------------
*/

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {

    $_SESSION['error'] = uploadError(
        $config,
        'required'
    );

    header("Location: ../{$module}/index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Parent Module
|--------------------------------------------------------------------------
*/

switch ($module) {

    case 'lokasi':
        $parentTable      = 'lokasi';
        $parentPrimaryKey = 'id_lokasi';
        $parentNameField  = 'nama_lokasi';
        break;

    case 'ruangan':
        $parentTable      = 'ruangan';
        $parentPrimaryKey = 'id_ruangan';
        $parentNameField  = 'nama_ruangan';
        break;

    case 'public_space':
        $parentTable      = 'public_space';
        $parentPrimaryKey = 'id_public_space';
        $parentNameField  = 'nama_public_space';
        break;

    default:
        exit('Module tidak dikenali.');
}

/*
|--------------------------------------------------------------------------
| Data Parent
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT *
    FROM {$parentTable}
    WHERE {$parentPrimaryKey} = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$parent = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$parent) {

    $_SESSION['error'] = 'Data tidak ditemukan.';

    header("Location: ../{$module}/index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Gallery
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT *
    FROM {$table}
    WHERE {$foreignKey} = ?
    ORDER BY
        is_cover DESC,
        urutan ASC,
        created_at ASC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$result = $stmt->get_result();

$photos = [];

while ($row = $result->fetch_assoc()) {
    $photos[] = $row;
}

$stmt->close();

/*
|--------------------------------------------------------------------------
| Information
|--------------------------------------------------------------------------
*/

$totalPhoto = count($photos);

$maxPhoto = $config['upload']['max_photo'];

$canUpload = $totalPhoto < $maxPhoto;

$uploadPath = "../../../assets/uploads/{$uploadFolder}/";
?>

    <main class="app-main">

    <!-- Header -->
    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h2 class="fw-bold mb-1">
                        Gallery Foto <?= htmlspecialchars($moduleName); ?>
                    </h2>

                    <span class="text-muted">
                        <?= htmlspecialchars($parent[$parentNameField]); ?>
                    </span>

                </div>

                <div>

                    <a
                        href="../<?= $module; ?>/index.php"
                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali

                    </a>

                </div>

            </div>

        </div>

    </div>


    <div class="app-content">

        <div class="container-fluid">


            <!-- Upload Card -->

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            <i class="bi bi-images me-2 text-warning"></i>

                            Gallery Foto

                        </h5>

                        <span class="badge bg-warning text-dark fs-6">

                            <?= $totalPhoto; ?> / <?= $maxPhoto; ?> Foto

                        </span>

                    </div>

                </div>


            <div class="card-body">

            <form
                action="upload.php?tipe=<?= $module ?>&id=<?= $id ?>"
                method="POST"
                enctype="multipart/form-data">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $id; ?>">

                <div class="input-group">

                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                        <?= !$canUpload ? 'disabled' : ''; ?>
                        required>

                    <button
                        type="submit"
                        class="btn btn-warning px-4"
                        <?= !$canUpload ? 'disabled' : ''; ?>>

                        <i class="bi bi-upload me-2"></i>
                        Upload Foto

                    </button>

                </div>

                <div class="form-text mt-2">

                    Maksimal <?= $maxPhoto; ?> foto • Maksimal ukuran 10 MB

                </div>

            </form>
            </div>

            <!-- Gallery -->

            <div class="row">

    <?php foreach ($photos as $photo) : ?>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="position-relative">

                <img
                    src="<?= $uploadPath . htmlspecialchars($photo['nama_file']); ?>"
                    class="card-img-top"
                    alt="Gallery Photo"
                    style="height:220px;object-fit:cover;">

                <?php if ((int)$photo['is_cover'] === 1) : ?>

                    <span
                        class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 px-3 py-2">

                        <i class="bi bi-star-fill me-1"></i>
                        Cover

                    </span>

                <?php endif; ?>

            </div>


            <div class="card-body">

                <h6
                    class="fw-semibold text-truncate mb-2"
                    title="<?= htmlspecialchars($photo['nama_file']); ?>">

                    <?= htmlspecialchars($photo['nama_file']); ?>

                </h6>

                <div class="text-muted small">

                    <i class="bi bi-sort-numeric-down me-1"></i>

                    Urutan :
                    <?= (int)$photo['urutan']; ?>

                </div>

            </div>


            <div class="card-footer bg-white border-0">

                <div class="d-grid gap-2">

                    <?php if ((int)$photo['is_cover'] === 0) : ?>

                        <a
                            href="cover.php?tipe=<?= $module ?>&id=<?= $id ?>&foto=<?= $photo[$primaryKey]; ?>"
                            class="btn btn-warning btn-sm btn-cover">

                            <i class="bi bi-star-fill me-1"></i>

                            Jadikan Cover

                        </a>

                    <?php else : ?>

                        <button
                            class="btn btn-success btn-sm"
                            disabled>

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Cover Aktif

                        </button>

                    <?php endif; ?>


                    <?php if ((int)$photo['is_cover'] === 0) : ?>

                        <a
                            href="hapus.php?tipe=<?= $module ?>&id=<?= $id ?>&foto=<?= $photo[$primaryKey]; ?>"
                            class="btn btn-outline-danger btn-sm btn-delete">

                            <i class="bi bi-trash me-1"></i>

                            Hapus

                        </a>

                    <?php else : ?>

                        <button
                            class="btn btn-outline-secondary btn-sm"
                            disabled>

                            <i class="bi bi-lock-fill me-1"></i>

                            Tidak Bisa Dihapus

                        </button>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>
    <?php endforeach; ?>

    <?php if (empty($photos)) : ?>

    <div class="col-12">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <div
                    class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center mb-4"
                    style="width:110px;height:110px;">

                    <i
                        class="bi bi-images"
                        style="font-size:48px;color:#ffc107;">
                    </i>

                </div>

                <h4 class="fw-bold mb-2">

                    Belum Ada Foto

                </h4>

                <p class="text-muted mb-4">

                    Upload foto pertama untuk
                    <strong><?= htmlspecialchars($parent[$parentNameField]); ?></strong>

                </p>

                <?php if ($canUpload) : ?>

                    <label
                        for="foto"
                        class="btn btn-warning px-4">

                        <i class="bi bi-upload me-2"></i>

                        Upload Sekarang

                    </label>

                <?php endif; ?>

            </div>

        </div>

    </div>

    <?php endif; ?>

        </div>

    </div>

</main>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>
<?php if (isset($_SESSION['success'])) : ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: <?= json_encode($_SESSION['success']); ?>,
    confirmButtonColor: '#f59e0b'
});
</script>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>


<?php if (isset($_SESSION['error'])) : ?>

<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: <?= json_encode($_SESSION['error']); ?>,
    confirmButtonColor: '#dc3545'
});
</script>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Cover
    document.querySelectorAll('.btn-cover').forEach(button => {

        button.addEventListener('click', function (e) {

            e.preventDefault();

            Swal.fire({
                title: 'Jadikan Cover?',
                text: 'Foto ini akan menjadi cover utama.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Jadikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if(result.isConfirmed){
                    window.location.href = this.href;
                }

            });

        });

    });


    // Delete
    document.querySelectorAll('.btn-delete').forEach(button => {

        button.addEventListener('click', function (e) {

            e.preventDefault();

            Swal.fire({
                title: 'Hapus Foto?',
                text: 'Foto yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if(result.isConfirmed){
                    window.location.href = this.href;
                }

            });

        });

    });

});
</script>
      

