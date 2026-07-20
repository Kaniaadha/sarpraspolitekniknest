<?php
session_start();

$menu = "stock_opname";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";


$id_admin = $_SESSION['id_admin'];

$queryAdmin = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE id_admin = '$id_admin'
");

$admin = mysqli_fetch_assoc($queryAdmin);

$queryKode = mysqli_query($conn, "
    SELECT MAX(id_stock_opname) AS id_terakhir
    FROM stock_opname
");

$dataKode = mysqli_fetch_assoc($queryKode);

$nomor = ($dataKode['id_terakhir'] ?? 0) + 1;

$kodeStockOpname =
    "SO" .
    date('Ymd') .
    str_pad($nomor, 3, "0", STR_PAD_LEFT);

$queryInventaris = mysqli_query($conn, "
    SELECT
        i.*,
        k.nama_kategori,

        r.nama_ruangan,
        lr.nama_lantai AS lantai_ruangan,
        lokr.nama_lokasi AS lokasi_ruangan,

        ps.nama_public_space,
        lp.nama_lantai AS lantai_public,
        lokp.nama_lokasi AS lokasi_public

    FROM inventaris i

    INNER JOIN kategori k
        ON i.id_kategori = k.id_kategori

    LEFT JOIN ruangan r
        ON i.id_ruangan = r.id_ruangan

    LEFT JOIN lantai lr
        ON r.id_lantai = lr.id_lantai

    LEFT JOIN lokasi lokr
        ON lr.id_lokasi = lokr.id_lokasi

    LEFT JOIN public_space ps
        ON i.id_public_space = ps.id_public_space

    LEFT JOIN lantai lp
        ON ps.id_lantai = lp.id_lantai

    LEFT JOIN lokasi lokp
        ON lp.id_lokasi = lokp.id_lokasi

    WHERE i.status = 'Aktif'

    ORDER BY i.nama_barang ASC
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

                    Stock Opname

                </h2>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">

                        Dashboard

                    </li>

                    <li class="breadcrumb-item">

                        Transaksi

                    </li>

                    <li class="breadcrumb-item active">

                        Stock Opname

                    </li>

                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <form action="proses_tambah.php" method="POST">

            <input
                type="hidden"
                name="kode_stock_opname"
                value="<?= $kodeStockOpname; ?>">

            <input
                type="hidden"
                name="id_admin"
                value="<?= $id_admin; ?>">

            <input
                type="hidden"
                name="tanggal"
                value="<?= date('Y-m-d'); ?>">

            <input
                type="hidden"
                name="status"
                value="Draft">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header py-3">

                    <h5 class="mb-0">

                        <i class="bi bi-clipboard-check me-2"></i>

                        Informasi Stock Opname

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <table class="table table-borderless mb-0">

                                <tr>

                                    <th width="180">

                                        Kode Stock Opname

                                    </th>

                                    <td width="20">:</td>

                                    <td>

                                        <?= $kodeStockOpname; ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Petugas

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= $admin['nama_admin']; ?>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Tanggal

                                    </th>

                                    <td>:</td>

                                    <td>

                                        <?= date('d-m-Y'); ?>

                                    </td>

                                </tr>

                            </table>

                        </div>

                    </div>

                    <div class="alert alert-info mt-4 mb-0">

                        <h5>

                            <i class="bi bi-info-circle me-2"></i>

                            Informasi

                        </h5>

                        <ul class="mb-0">

                            <li>Stok Sistem berasal dari data inventaris.</li>

                            <li>Isi Stok Fisik sesuai hasil pemeriksaan.</li>

                            <li>Selisih dihitung otomatis oleh sistem.</li>

                            <li>Data akan tersimpan setelah menekan tombol <strong>Simpan Stock Opname</strong>.</li>

                        </ul>

                    </div>

                </div>

            </div>

<div class="card border-0 shadow-sm">

    <div class="card-header py-3">

    <div class="d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            <i class="bi bi-box-seam me-2"></i>

            Daftar Inventaris

        </h5>

    </div>

</div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle datatable">

                <thead class="table-secondary">

                    <tr>

                        <th width="5%">

                            No

                        </th>

                        <th>

                            Kode

                        </th>

                        <th>

                            Nama Barang

                        </th>

                        <th>

                            Kategori

                        </th>

                        <th>

                            Penempatan

                        </th>

                        <th>

                            Stok Sistem

                        </th>

                        <th width="10%">

                            Stok Fisik

                        </th>

                        <th width="10%">

                            Selisih

                        </th>

                        <th width="15%">

                            Kondisi

                        </th>

                        <th>

                            Catatan

                        </th>

                    </tr>

                </thead>

                <tbody>

<?php

$no = 1;

while ($row = mysqli_fetch_assoc($queryInventaris)) :

?>

<tr>

    <td>

        <?= $no++; ?>

        <input
            type="hidden"
            name="id_inventaris[]"
            value="<?= $row['id_inventaris']; ?>">

    </td>

    <td>

        <?= htmlspecialchars($row['kode_inventaris']); ?>

    </td>

    <td>

        <strong>

            <?= htmlspecialchars($row['nama_barang']); ?>

        </strong>

        <?php if (!empty($row['merk'])) : ?>

            <br>

            <small class="text-muted">

                <?= htmlspecialchars($row['merk']); ?>

            </small>

        <?php endif; ?>

    </td>

    <td>

        <?= htmlspecialchars($row['nama_kategori']); ?>

    </td>

    <td>

        <?php if (!empty($row['id_ruangan'])) : ?>

            <?= htmlspecialchars($row['lokasi_ruangan']); ?>

            <br>

            <?= htmlspecialchars($row['lantai_ruangan']); ?>

            <br>

            <strong>

                <?= htmlspecialchars($row['nama_ruangan']); ?>

            </strong>

        <?php else : ?>

            <?= htmlspecialchars($row['lokasi_public']); ?>

            <br>

            <?= htmlspecialchars($row['lantai_public']); ?>

            <br>

            <strong>

                <?= htmlspecialchars($row['nama_public_space']); ?>

            </strong>

        <?php endif; ?>

    </td>

    <td class="text-center">

        <?= number_format($row['jumlah']); ?>

        <input
            type="hidden"
            name="stok_sistem[]"
            value="<?= $row['jumlah']; ?>">

    </td>

    <td>

        <input
            type="number"
            name="stok_fisik[]"
            class="form-control text-center stok-fisik"
            data-stok="<?= $row['jumlah']; ?>"
            min="0"
            value="<?= $row['jumlah']; ?>"
            required>

    </td>

    <td class="text-center">

        <span class="badge bg-secondary selisih">

            0

        </span>

    </td>

    <td>

        <select
            name="kondisi[]"
            class="form-select"
            required>

            <option value="Baik">

                Baik

            </option>

            <option value="Rusak Ringan">

                Rusak Ringan

            </option>

            <option value="Rusak Berat">

                Rusak Berat

            </option>

        </select>

    </td>

    <td>

        <textarea
            name="catatan[]"
            class="form-control"
            rows="1"
            placeholder="Catatan"></textarea>

    </td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

    <a href="riwayat.php" class="btn btn-secondary">
        <i class="bi bi-clock-history me-1"></i>
        Riwayat Stock Opname
    </a>

    <button type="submit" class="btn btn-success">
        <i class="bi bi-check-circle me-1"></i>
        Simpan Stock Opname
    </button>

</div>

</form>

</div>

</main>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const stokFisik = document.querySelectorAll(".stok-fisik");

    stokFisik.forEach(function (input) {

        hitungSelisih(input);

        input.addEventListener("input", function () {

            hitungSelisih(this);

        });

    });

});

function hitungSelisih(input) {

    const stokSistem = parseInt(input.dataset.stok) || 0;

    const stokFisik = parseInt(input.value) || 0;

    const selisih = stokFisik - stokSistem;

    const badge = input
        .closest("tr")
        .querySelector(".selisih");

    badge.innerHTML = selisih;

    badge.classList.remove(
        "bg-success",
        "bg-danger",
        "bg-warning",
        "bg-secondary"
    );

    if (selisih > 0) {

        badge.classList.add("bg-warning");
        badge.innerHTML = "+" + selisih;

    } else if (selisih < 0) {

        badge.classList.add("bg-danger");

    } else {

        badge.classList.add("bg-success");
        badge.innerHTML = "0";

    }

}

</script>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>