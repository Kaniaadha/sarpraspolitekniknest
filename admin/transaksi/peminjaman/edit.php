<?php
session_start();

$menu = "peminjaman";

if (!isset($_SESSION['id_admin'])) {

    header("Location: ../../../login.php");
    exit;

}

require_once "../../../config/database.php";

if (
    !isset($_GET['id']) ||
    empty($_GET['id'])
) {

    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";

    header("Location: index.php");
    exit;

}

$id_peminjaman = (int) $_GET['id'];


$queryPeminjaman = mysqli_query($conn, "
    SELECT *
    FROM peminjaman
    WHERE id_peminjaman = '$id_peminjaman'
");

if (
    !$queryPeminjaman ||
    mysqli_num_rows($queryPeminjaman) == 0
) {

    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";

    header("Location: index.php");
    exit;

}

$peminjaman = mysqli_fetch_assoc($queryPeminjaman);


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


$queryInventaris = mysqli_query($conn, "
    SELECT
        id_inventaris,
        kode_inventaris,
        nama_barang,
        jumlah,
        kondisi,
        status
    FROM inventaris
    WHERE status = 'Aktif'
    ORDER BY nama_barang ASC
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

                    Edit Peminjaman

                </h2>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        Dashboard
                    </li>

                    <li class="breadcrumb-item">
                        Transaksi
                    </li>

                    <li class="breadcrumb-item">
                        Peminjaman
                    </li>

                    <li class="breadcrumb-item active">
                        Edit
                    </li>

                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <form
            action="proses_edit.php"
            method="POST">

            <input
                type="hidden"
                name="id_peminjaman"
                value="<?= $peminjaman['id_peminjaman']; ?>">

            <!-- Card Data Peminjam -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header">

        <h5 class="mb-0">

            <i class="bi bi-person-circle me-2"></i>

            Data Peminjam

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Nama Peminjam

                </label>

                <input
                    type="text"
                    name="nama_peminjam"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['nama_peminjam']); ?>"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    NIM / NIP

                </label>

                <input
                    type="text"
                    name="nim_nip"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['nim_nip']); ?>"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    No. HP

                </label>

                <input
                    type="text"
                    name="no_hp"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['no_hp']); ?>">

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Email

                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['email']); ?>">

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Tanggal Pinjam

                </label>

                <input
                    type="date"
                    name="tanggal_pinjam"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['tanggal_pinjam']); ?>"
                    min="<?= date('Y-m-d'); ?>"
                    required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Tanggal Kembali

                </label>

                <input
                    type="date"
                    name="tanggal_kembali"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['tanggal_kembali']); ?>"
                    min="<?= date('Y-m-d'); ?>"
                    required>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label">

                    Tujuan Peminjaman

                </label>

                <textarea
                    name="tujuan_peminjaman"
                    class="form-control"
                    rows="3"
                    required><?= htmlspecialchars($peminjaman['tujuan_peminjaman']); ?></textarea>

            </div>

            <div class="col-md-4">

                <label class="form-label">

                    Status

                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?= htmlspecialchars($peminjaman['status']); ?>"
                    readonly>

            </div>

        </div>

    </div>

</div>

<!-- Card Data Barang -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            <i class="bi bi-box-seam me-2"></i>

            Data Barang

        </h5>

        <button
            type="button"
            class="btn btn-success btn-sm"
            id="tambahBarang">

            <i class="bi bi-plus-circle me-1"></i>

            Tambah Barang

        </button>

    </div>

    <div class="card-body">

        <div id="barangWrapper">

            <?php while ($detail = mysqli_fetch_assoc($queryDetail)) : ?>

                <div class="barang-item border rounded p-3 mb-3">

                    <div class="row">

                        <!-- Barang -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Barang

                            </label>

                            <select
                                name="id_inventaris[]"
                                class="form-select"
                                required>

                                <option value="">

                                    -- Pilih Barang --

                                </option>

                                <?php

                                mysqli_data_seek($queryInventaris, 0);

                                while ($barang = mysqli_fetch_assoc($queryInventaris)) :

                                ?>

                                    <option
                                        value="<?= $barang['id_inventaris']; ?>"
                                        data-stok="<?= $barang['jumlah']; ?>"
                                        <?= ((int)$barang['id_inventaris'] === (int)$detail['id_inventaris']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($barang['kode_inventaris']); ?>

                                        |

                                        <?= htmlspecialchars($barang['nama_barang']); ?>

                                        (Stok :
                                        <?= $barang['jumlah']; ?>)

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <!-- Jumlah -->

                        <div class="col-md-2 mb-3">

                            <label class="form-label">

                                Jumlah

                            </label>

                            <input
                                type="number"
                                name="jumlah[]"
                                class="form-control jumlahBarang"
                                min="1"
                                value="<?= $detail['jumlah']; ?>"
                                required>

                        </div>

                        <!-- Kondisi -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Kondisi Sebelum

                            </label>

                            <select
                                name="kondisi_sebelum[]"
                                class="form-select"
                                required>

                                <option
                                    value="Baik"
                                    <?= ($detail['kondisi_sebelum'] == "Baik") ? "selected" : ""; ?>>

                                    Baik

                                </option>

                                <option
                                    value="Rusak Ringan"
                                    <?= ($detail['kondisi_sebelum'] == "Rusak Ringan") ? "selected" : ""; ?>>

                                    Rusak Ringan

                                </option>

                                <option
                                    value="Rusak Berat"
                                    <?= ($detail['kondisi_sebelum'] == "Rusak Berat") ? "selected" : ""; ?>>

                                    Rusak Berat

                                </option>

                            </select>

                        </div>

                        <!-- Catatan -->

                        <div class="col-md-10">

                            <label class="form-label">

                                Catatan

                            </label>

                            <textarea
                                name="catatan[]"
                                rows="2"
                                class="form-control"
                                placeholder="Opsional"><?= htmlspecialchars($detail['catatan']); ?></textarea>

                        </div>

                        <!-- Tombol -->

                        <div class="col-md-2 d-flex align-items-end">

                            <button
                                type="button"
                                class="btn btn-danger w-100 hapusBarang">

                                <i class="bi bi-trash"></i>

                                Hapus

                            </button>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </div>

</div>

<div class="d-flex justify-content-end">

    <a
        href="index.php"
        class="btn btn-secondary me-2">

        <i class="bi bi-arrow-left-circle me-1"></i>

        Kembali

    </a>

    <button
        type="submit"
        class="btn btn-primary">

        <i class="bi bi-save me-1"></i>

        Update Peminjaman

    </button>

</div>

</form>

</div>

</main>

<script>

document.addEventListener("DOMContentLoaded", function(){

    const wrapper = document.getElementById("barangWrapper");
    const btnTambah = document.getElementById("tambahBarang");
    const form = document.querySelector("form");

    // Tambah Barang
    btnTambah.addEventListener("click", function(){

        let item = wrapper.querySelector(".barang-item:last-child");
        let clone = item.cloneNode(true);

        clone.querySelectorAll("input").forEach(function(input){

            if(input.type !== "hidden"){

                input.value = "";

            }

        });

        clone.querySelectorAll("textarea").forEach(function(textarea){

            textarea.value = "";

        });

        clone.querySelectorAll("select").forEach(function(select){

            select.selectedIndex = 0;

        });

        wrapper.appendChild(clone);

    });

    // Hapus Barang
    wrapper.addEventListener("click", function(e){

        if(e.target.closest(".hapusBarang")){

            let items = wrapper.querySelectorAll(".barang-item");

            if(items.length > 1){

                e.target.closest(".barang-item").remove();

            }else{

                Swal.fire({

                    icon: "warning",

                    title: "Peringatan",

                    text: "Minimal harus ada satu barang."

                });

            }

        }

    });

    // Submit Form
    form.addEventListener("submit", function(e){

        // Validasi tanggal
        let tanggalPinjam =
            document.querySelector("[name='tanggal_pinjam']").value;

        let tanggalKembali =
            document.querySelector("[name='tanggal_kembali']").value;

        if(tanggalKembali < tanggalPinjam){

            e.preventDefault();

            Swal.fire({

                icon: "error",

                title: "Tanggal Tidak Valid",

                text: "Tanggal kembali tidak boleh lebih awal dari tanggal pinjam."

            });

            return;

        }

        let barangDipilih = [];
        let valid = true;

        document.querySelectorAll(".barang-item").forEach(function(item){

            let select = item.querySelector("[name='id_inventaris[]']");
            let jumlah = item.querySelector(".jumlahBarang");

            // Barang wajib dipilih
            if(select.value === ""){

                valid = false;

                Swal.fire({

                    icon: "error",

                    title: "Barang Belum Dipilih",

                    text: "Silakan pilih barang terlebih dahulu."

                });

                return;

            }

            // Barang duplikat
            if(barangDipilih.includes(select.value)){

                valid = false;

                Swal.fire({

                    icon: "error",

                    title: "Barang Duplikat",

                    text: "Barang yang sama tidak boleh dipilih lebih dari satu kali."

                });

                return;

            }

            barangDipilih.push(select.value);

            // Jumlah
            if(parseInt(jumlah.value) <= 0){

                valid = false;

                Swal.fire({

                    icon: "error",

                    title: "Jumlah Tidak Valid",

                    text: "Jumlah barang minimal 1."

                });

                return;

            }

            // Validasi stok
            let stok =
                select.options[select.selectedIndex].dataset.stok;

            if(stok){

                if(parseInt(jumlah.value) > parseInt(stok)){

                    valid = false;

                    Swal.fire({

                        icon: "error",

                        title: "Stok Tidak Mencukupi",

                        text: "Jumlah pinjam melebihi stok barang."

                    });

                    return;

                }

            }

        });

        if(!valid){

            e.preventDefault();

            return;

        }

    });

});

</script>

<?php

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";

?>