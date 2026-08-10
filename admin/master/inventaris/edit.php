<?php
session_start();

$menu = "inventaris";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM inventaris
WHERE id_inventaris='$id'
");

$data = mysqli_fetch_assoc($query);

if(!$data){
    header("Location: index.php");
    exit;
}

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
Edit Inventaris
</h2>

<ol class="breadcrumb mb-0">

<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item">Inventaris</li>
<li class="breadcrumb-item active">Edit</li>

</ol>

</div>

</div>

</div>

<div class="container-fluid">

<div class="card border-0 shadow-sm">

<div class="card-header py-3">

<h5 class="mb-0">

<i class="bi bi-pencil-square me-2"></i>

Form Edit Inventaris

</h5>

</div>

<div class="card-body">

<form
    action="proses_edit.php"
    method="POST"
    enctype="multipart/form-data">

<input
    type="hidden"
    name="id_inventaris"
    value="<?= $data['id_inventaris']; ?>">

<input
type="hidden"
name="foto_lama"
value="<?= $data['foto']; ?>">

<div class="row">

<div class="col-md-4 mb-3">

    <label class="form-label">
        Kode Inventaris
    </label>

    <input
        type="text"
        name="kode_inventaris"
        class="form-control"
        value="<?= htmlspecialchars(
            $old['kode_inventaris'] ?? $data['kode_inventaris']
        ); ?>"
        placeholder=".NBK.L3.22"
        required>

    <div class="form-text">
        Kode harus diawali dengan <strong>.NBK.</strong>
    </div>

</div>

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
<?= (($old['id_kategori'] ?? $data['id_kategori'])==$k['id_kategori'])?'selected':'';?>>

<?= htmlspecialchars($k['nama_kategori']);?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Nama Barang

</label>

<input
type="text"
name="nama_barang"
class="form-control"
value="<?= htmlspecialchars($old['nama_barang'] ?? $data['nama_barang']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Merk

</label>

<input
type="text"
name="merk"
class="form-control"
value="<?= htmlspecialchars($old['merk'] ?? $data['merk']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Spesifikasi

</label>

<textarea
name="spesifikasi"
rows="2"
class="form-control"><?= htmlspecialchars($old['spesifikasi'] ?? $data['spesifikasi']); ?></textarea>

</div>

<hr class="my-4">

<h5 class="mb-3">

Penempatan Barang

</h5>

<?php

$jenis = !empty($data['id_ruangan']) ? "ruangan" : "public";

if(isset($old['jenis_penempatan'])){
    $jenis = $old['jenis_penempatan'];
}

?>

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
<?= ($jenis=="ruangan")?"selected":"";?>>

Ruangan

</option>

<option
value="public"
<?= ($jenis=="public")?"selected":"";?>>

Public Space

</option>

</select>

</div>

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
value="<?= $r['id_ruangan'];?>"
<?= (($old['id_ruangan'] ?? $data['id_ruangan'])==$r['id_ruangan'])?'selected':'';?>>

<?= htmlspecialchars($r['nama_lokasi']);?>

-

<?= htmlspecialchars($r['nama_lantai']);?>

-

<?= htmlspecialchars($r['nama_ruangan']);?>

</option>

<?php endwhile; ?>

</select>

</div>

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
value="<?= $p['id_public_space'];?>"
<?= (($old['id_public_space'] ?? $data['id_public_space'])==$p['id_public_space'])?'selected':'';?>>

<?= htmlspecialchars($p['nama_lokasi']);?>

-

<?= htmlspecialchars($p['nama_lantai']);?>

-

<?= htmlspecialchars($p['nama_public_space']);?>

</option>

<?php endwhile; ?>

</select>

</div>
<hr class="my-4">

<h5 class="mb-3">

    Foto Inventaris

</h5>

<div class="row">

    <div class="col-md-4 mb-3">

    <?php if (!empty($data['foto'])) : ?>

        <img
            src="../../../assets/uploads/inventaris/<?= $data['foto']; ?>"
            class="img-fluid rounded border shadow-sm"
            style="max-height:220px;">

        <div class="mt-2">
            <a
                href="hapus_foto.php?id=<?= $data['id_inventaris']; ?>"
                class="btn btn-danger btn-sm btn-hapus-foto">

                <i class="fas fa-trash"></i>
                Hapus Foto

            </a>
        </div>

    <?php else : ?>

        <div
            class="border rounded d-flex align-items-center justify-content-center"
            style="height:220px;">

            <span class="text-muted">
                Belum ada foto
            </span>

        </div>

    <?php endif; ?>

    </div>

    <div class="col-md-8">

        <label class="form-label">

            Ganti Foto

        </label>

        <input
            type="file"
            name="foto"
            class="form-control"
            accept=".jpg,.jpeg,.png,.webp">

        <div class="form-text">

            Kosongkan jika tidak ingin mengganti foto.

        </div>

    </div>

</div>
<hr class="my-4">

<h5 class="mb-3">
Informasi Inventaris
</h5>

<div class="row">

    <div class="col-md-3 mb-3">

        <label class="form-label">
            Jumlah
        </label>

        <input
            type="number"
            name="jumlah"
            class="form-control"
            min="1"
            value="<?= htmlspecialchars($old['jumlah'] ?? $data['jumlah']); ?>"
            required>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">
            Kondisi
        </label>

        <select
            name="kondisi"
            class="form-select"
            required>

            <option value="">-- Pilih Kondisi --</option>

            <option
                value="Baik"
                <?= (($old['kondisi'] ?? $data['kondisi'])=="Baik") ? "selected" : ""; ?>>
                Baik
            </option>

            <option
                value="Rusak Ringan"
                <?= (($old['kondisi'] ?? $data['kondisi'])=="Rusak Ringan") ? "selected" : ""; ?>>
                Rusak Ringan
            </option>

            <option
                value="Rusak Berat"
                <?= (($old['kondisi'] ?? $data['kondisi'])=="Rusak Berat") ? "selected" : ""; ?>>
                Rusak Berat
            </option>

        </select>

    </div>

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
            value="<?= htmlspecialchars($old['tahun_perolehan'] ?? $data['tahun_perolehan']); ?>">

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">
            Sumber Perolehan
        </label>

        <input
            type="text"
            name="sumber_perolehan"
            class="form-control"
            value="<?= htmlspecialchars($old['sumber_perolehan'] ?? $data['sumber_perolehan']); ?>">

    </div>

    <div class="col-md-12 mb-3">

        <label class="form-label">
            Status
        </label>

        <select
            name="status"
            class="form-select"
            required>

            <option value="">-- Pilih Status --</option>

            <option
                value="Aktif"
                <?= (($old['status'] ?? $data['status'])=="Aktif") ? "selected" : ""; ?>>
                Aktif
            </option>

            <option
                value="Nonaktif"
                <?= (($old['status'] ?? $data['status'])=="Nonaktif") ? "selected" : ""; ?>>
                Nonaktif
            </option>

        </select>

    </div>

</div>

<hr>

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

<script>

function togglePenempatan(){

    const jenis = document.getElementById("jenis_penempatan").value;

    const boxRuangan = document.getElementById("box_ruangan");
    const boxPublic = document.getElementById("box_public");

    const selectRuangan = document.querySelector("select[name='id_ruangan']");
    const selectPublic = document.querySelector("select[name='id_public_space']");

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

document.addEventListener("DOMContentLoaded", function(){

    document
        .getElementById("jenis_penempatan")
        .addEventListener("change", togglePenempatan);

    togglePenempatan();

});

</script>
<script>
document.querySelectorAll('.btn-hapus-foto').forEach(button => {

    button.addEventListener('click', function(e) {

        e.preventDefault();

        const url = this.getAttribute('href');

        Swal.fire({
            title: 'Hapus Foto?',
            text: 'Foto inventaris akan dihapus, tetapi data inventaris tetap tersimpan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {
                window.location.href = url;
            }

        });

    });

});
</script>
<?php
unset($_SESSION['old']);

require_once "../../../includes/footer.php";
require_once "../../../includes/scripts.php";
?>