<?php
session_start();

$menu = "inventaris";

if (!isset($_SESSION['id_admin'])) {
    header("Location: ../../../login.php");
    exit;
}

require_once "../../../config/database.php";

$query = mysqli_query($conn, "
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

    ORDER BY
        i.nama_barang ASC
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
                    Data Inventaris
                </h2>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        Dashboard
                    </li>

                    <li class="breadcrumb-item">
                        Master
                    </li>

                    <li class="breadcrumb-item active">
                        Inventaris
                    </li>

                </ol>

            </div>

        </div>

    </div>

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-header py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        <i class="bi bi-box-seam me-2"></i>

                        Daftar Inventaris

                    </h5>

                    <a
                        href="tambah.php"
                        class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>

                        Tambah Inventaris

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

                                <th width="8%">
                                    Gambar
                                </th>

                                <th class="text-center">
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

                                <th class="text-center">
                                    Jumlah
                                </th>

                                <th class="text-center">
                                    Kondisi
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th width="15%" class="text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php $no = 1; ?>

                            <?php while($row = mysqli_fetch_assoc($query)) : ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>
                                    <td class="text-center">

                                        <?php if (!empty($row['foto'])) : ?>

                                            <img
                                                src="../../../assets/uploads/inventaris/<?= htmlspecialchars($row['foto']); ?>"
                                                class="img-thumbnail"
                                                style="
                                                    width:70px;
                                                    height:70px;
                                                    object-fit:cover;
                                                    cursor:pointer;
                                                "
                                                onclick="previewFoto(this.src)">

                                        <?php else : ?>

                                            <div
                                                class="border rounded d-flex align-items-center justify-content-center mx-auto"
                                                style="
                                                    width:70px;
                                                    height:70px;
                                                    color:#999;
                                                    font-size:12px;
                                                ">

                                                Tidak Ada

                                            </div>

                                        <?php endif; ?>

                                    </td>
                                    <td class="text-center">
                                        <?= htmlspecialchars($row['kode_inventaris']); ?>
                                    </td>

                                    <td>

                                        <strong>

                                            <?= htmlspecialchars($row['nama_barang']); ?>

                                        </strong>

                                        <?php if(!empty($row['merk'])) : ?>

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

                                        <?php if(!empty($row['id_ruangan'])) : ?>

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

                                    </td>

                            <td class="text-center">

                                <?php

                                switch($row['kondisi']){

                                    case "Baik":

                                        echo '<span class="badge bg-success">Baik</span>';

                                        break;

                                    case "Rusak Ringan":

                                        echo '<span class="badge bg-warning text-dark">Rusak Ringan</span>';

                                        break;

                                    case "Rusak Berat":

                                        echo '<span class="badge bg-danger">Rusak Berat</span>';

                                        break;

                                    default:

                                        echo '<span class="badge bg-secondary">'
                                            . htmlspecialchars($row['kondisi']) .
                                            '</span>';

                                        break;

                                }

                                ?>

                            </td>

                            <td class="text-center">

                                <?php if($row['status']=="Aktif") : ?>

                                    <span class="badge bg-success rounded-pill">

                                        Aktif

                                    </span>

                                <?php else : ?>

                                    <span class="badge bg-danger rounded-pill">

                                        Nonaktif

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="text-center">

                                <a
                                    href="edit.php?id=<?= $row['id_inventaris']; ?>"
                                    class="btn btn-warning btn-sm me-1">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a
                                    href="#"
                                    class="btn btn-danger btn-sm"
                                    onclick="hapusInventaris(<?= $row['id_inventaris']; ?>)">

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

                            function hapusInventaris(id){

                                Swal.fire({

                                    title: 'Hapus Data?',

                                    text: 'Data inventaris yang dihapus tidak dapat dikembalikan.',

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
                            function previewFoto(src){

                                Swal.fire({

                                    imageUrl: src,
                                    imageAlt: 'Foto Inventaris',
                                    showConfirmButton: false,
                                    showCloseButton: true,
                                    width: 700

                                });

                            }
                            </script>

                            <?php
                            require_once "../../../includes/footer.php";
                            require_once "../../../includes/scripts.php";
                            ?>