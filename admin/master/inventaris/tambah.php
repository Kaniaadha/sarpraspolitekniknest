<?php

session_start();

$menu = "inventaris";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

$old = $_SESSION['old'] ?? [];

// ======================
// Data Kategori
// ======================

$kategori = mysqli_query($conn,"
    SELECT *
    FROM kategori
    WHERE status='Aktif'
    ORDER BY nama_kategori ASC
");

// ======================
// Data Ruangan
// ======================

$ruangan = mysqli_query($conn,"
    SELECT
        r.id_ruangan,
        r.nama_ruangan,
        l.nama_lantai,
        lok.nama_lokasi

    FROM ruangan r

    INNER JOIN lantai l
        ON r.id_lantai=l.id_lantai

    INNER JOIN lokasi lok
        ON l.id_lokasi=lok.id_lokasi

    WHERE r.status='Aktif'

    ORDER BY
        lok.nama_lokasi,
        l.nomor_lantai,
        r.nama_ruangan
");

// ======================
// Data Public Space
// ======================

$public = mysqli_query($conn,"
    SELECT
        ps.id_public_space,
        ps.nama_public_space,
        l.nama_lantai,
        lok.nama_lokasi

    FROM public_space ps

    INNER JOIN lantai l
        ON ps.id_lantai=l.id_lantai

    INNER JOIN lokasi lok
        ON l.id_lokasi=lok.id_lokasi

    WHERE ps.status='Aktif'

    ORDER BY
        lok.nama_lokasi,
        l.nomor_lantai,
        ps.nama_public_space
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
                    Tambah Inventaris
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
                        Tambah
                    </li>

                </ol>

            </div>

        </div>

    </div>


    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <h5 class="mb-0">

                    <i class="bi bi-box-seam me-2"></i>

                    Form Tambah Inventaris

                </h5>

            </div>


            <div class="card-body">

                <form
                    action="proses_tambah.php"
                    method="POST"
                    enctype="multipart/form-data">


                    <!-- =========================================
                         DATA UTAMA
                    ========================================== -->

                    <div class="row">


                        <!-- KODE -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Kode Inventaris
                            </label>

                            <input
                                type="text"
                                name="kode_inventaris"
                                class="form-control"
                                value="<?= htmlspecialchars($old['kode_inventaris'] ?? '') ?>"
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

                                <?php while($k=mysqli_fetch_assoc($kategori)): ?>

                                    <option
                                        value="<?= $k['id_kategori'];?>"
                                        <?= (($old['id_kategori']??'')==$k['id_kategori'])?'selected':'';?>>

                                        <?= htmlspecialchars($k['nama_kategori']);?>

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
                                value="<?= htmlspecialchars($old['nama_barang'] ?? '') ?>"
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
                                value="<?= htmlspecialchars($old['merk'] ?? '') ?>">

                        </div>


                        <!-- SPESIFIKASI -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Spesifikasi
                            </label>

                            <textarea
                                name="spesifikasi"
                                rows="2"
                                class="form-control"><?= htmlspecialchars($old['spesifikasi'] ?? '') ?></textarea>

                        </div>

                    </div>


                    <hr class="my-4">


                    <!-- =========================================
                         PENEMPATAN BARANG
                    ========================================== -->

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
                                    <?= (($old['jenis_penempatan']??'')=="ruangan")?'selected':'';?>>

                                    Ruangan

                                </option>

                                <option
                                    value="public"
                                    <?= (($old['jenis_penempatan']??'')=="public")?'selected':'';?>>

                                    Public Space

                                </option>

                            </select>

                        </div>


                        <!-- RUANGAN -->

                        <div
                            class="col-md-8 mb-3"
                            id="box_ruangan"
                            style="display:none;">

                            <label class="form-label">
                                Ruangan
                            </label>

                            <select
                                name="id_ruangan"
                                class="form-select">

                                <option value="">
                                    -- Pilih Ruangan --
                                </option>

                                <?php while($r=mysqli_fetch_assoc($ruangan)): ?>

                                    <option
                                        value="<?= $r['id_ruangan'];?>">

                                        <?= htmlspecialchars($r['nama_lokasi']);?>

                                        -

                                        <?= htmlspecialchars($r['nama_lantai']);?>

                                        -

                                        <?= htmlspecialchars($r['nama_ruangan']);?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <!-- PUBLIC SPACE -->

                        <div
                            class="col-md-8 mb-3"
                            id="box_public"
                            style="display:none;">

                            <label class="form-label">
                                Public Space
                            </label>

                            <select
                                name="id_public_space"
                                class="form-select">

                                <option value="">
                                    -- Pilih Public Space --
                                </option>

                                <?php while($p=mysqli_fetch_assoc($public)): ?>

                                    <option
                                        value="<?= $p['id_public_space'];?>">

                                        <?= htmlspecialchars($p['nama_lokasi']);?>

                                        -

                                        <?= htmlspecialchars($p['nama_lantai']);?>

                                        -

                                        <?= htmlspecialchars($p['nama_public_space']);?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                    </div>


                    <hr class="my-4">


                    <!-- =========================================
                         FOTO
                    ========================================== -->

                    <h5 class="mb-3">
                        Foto Inventaris
                    </h5>


                    <div class="row">

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Foto Barang
                            </label>

                            <input
                                type="file"
                                name="foto"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp">

                            <div class="form-text">

                                Format:
                                JPG, JPEG, PNG, WEBP
                                • Maksimal 5 MB

                            </div>

                        </div>

                    </div>


                    <!-- =========================================
                         INFORMASI INVENTARIS
                    ========================================== -->

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
                                value="<?= htmlspecialchars($old['jumlah'] ?? '1'); ?>"
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
                                    value="<?= htmlspecialchars($old['harga'] ?? ''); ?>"
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
                                    <?= (($old['kondisi'] ?? '') == "Baik") ? "selected" : ""; ?>>

                                    Baik

                                </option>

                                <option
                                    value="Rusak Ringan"
                                    <?= (($old['kondisi'] ?? '') == "Rusak Ringan") ? "selected" : ""; ?>>

                                    Rusak Ringan

                                </option>

                                <option
                                    value="Rusak Berat"
                                    <?= (($old['kondisi'] ?? '') == "Rusak Berat") ? "selected" : ""; ?>>

                                    Rusak Berat

                                </option>

                            </select>

                        </div>


                        <!-- TAHUN -->

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
                                value="<?= htmlspecialchars($old['tahun_perolehan'] ?? ''); ?>">

                        </div>


                        <!-- SUMBER -->

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Sumber Perolehan
                            </label>

                            <input
                                type="text"
                                name="sumber_perolehan"
                                class="form-control"
                                value="<?= htmlspecialchars($old['sumber_perolehan'] ?? ''); ?>">

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
                                    <?= (($old['status'] ?? '') == "Aktif") ? "selected" : ""; ?>>

                                    Aktif

                                </option>

                                <option
                                    value="Nonaktif"
                                    <?= (($old['status'] ?? '') == "Nonaktif") ? "selected" : ""; ?>>

                                    Nonaktif

                                </option>

                            </select>

                        </div>

                    </div>


                    <hr>


                    <!-- =========================================
                         BUTTON
                    ========================================== -->

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

                        Simpan

                    </button>


                </form>

            </div>

        </div>

    </div>

</main>


<script>

function togglePenempatan(){

    let jenis =
        document.getElementById("jenis_penempatan").value;

    let boxRuangan =
        document.getElementById("box_ruangan");

    let boxPublic =
        document.getElementById("box_public");

    let selectRuangan =
        document.querySelector(
            "select[name='id_ruangan']"
        );

    let selectPublic =
        document.querySelector(
            "select[name='id_public_space']"
        );


    if(jenis === "ruangan"){

        boxRuangan.style.display = "block";

        boxPublic.style.display = "none";

        selectRuangan.required = true;

        selectPublic.required = false;

        selectPublic.value = "";

    }

    else if(jenis === "public"){

        boxRuangan.style.display = "none";

        boxPublic.style.display = "block";

        selectRuangan.required = false;

        selectPublic.required = true;

        selectRuangan.value = "";

    }

    else{

        boxRuangan.style.display = "none";

        boxPublic.style.display = "none";

        selectRuangan.required = false;

        selectPublic.required = false;

    }

}


document.addEventListener(
    "DOMContentLoaded",
    function(){

        document
            .getElementById("jenis_penempatan")
            .addEventListener(
                "change",
                togglePenempatan
            );

        togglePenempatan();

    }
);

</script>


<?php

unset($_SESSION['old']);

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>