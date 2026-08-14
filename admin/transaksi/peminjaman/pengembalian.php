<?php

session_start();

// Cek login admin
$menu = "peminjaman";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

// Koneksi database
require_once "../../../config/database.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$id_peminjaman = (int) $_GET['id'];

// Mengambil data peminjaman
$queryPeminjaman = mysqli_query($conn, "
    SELECT *
    FROM peminjaman
    WHERE id_peminjaman = '$id_peminjaman'
    LIMIT 1
");

if (!$queryPeminjaman || mysqli_num_rows($queryPeminjaman) == 0) {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";
    header("Location: index.php");
    exit;
}

$peminjaman = mysqli_fetch_assoc($queryPeminjaman);

if ($peminjaman['status'] != "Dipinjam") {
    $_SESSION['error'] = "Barang tidak dapat diproses untuk pengembalian.";
    header("Location: detail.php?id=" . $id_peminjaman);
    exit;
}

// Mengambil detail barang
$queryDetail = mysqli_query($conn, "
    SELECT
        dp.*,
        i.kode_inventaris,
        i.nama_barang
    FROM detail_peminjaman dp
    INNER JOIN inventaris i
        ON dp.id_inventaris = i.id_inventaris
    WHERE dp.id_peminjaman = '$id_peminjaman'
    ORDER BY dp.id_detail ASC
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
                    Konfirmasi Pengembalian
                </h2>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL; ?>/admin/dashboard.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        Transaksi
                    </li>

                    <li class="breadcrumb-item">
                        <a href="index.php">
                            Peminjaman
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Pengembalian
                    </li>

                </ol>

            </div>

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

        <!-- Informasi Peminjaman -->

        <div class="row mb-4">

            <div class="col-lg-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <div class="row align-items-center">

                            <div class="col">

                                <h5 class="mb-0">
                                    <i class="bi bi-person-vcard me-2"></i>
                                    Informasi Peminjaman
                                </h5>

                            </div>

                            <div class="col-auto">

                                <span class="badge bg-primary px-3 py-2">
                                    Sedang Dipinjam
                                </span>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <table class="table table-borderless">

                                    <tr>
                                        <th width="35%">Kode Peminjaman</th>
                                        <td>
                                            <strong><?= htmlspecialchars($peminjaman['kode_peminjaman']); ?></strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Nama Peminjam</th>
                                        <td><?= htmlspecialchars($peminjaman['nama_peminjam']); ?></td>
                                    </tr>

                                    <tr>
                                        <th>NIM / NIP</th>
                                        <td><?= htmlspecialchars($peminjaman['nim_nip']); ?></td>
                                    </tr>

                                    <tr>
                                        <th>No. HP</th>
                                        <td><?= htmlspecialchars($peminjaman['no_hp']); ?></td>
                                    </tr>

                                    <tr>
                                        <th>Email</th>
                                        <td><?= htmlspecialchars($peminjaman['email']); ?></td>
                                    </tr>

                                </table>

                            </div>

                            <div class="col-md-6">

                                <table class="table table-borderless">

                                    <tr>
                                        <th width="35%">Tanggal Pinjam</th>
                                        <td><?= date('d-m-Y', strtotime($peminjaman['tanggal_pinjam'])); ?></td>
                                    </tr>

                                    <tr>
                                        <th>Tanggal Kembali</th>
                                        <td><?= date('d-m-Y', strtotime($peminjaman['tanggal_kembali'])); ?></td>
                                    </tr>

                                    <tr>
                                        <th>Tujuan</th>
                                        <td><?= nl2br(htmlspecialchars($peminjaman['tujuan_peminjaman'])); ?></td>
                                    </tr>

                                    <tr>
                                        <th>Catatan Admin</th>
                                        <td>
                                            <?= !empty($peminjaman['catatan_admin'])
                                                ? nl2br(htmlspecialchars($peminjaman['catatan_admin']))
                                                : '-'; ?>
                                        </td>
                                    </tr>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Form Pengembalian -->

        <form action="proses_selesai.php" method="POST">

            <input
                type="hidden"
                name="id_peminjaman"
                value="<?= $id_peminjaman; ?>">

            <div class="card border-0 shadow-sm">

                <div class="card-header">

                    <h5 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Data Barang
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row mb-4">

            <!-- Data Barang -->

            <?php if (mysqli_num_rows($queryDetail) > 0) : ?>

                <?php while ($barang = mysqli_fetch_assoc($queryDetail)) : ?>

                    <div class="border rounded p-3 mb-4">

                        <input
                            type="hidden"
                            name="id_detail[]"
                            value="<?= $barang['id_detail']; ?>">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Kode Barang
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($barang['kode_inventaris']); ?>"
                                    readonly>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Nama Barang
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($barang['nama_barang']); ?>"
                                    readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Jumlah
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= $barang['jumlah']; ?>"
                                    readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Kondisi Sebelum
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($barang['kondisi_sebelum']); ?>"
                                    readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Kondisi Sesudah
                                </label>

                                <select
                                    name="kondisi_sesudah[]"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Kondisi --
                                    </option>

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

                            </div>

                            <div class="col-12">

                                <label class="form-label">
                                    Catatan
                                </label>

                                <textarea
                                    name="catatan[]"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Masukkan catatan apabila diperlukan..."></textarea>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else : ?>

                <div class="text-center py-5">

                    <i class="bi bi-inbox fs-1 text-muted"></i>

                    <p class="text-muted mt-3 mb-0">
                        Tidak ada data barang.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

<!-- Tombol Aksi -->

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <a
                        href="detail.php?id=<?= $id_peminjaman; ?>"
                        class="btn btn-secondary">

                        <i class="bi bi-arrow-left-circle me-1"></i>
                        Kembali

                    </a>

                </div>

                <div class="col-md-6 text-end">

                    <button
                        type="submit"
                        class="btn btn-success"
                        id="btnSimpan">

                        <i class="bi bi-check-circle me-1"></i>
                        Simpan Pengembalian

                    </button>

                </div>

            </div>

        </div>

    </div>

</form>

</div>

</div>

</main>

<!-- SweetAlert -->

<script>

document.getElementById("btnSimpan").addEventListener("click", function (e) {

    e.preventDefault();

    Swal.fire({

        title: "Konfirmasi Pengembalian",

        html: `
            Pastikan barang telah diterima,
            jumlah sesuai, dan kondisi barang
            sudah diperiksa.
        `,

        icon: "question",

        showCancelButton: true,

        confirmButtonColor: "#198754",

        cancelButtonColor: "#6c757d",

        confirmButtonText: "Ya, Simpan",

        cancelButtonText: "Batal",

        reverseButtons: true

    }).then((result) => {

        if (result.isConfirmed) {

            this.closest("form").submit();

        }

    });

});

</script>

<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>