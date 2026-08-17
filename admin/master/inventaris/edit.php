<?php

session_start();

$menu = "inventaris";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


// =====================================================
// AMBIL ID
// =====================================================

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($id <= 0) {

    $_SESSION['error'] =
        "Data inventaris tidak ditemukan.";

    header("Location: index.php");
    exit;
}


// =====================================================
// DATA INVENTARIS
// =====================================================

$queryInventaris = mysqli_query(
    $conn,
    "
    SELECT *
    FROM inventaris
    WHERE id_inventaris = '$id'
    LIMIT 1
    "
);

if (
    !$queryInventaris ||
    mysqli_num_rows($queryInventaris) !== 1
) {

    $_SESSION['error'] =
        "Data inventaris tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($queryInventaris);


// =====================================================
// DATA KATEGORI
// =====================================================

$kategori = mysqli_query(
    $conn,
    "
    SELECT *
    FROM kategori
    WHERE status = 'Aktif'
    ORDER BY nama_kategori ASC
    "
);


// =====================================================
// DATA RUANGAN
// =====================================================

$ruangan = mysqli_query(
    $conn,
    "
    SELECT
        r.id_ruangan,
        r.nama_ruangan,
        l.nama_lantai,
        lok.nama_lokasi

    FROM ruangan r

    INNER JOIN lantai l
        ON r.id_lantai = l.id_lantai

    INNER JOIN lokasi lok
        ON l.id_lokasi = lok.id_lokasi

    WHERE r.status = 'Aktif'

    ORDER BY
        lok.nama_lokasi,
        l.nomor_lantai,
        r.nama_ruangan
    "
);


// =====================================================
// DATA PUBLIC SPACE
// =====================================================

$public = mysqli_query(
    $conn,
    "
    SELECT
        ps.id_public_space,
        ps.nama_public_space,
        l.nama_lantai,
        lok.nama_lokasi

    FROM public_space ps

    INNER JOIN lantai l
        ON ps.id_lantai = l.id_lantai

    INNER JOIN lokasi lok
        ON l.id_lokasi = lok.id_lokasi

    WHERE ps.status = 'Aktif'

    ORDER BY
        lok.nama_lokasi,
        l.nomor_lantai,
        ps.nama_public_space
    "
);


// =====================================================
// TENTUKAN JENIS PENEMPATAN
// =====================================================

$jenis_penempatan = '';

if (!empty($data['id_ruangan'])) {

    $jenis_penempatan = 'ruangan';

} elseif (!empty($data['id_public_space'])) {

    $jenis_penempatan = 'public';

}


// =====================================================
// HEADER
// =====================================================

require_once "../../../includes/header.php";
require_once "../../../includes/navbar.php";
require_once "../../../includes/sidebar.php";

?>

<main class="app-main">


    <!-- =================================================
         HEADER
    ================================================== -->

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <h2 class="fw-bold mb-0">

                    Edit Inventaris

                </h2>


                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        Dashboard
                    </li>

                    <li class="breadcrumb-item">
                        Master
                    </li>

                    <li class="breadcrumb-item">
                        Inventaris
                    </li>

                    <li class="breadcrumb-item active">
                        Edit
                    </li>

                </ol>

            </div>

        </div>

    </div>


    <!-- =================================================
         CONTENT
    ================================================== -->

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">


            <!-- CARD HEADER -->

            <div class="card-header py-3">

                <h5 class="mb-0">

                    <i class="bi bi-pencil-square me-2"></i>

                    Form Edit Inventaris

                </h5>

            </div>


            <!-- CARD BODY -->

            <div class="card-body">


                <form
                    action="proses_edit.php"
                    method="POST"
                    enctype="multipart/form-data">


                    <!-- =================================================
                         HIDDEN ID
                    ================================================== -->

                    <input
                        type="hidden"
                        name="id_inventaris"
                        value="<?= (int) $data['id_inventaris']; ?>">


                    <!-- =================================================
                         DATA UTAMA
                    ================================================== -->

                    <div class="row">


                        <!-- KODE INVENTARIS -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Kode Inventaris

                            </label>


                            <input
                                type="text"
                                name="kode_inventaris"
                                class="form-control"
                                value="<?= htmlspecialchars($data['kode_inventaris']); ?>"
                                placeholder=".NBK.xx.xx.xx.xxxx"
                                required>


                            <div class="form-text">

                                Kode harus diawali dengan
                                <strong>.NBK.</strong>

                            </div>

                        </div>


                        <!-- KATEGORI -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Kategori

                            </label>


                            <select
                                name="id_kategori"
                                class="form-select"
                                required>

                                <option value="">

                                    -- Pilih Kategori --

                                </option>


                                <?php while (
                                    $k = mysqli_fetch_assoc($kategori)
                                ): ?>

                                    <option
                                        value="<?= $k['id_kategori']; ?>"
                                        <?= (
                                            $data['id_kategori']
                                            == $k['id_kategori']
                                        )
                                            ? 'selected'
                                            : '';
                                        ?>>

                                        <?= htmlspecialchars(
                                            $k['nama_kategori']
                                        ); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <!-- NAMA BARANG -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Nama Barang

                            </label>


                            <input
                                type="text"
                                name="nama_barang"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $data['nama_barang']
                                ); ?>"
                                required>

                        </div>


                        <!-- MERK -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Merk

                            </label>


                            <input
                                type="text"
                                name="merk"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $data['merk']
                                ); ?>">

                        </div>


                        <!-- SPESIFIKASI -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Spesifikasi

                            </label>


                            <textarea
                                name="spesifikasi"
                                rows="2"
                                class="form-control"><?= htmlspecialchars(
                                    $data['spesifikasi']
                                ); ?></textarea>

                        </div>

                    </div>


                    <hr class="my-4">


                    <!-- =================================================
                         PENEMPATAN BARANG
                    ================================================== -->

                    <h5 class="mb-3">

                        Penempatan Barang

                    </h5>


                    <div class="row">


                        <!-- JENIS PENEMPATAN -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Jenis Penempatan

                            </label>


                            <select
                                id="jenis_penempatan"
                                name="jenis_penempatan"
                                class="form-select"
                                required>


                                <option value="">

                                    -- Pilih --

                                </option>


                                <option
                                    value="ruangan"
                                    <?= (
                                        $jenis_penempatan
                                        == 'ruangan'
                                    )
                                        ? 'selected'
                                        : '';
                                    ?>>

                                    Ruangan

                                </option>


                                <option
                                    value="public"
                                    <?= (
                                        $jenis_penempatan
                                        == 'public'
                                    )
                                        ? 'selected'
                                        : '';
                                    ?>>

                                    Public Space

                                </option>


                            </select>

                        </div>


                        <!-- RUANGAN -->

                        <div
                            class="col-md-8 mb-3"
                            id="box_ruangan"
                            style="<?= (
                                $jenis_penempatan == 'ruangan'
                            )
                                ? 'display:block;'
                                : 'display:none;';
                            ?>">


                            <label class="form-label">

                                Ruangan

                            </label>


                            <select
                                name="id_ruangan"
                                class="form-select">


                                <option value="">

                                    -- Pilih Ruangan --

                                </option>


                                <?php while (
                                    $r = mysqli_fetch_assoc($ruangan)
                                ): ?>

                                    <option
                                        value="<?= $r['id_ruangan']; ?>"
                                        <?= (
                                            $data['id_ruangan']
                                            == $r['id_ruangan']
                                        )
                                            ? 'selected'
                                            : '';
                                        ?>>

                                        <?= htmlspecialchars(
                                            $r['nama_lokasi']
                                        ); ?>

                                        -

                                        <?= htmlspecialchars(
                                            $r['nama_lantai']
                                        ); ?>

                                        -

                                        <?= htmlspecialchars(
                                            $r['nama_ruangan']
                                        ); ?>

                                    </option>

                                <?php endwhile; ?>


                            </select>

                        </div>


                        <!-- PUBLIC SPACE -->

                        <div
                            class="col-md-8 mb-3"
                            id="box_public"
                            style="<?= (
                                $jenis_penempatan == 'public'
                            )
                                ? 'display:block;'
                                : 'display:none;';
                            ?>">


                            <label class="form-label">

                                Public Space

                            </label>


                            <select
                                name="id_public_space"
                                class="form-select">


                                <option value="">

                                    -- Pilih Public Space --

                                </option>


                                <?php while (
                                    $p = mysqli_fetch_assoc($public)
                                ): ?>

                                    <option
                                        value="<?= $p['id_public_space']; ?>"
                                        <?= (
                                            $data['id_public_space']
                                            == $p['id_public_space']
                                        )
                                            ? 'selected'
                                            : '';
                                        ?>>

                                        <?= htmlspecialchars(
                                            $p['nama_lokasi']
                                        ); ?>

                                        -

                                        <?= htmlspecialchars(
                                            $p['nama_lantai']
                                        ); ?>

                                        -

                                        <?= htmlspecialchars(
                                            $p['nama_public_space']
                                        ); ?>

                                    </option>

                                <?php endwhile; ?>


                            </select>

                        </div>

                    </div>


                    <hr class="my-4">


                    <!-- =================================================
                         FOTO
                    ================================================== -->

                    <h5 class="mb-3">

                        Foto Inventaris

                    </h5>


                    <div class="row">


                        <div class="col-md-12 mb-3">

                            <label class="form-label">

                                Foto Barang

                            </label>


                            <?php if (
                                !empty($data['foto'])
                            ): ?>

                                <div class="mb-3">

                                    <img
                                        src="../../../assets/uploads/inventaris/<?= htmlspecialchars(
                                            $data['foto']
                                        ); ?>"
                                        alt="Foto Inventaris"
                                        style="
                                            width:120px;
                                            height:120px;
                                            object-fit:cover;
                                            border-radius:10px;
                                        ">

                                </div>

                            <?php endif; ?>


                            <input
                                type="file"
                                name="foto"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp">


                            <div class="form-text">

                                Kosongkan jika tidak ingin
                                mengganti foto.

                                Format:
                                JPG, JPEG, PNG, WEBP
                                • Maksimal 5 MB

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         INFORMASI INVENTARIS
                    ================================================== -->

                    <h5 class="mb-3">

                        Informasi Inventaris

                    </h5>


                    <div class="row">


                        <!-- JUMLAH -->

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Jumlah

                            </label>


                            <input
                                type="number"
                                name="jumlah"
                                class="form-control"
                                min="1"
                                value="<?= htmlspecialchars(
                                    $data['jumlah']
                                ); ?>"
                                required>

                        </div>


                        <!-- HARGA -->

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Harga

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">

                                    Rp

                                </span>


                                <input
                                    type="number"
                                    name="harga"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="<?= htmlspecialchars(
                                        $data['harga'] ?? ''
                                    ); ?>"
                                    placeholder="0">

                            </div>


                            <div class="form-text">

                                Harga per unit

                            </div>

                        </div>


                        <!-- KONDISI -->

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Kondisi

                            </label>


                            <select
                                name="kondisi"
                                class="form-select"
                                required>


                                <option value="">

                                    -- Pilih Kondisi --

                                </option>


                                <option
                                    value="Baik"
                                    <?= (
                                        $data['kondisi']
                                        == 'Baik'
                                    )
                                        ? 'selected'
                                        : '';
                                    ?>>

                                    Baik

                                </option>


                                <option
                                    value="Rusak Ringan"
                                    <?= (
                                        $data['kondisi']
                                        == 'Rusak Ringan'
                                    )
                                        ? 'selected'
                                        : '';
                                    ?>>

                                    Rusak Ringan

                                </option>


                                <option
                                    value="Rusak Berat"
                                    <?= (
                                        $data['kondisi']
                                        == 'Rusak Berat'
                                    )
                                        ? 'selected'
                                        : '';
                                    ?>>

                                    Rusak Berat

                                </option>


                            </select>

                        </div>


                        <!-- TAHUN PEROLEHAN -->

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Tahun Perolehan

                            </label>


                            <input
                                type="number"
                                name="tahun_perolehan"
                                class="form-control"
                                min="1900"
                                max="<?= date('Y'); ?>"
                                value="<?= htmlspecialchars(
                                    $data['tahun_perolehan'] ?? ''
                                ); ?>">

                        </div>


                        <!-- SUMBER PEROLEHAN -->

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Sumber Perolehan

                            </label>


                            <input
                                type="text"
                                name="sumber_perolehan"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $data['sumber_perolehan'] ?? ''
                                ); ?>">

                        </div>


                        <!-- STATUS -->

                        <div class="col-md-9 mb-3">

                            <label class="form-label">

                                Status

                            </label>


                            <select
                                name="status"
                                class="form-select"
                                required>


                                <option value="">

                                    -- Pilih Status --

                                </option>


                                <option
                                    value="Aktif"
                                    <?= (
                                        $data['status']
                                        == 'Aktif'
                                    )
                                        ? 'selected'
                                        : '';
                                    ?>>

                                    Aktif

                                </option>


                                <option
                                    value="Nonaktif"
                                    <?= (
                                        $data['status']
                                        == 'Nonaktif'
                                    )
                                        ? 'selected'
                                        : '';
                                    ?>>

                                    Nonaktif

                                </option>


                            </select>

                        </div>


                    </div>


                    <hr>


                    <!-- =================================================
                         BUTTON
                    ================================================== -->

                    <a
                        href="index.php"
                        class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-save"></i>

                        Simpan Perubahan

                    </button>


                </form>

            </div>

        </div>

    </div>

</main>


<!-- =================================================
     SCRIPT PENEMPATAN
================================================== -->

<script>

function togglePenempatan() {

    const jenis =
        document.getElementById(
            "jenis_penempatan"
        ).value;


    const boxRuangan =
        document.getElementById(
            "box_ruangan"
        );


    const boxPublic =
        document.getElementById(
            "box_public"
        );


    const selectRuangan =
        document.querySelector(
            "select[name='id_ruangan']"
        );


    const selectPublic =
        document.querySelector(
            "select[name='id_public_space']"
        );


    if (jenis === "ruangan") {

        boxRuangan.style.display =
            "block";

        boxPublic.style.display =
            "none";


        selectRuangan.required =
            true;

        selectPublic.required =
            false;


        selectPublic.value = "";

    }


    else if (jenis === "public") {

        boxRuangan.style.display =
            "none";

        boxPublic.style.display =
            "block";


        selectRuangan.required =
            false;

        selectPublic.required =
            true;


        selectRuangan.value = "";

    }


    else {

        boxRuangan.style.display =
            "none";

        boxPublic.style.display =
            "none";


        selectRuangan.required =
            false;

        selectPublic.required =
            false;

    }

}


document.addEventListener(
    "DOMContentLoaded",
    function () {

        document
            .getElementById(
                "jenis_penempatan"
            )
            .addEventListener(
                "change",
                togglePenempatan
            );


        togglePenempatan();

    }
);

</script>


<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>