<?php
session_start();

$menu = "peminjaman";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

$queryInventaris = mysqli_query($conn, "
    SELECT
        id_inventaris,
        kode_inventaris,
        nama_barang,
        jumlah,
        kondisi
    FROM inventaris
    WHERE status = 'Aktif'
    AND jumlah > 0
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

                    Tambah Peminjaman

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
                        Tambah
                    </li>

                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <form action="proses_tambah.php" method="POST">

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
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                No HP

                            </label>

                            <input
                                type="text"
                                name="no_hp"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Tanggal Pinjam

                            </label>

                            <input
                                type="date"
                                name="tanggal_pinjam"
                                class="form-control"
                                value="<?= date('Y-m-d'); ?>"
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
                                min="<?= date('Y-m-d'); ?>"
                                required>

                        </div>

                        <div class="col-12">

                            <label class="form-label">

                                Tujuan Peminjaman

                            </label>

                            <textarea
                                name="tujuan_peminjaman"
                                class="form-control"
                                rows="3"
                                required></textarea>

                        </div>

                    </div>

                </div>

            </div>

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

                        <div class="barang-item border rounded p-3 mb-3">

                            <div class="row">

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
                                        mysqli_data_seek($queryInventaris,0);

                                        while($barang=mysqli_fetch_assoc($queryInventaris)):
                                        ?>

                                        <option
                                            value="<?= $barang['id_inventaris']; ?>"
                                            data-stok="<?= $barang['jumlah']; ?>">

                                            <?= htmlspecialchars($barang['kode_inventaris']); ?>

                                            |

                                            <?= htmlspecialchars($barang['nama_barang']); ?>

                                            (Stok :
                                            <?= $barang['jumlah']; ?>)

                                        </option>

                                        <?php endwhile; ?>

                                    </select>

                                </div>

                                <div class="col-md-2 mb-3">

                                    <label class="form-label">

                                        Jumlah

                                    </label>

                                    <input
                                        type="number"
                                        name="jumlah[]"
                                        class="form-control jumlahBarang"
                                        min="1"
                                        required>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">

                                        Kondisi Sebelum

                                    </label>

                                    <select
                                        name="kondisi_sebelum[]"
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

                                </div>

                                <div class="col-md-10">

                                    <label class="form-label">

                                        Catatan

                                    </label>

                                    <textarea
                                        name="catatan[]"
                                        rows="2"
                                        class="form-control"
                                        placeholder="Opsional"></textarea>

                                </div>

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

                    Simpan Peminjaman

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

    btnTambah.addEventListener("click", function(){

        let item = wrapper.querySelector(".barang-item");
        let clone = item.cloneNode(true);

        clone.querySelectorAll("input").forEach(function(input){

            input.value = "";

        });

        clone.querySelectorAll("textarea").forEach(function(textarea){

            textarea.value = "";

        });

        clone.querySelectorAll("select").forEach(function(select){

            select.selectedIndex = 0;

        });

        wrapper.appendChild(clone);

    });

    wrapper.addEventListener("click", function(e){

        if(e.target.closest(".hapusBarang")){

            let items = wrapper.querySelectorAll(".barang-item");

            if(items.length > 1){

                e.target.closest(".barang-item").remove();

            }else{

                Swal.fire({

                    icon : "warning",
                    title : "Peringatan",
                    text : "Minimal harus ada satu barang."

                });

            }

        }

    });

    form.addEventListener("submit", function(e){

        let tanggalPinjam =
            document.querySelector("[name='tanggal_pinjam']").value;

        let tanggalKembali =
            document.querySelector("[name='tanggal_kembali']").value;

        if(tanggalKembali < tanggalPinjam){

            e.preventDefault();

            Swal.fire({

                icon : "error",

                title : "Tanggal Tidak Valid",

                text : "Tanggal kembali tidak boleh lebih awal dari tanggal pinjam."

            });

            return;

        }

        let barangDipilih = [];

        let valid = true;

        document.querySelectorAll("[name='id_inventaris[]']").forEach(function(select){

            if(select.value != ""){

                if(barangDipilih.includes(select.value)){

                    valid = false;

                }

                barangDipilih.push(select.value);

            }

        });

        if(!valid){

            e.preventDefault();

            Swal.fire({

                icon : "error",

                title : "Barang Duplikat",

                text : "Barang yang sama tidak boleh dipilih lebih dari satu kali."

            });

            return;

        }

        let jumlahInput =
            document.querySelectorAll(".jumlahBarang");

        jumlahInput.forEach(function(input){

            let stok =
                input.closest(".barang-item")
                     .querySelector("select option:checked")
                     .dataset.stok;

            if(stok){

                if(parseInt(input.value) > parseInt(stok)){

                    valid = false;

                }

            }

        });

        if(!valid){

            e.preventDefault();

            Swal.fire({

                icon : "error",

                title : "Stok Tidak Mencukupi",

                text : "Jumlah pinjam melebihi stok barang."

            });

            return;

        }

    });

});

</script>

<?php
require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>