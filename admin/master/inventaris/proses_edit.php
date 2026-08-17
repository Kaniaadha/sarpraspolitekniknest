<?php

session_start();

require_once "../../../config/database.php";
require_once "../../../helpers/activity_log.php";
require_once "../foto/helper.php";
require_once "../foto/config.php";


// =====================================================
// AMBIL DATA POST
// =====================================================

$id_inventaris = (int) ($_POST['id_inventaris'] ?? 0);

$kode_inventaris  = trim($_POST['kode_inventaris'] ?? '');
$id_kategori      = (int) ($_POST['id_kategori'] ?? 0);
$nama_barang      = trim($_POST['nama_barang'] ?? '');
$merk             = trim($_POST['merk'] ?? '');
$spesifikasi      = trim($_POST['spesifikasi'] ?? '');

$jenis_penempatan = $_POST['jenis_penempatan'] ?? '';

$id_ruangan = !empty($_POST['id_ruangan'])
    ? (int) $_POST['id_ruangan']
    : NULL;

$id_public_space = !empty($_POST['id_public_space'])
    ? (int) $_POST['id_public_space']
    : NULL;

$jumlah           = (int) ($_POST['jumlah'] ?? 0);
$harga            = trim($_POST['harga'] ?? '');
$kondisi          = $_POST['kondisi'] ?? '';
$tahun_perolehan  = trim($_POST['tahun_perolehan'] ?? '');
$sumber_perolehan = trim($_POST['sumber_perolehan'] ?? '');
$status           = $_POST['status'] ?? '';

$currentYear = date('Y');


// =====================================================
// VALIDASI ID
// =====================================================

if ($id_inventaris <= 0) {

    $_SESSION['error'] =
        "Data inventaris tidak ditemukan.";

    header("Location: index.php");
    exit;
}


// =====================================================
// CEK DATA INVENTARIS
// =====================================================

$stmtData = mysqli_prepare(
    $conn,
    "
    SELECT *
    FROM inventaris
    WHERE id_inventaris = ?
    LIMIT 1
    "
);

mysqli_stmt_bind_param(
    $stmtData,
    "i",
    $id_inventaris
);

mysqli_stmt_execute($stmtData);

$resultData =
    mysqli_stmt_get_result($stmtData);

if (
    !$resultData ||
    mysqli_num_rows($resultData) !== 1
) {

    mysqli_stmt_close($stmtData);

    $_SESSION['error'] =
        "Data inventaris tidak ditemukan.";

    header("Location: index.php");
    exit;
}

$dataLama =
    mysqli_fetch_assoc($resultData);

mysqli_stmt_close($stmtData);


// =====================================================
// VALIDASI WAJIB
// =====================================================

if (
    empty($kode_inventaris) ||
    empty($id_kategori) ||
    empty($nama_barang) ||
    empty($jenis_penempatan) ||
    empty($jumlah) ||
    empty($kondisi) ||
    empty($status)
) {

    $_SESSION['error'] =
        "Semua field wajib diisi kecuali Merk, Spesifikasi, Harga, Tahun Perolehan, dan Sumber Perolehan.";

    header(
        "Location: edit.php?id=" .
        $id_inventaris
    );

    exit;
}


// =====================================================
// VALIDASI FORMAT KODE
// =====================================================

if (!preg_match('/^\.NBK\..+$/', $kode_inventaris)) {

    $_SESSION['error'] =
        "Kode Inventaris harus diawali dengan .NBK.";

    header(
        "Location: edit.php?id=" .
        $id_inventaris
    );

    exit;
}


// =====================================================
// VALIDASI JUMLAH
// =====================================================

if ($jumlah <= 0) {

    $_SESSION['error'] =
        "Jumlah harus lebih dari 0.";

    header(
        "Location: edit.php?id=" .
        $id_inventaris
    );

    exit;
}


// =====================================================
// VALIDASI HARGA
// =====================================================

if ($harga !== '') {

    if (!is_numeric($harga)) {

        $_SESSION['error'] =
            "Harga harus berupa angka.";

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }


    if ((float) $harga < 0) {

        $_SESSION['error'] =
            "Harga tidak boleh kurang dari 0.";

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }

}


// =====================================================
// FORMAT HARGA
// =====================================================

$harga_sql = ($harga === '')
    ? "NULL"
    : "'" .
      mysqli_real_escape_string(
          $conn,
          $harga
      ) .
      "'";


// =====================================================
// VALIDASI TAHUN
// =====================================================

if (!empty($tahun_perolehan)) {

    if (
        $tahun_perolehan < 1900 ||
        $tahun_perolehan > $currentYear
    ) {

        $_SESSION['error'] =
            "Tahun Perolehan tidak valid.";

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }
}


// =====================================================
// VALIDASI PENEMPATAN
// =====================================================

if ($jenis_penempatan === "ruangan") {

    if (empty($id_ruangan)) {

        $_SESSION['error'] =
            "Silakan pilih Ruangan.";

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }

    $id_public_space = NULL;

}

elseif ($jenis_penempatan === "public") {

    if (empty($id_public_space)) {

        $_SESSION['error'] =
            "Silakan pilih Public Space.";

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }

    $id_ruangan = NULL;

}

else {

    $_SESSION['error'] =
        "Jenis Penempatan tidak valid.";

    header(
        "Location: edit.php?id=" .
        $id_inventaris
    );

    exit;
}


// =====================================================
// CEK KODE DUPLIKAT
// =====================================================

$stmtCek = mysqli_prepare(
    $conn,
    "
    SELECT id_inventaris
    FROM inventaris
    WHERE kode_inventaris = ?
    AND id_inventaris != ?
    LIMIT 1
    "
);

mysqli_stmt_bind_param(
    $stmtCek,
    "si",
    $kode_inventaris,
    $id_inventaris
);

mysqli_stmt_execute($stmtCek);

$resultCek =
    mysqli_stmt_get_result($stmtCek);

if (
    $resultCek &&
    mysqli_num_rows($resultCek) > 0
) {

    mysqli_stmt_close($stmtCek);

    $_SESSION['error'] =
        "Kode Inventaris sudah digunakan oleh data lain.";

    header(
        "Location: edit.php?id=" .
        $id_inventaris
    );

    exit;
}

mysqli_stmt_close($stmtCek);


// =====================================================
// FOTO LAMA
// =====================================================

$fotoLama =
    $dataLama['foto'] ?? null;

$fotoBaru = $fotoLama;

$uploadFolder =
    "../../../assets/uploads/inventaris/";

ensureUploadDirectory(
    $uploadFolder
);

$destinationBaru = null;


// =====================================================
// UPLOAD FOTO BARU
// =====================================================

if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
) {

    $validation =
        validateImage(
            $_FILES['foto'],
            $config
        );

    if ($validation !== true) {

        $_SESSION['error'] =
            $validation;

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }


    $extension =
        pathinfo(
            $_FILES['foto']['name'],
            PATHINFO_EXTENSION
        );


    $fotoBaru =
        generateUniqueFileName(
            $extension
        );


    $destinationBaru =
        $uploadFolder .
        $fotoBaru;


    if (
        !move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            $destinationBaru
        )
    ) {

        $_SESSION['error'] =
            "Upload foto gagal.";

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }

}


// =====================================================
// FORMAT NULL
// =====================================================

$id_ruangan_sql =
    is_null($id_ruangan)
    ? "NULL"
    : "'" . $id_ruangan . "'";


$id_public_sql =
    is_null($id_public_space)
    ? "NULL"
    : "'" . $id_public_space . "'";


$tahun_sql =
    ($tahun_perolehan === "")
    ? "NULL"
    : "'" .
      mysqli_real_escape_string(
          $conn,
          $tahun_perolehan
      ) .
      "'";


$foto_sql =
    ($fotoBaru === null || $fotoBaru === '')
    ? "NULL"
    : "'" .
      mysqli_real_escape_string(
          $conn,
          $fotoBaru
      ) .
      "'";


// =====================================================
// UPDATE DATABASE
// =====================================================

try {

    $query = mysqli_query(
        $conn,
        "
        UPDATE inventaris
        SET

            kode_inventaris =
                '" . mysqli_real_escape_string(
                    $conn,
                    $kode_inventaris
                ) . "',

            id_kategori =
                '$id_kategori',

            id_ruangan =
                $id_ruangan_sql,

            id_public_space =
                $id_public_sql,

            nama_barang =
                '" . mysqli_real_escape_string(
                    $conn,
                    $nama_barang
                ) . "',

            merk =
                '" . mysqli_real_escape_string(
                    $conn,
                    $merk
                ) . "',

            spesifikasi =
                '" . mysqli_real_escape_string(
                    $conn,
                    $spesifikasi
                ) . "',

            jumlah =
                '$jumlah',

            harga =
                $harga_sql,

            kondisi =
                '" . mysqli_real_escape_string(
                    $conn,
                    $kondisi
                ) . "',

            tahun_perolehan =
                $tahun_sql,

            sumber_perolehan =
                '" . mysqli_real_escape_string(
                    $conn,
                    $sumber_perolehan
                ) . "',

            status =
                '" . mysqli_real_escape_string(
                    $conn,
                    $status
                ) . "',

            foto =
                $foto_sql,

            updated_at =
                NOW()

        WHERE id_inventaris =
            '$id_inventaris'
        "
    );

}
catch (mysqli_sql_exception $e) {


    // =============================================
    // DUPLICATE KODE
    // =============================================

    if ($e->getCode() == 1062) {

        if (
            $destinationBaru !== null &&
            file_exists($destinationBaru)
        ) {

            deletePhysicalFile(
                $destinationBaru
            );
        }


        $_SESSION['error'] =
            "Kode Inventaris sudah digunakan oleh data lain.";

        header(
            "Location: edit.php?id=" .
            $id_inventaris
        );

        exit;
    }


    // =============================================
    // ERROR DATABASE
    // =============================================

    if (
        $destinationBaru !== null &&
        file_exists($destinationBaru)
    ) {

        deletePhysicalFile(
            $destinationBaru
        );
    }


    $_SESSION['error'] =
        "Data Inventaris gagal diperbarui.";

    header(
        "Location: edit.php?id=" .
        $id_inventaris
    );

    exit;
}


// =====================================================
// JIKA UPDATE BERHASIL
// =====================================================

if ($query) {


    // =================================================
    // HAPUS FOTO LAMA
    // =================================================

    if (
        $destinationBaru !== null &&
        $fotoLama &&
        $fotoLama !== $fotoBaru
    ) {

        $oldPhotoPath =
            $uploadFolder .
            $fotoLama;

        if (
            file_exists($oldPhotoPath)
        ) {

            deletePhysicalFile(
                $oldPhotoPath
            );
        }

    }


    // =================================================
    // ACTIVITY LOG
    // =================================================

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengubah Inventaris",
        "inventaris",
        $id_inventaris
    );


    // =================================================
    // SUCCESS
    // =================================================

    $_SESSION['success'] =
        "Data Inventaris berhasil diperbarui.";

    header("Location: index.php");

    exit;

}


// =====================================================
// UPDATE GAGAL
// =====================================================

if (
    $destinationBaru !== null &&
    file_exists($destinationBaru)
) {

    deletePhysicalFile(
        $destinationBaru
    );
}


$_SESSION['error'] =
    "Data Inventaris gagal diperbarui.";

header(
    "Location: edit.php?id=" .
    $id_inventaris
);

exit;