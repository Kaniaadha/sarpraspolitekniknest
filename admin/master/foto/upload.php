<?php

declare(strict_types=1);

/**
 * ==========================================================
 * Upload Gallery
 * Sistem Informasi Sarana dan Prasarana (SISARPRAS)
 * Politeknik NEST
 * ----------------------------------------------------------
 * File ini digunakan untuk mengunggah foto gallery
 * pada modul:
 * - Lokasi
 * - Ruangan
 * - Public Space
 *
 * Author  : SISARPRAS Team
 * Version : 2.0
 * ==========================================================
 */

session_start();

/*
|--------------------------------------------------------------------------
| Load Dependencies
|--------------------------------------------------------------------------
*/

require_once '../../../config/database.php';
require_once 'service.php';
require_once 'config.php';
require_once 'helper.php';


/*
|--------------------------------------------------------------------------
| Validasi Request
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid Request.');
}


/*
|--------------------------------------------------------------------------
| Validasi ID Data
|--------------------------------------------------------------------------
*/

$id = filter_input(
    INPUT_POST,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {

    $_SESSION['error'] = uploadError(
        $config,
        'required'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;
}


/*
|--------------------------------------------------------------------------
| Validasi File Upload
|--------------------------------------------------------------------------
*/

if (
    !isset($_FILES['foto']) ||
    empty($_FILES['foto']['name'])
) {

    $_SESSION['error'] = uploadError(
        $config,
        'required'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;
}

$file = $_FILES['foto'];


/*
|--------------------------------------------------------------------------
| Validasi Gambar
|--------------------------------------------------------------------------
*/

$validation = validateImage(
    $file,
    $config
);

if ($validation !== true) {

    $_SESSION['error'] = $validation;

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;
}


/*
|--------------------------------------------------------------------------
| Cek Jumlah Foto
|--------------------------------------------------------------------------
*/

$totalPhoto = getPhotoCount(
    $conn,
    $table,
    $foreignKey,
    $id
);

if ($totalPhoto >= $config['upload']['max_photo']) {

    $_SESSION['error'] = uploadError(
        $config,
        'max_photo'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;
}

/*
|--------------------------------------------------------------------------
| Generate File Information
|--------------------------------------------------------------------------
*/

$extension = getFileExtension(
    $file['name']
);

$fileName = generateUniqueFileName(
    $extension
);


/*
|--------------------------------------------------------------------------
| Upload Directory
|--------------------------------------------------------------------------
*/

$baseUploadPath = '../../../assets/uploads/';

$uploadPath = $baseUploadPath . $uploadFolder . DIRECTORY_SEPARATOR;

if (!ensureUploadDirectory($uploadPath)) {

    $_SESSION['error'] = uploadError(
        $config,
        'upload_failed'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;
}

$destination = $uploadPath . $fileName;


/*
|--------------------------------------------------------------------------
| Gallery Order
|--------------------------------------------------------------------------
*/

$order = getNextOrder(
    $conn,
    $table,
    $foreignKey,
    $id
);


/*
|--------------------------------------------------------------------------
| Cover Status
|--------------------------------------------------------------------------
|
| Upload pertama otomatis menjadi cover.
|
*/

$isCover = ($totalPhoto === 0) ? 1 : 0;


/*
|--------------------------------------------------------------------------
| Database Transaction
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();


try {

    /*
    |--------------------------------------------------------------------------
    | Upload Physical File
    |--------------------------------------------------------------------------
    */

    if (!move_uploaded_file(
        $file['tmp_name'],
        $destination
    )) {

        throw new Exception(
            uploadError(
                $config,
                'upload_failed'
            )
        );
    }

        /*
    |--------------------------------------------------------------------------
    | Insert Database
    |--------------------------------------------------------------------------
    */

    $sql = "
        INSERT INTO {$table}
        (
            {$foreignKey},
            nama_file,
            is_cover,
            urutan
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Gagal menyiapkan query database.');
    }

    $stmt->bind_param(
        "isii",
        $id,
        $fileName,
        $isCover,
        $order
    );

    if (!$stmt->execute()) {
        throw new Exception('Gagal menyimpan data foto.');
    }

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | Commit Transaction
    |--------------------------------------------------------------------------
    */

    $conn->commit();

    $namaModul = ucfirst(str_replace('_', ' ', $module));

    mysqli_query($conn, "
        INSERT INTO activity_log
        (
            id_admin,
            aktivitas,
            tabel_terkait,
            id_data
        )
        VALUES
        (
            '{$_SESSION['id_admin']}',
            'Menambah Foto {$namaModul}',
            '{$table}',
            '$id'
        )
    ");

    $_SESSION['success'] = uploadSuccess(
        $config,
        'upload_success'
    );

} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | Rollback Transaction
    |--------------------------------------------------------------------------
    */

    $conn->rollback();


    /*
    |--------------------------------------------------------------------------
    | Delete Uploaded File
    |--------------------------------------------------------------------------
    */

    deletePhysicalFile(
        $destination
    );


    /*
    |--------------------------------------------------------------------------
    | Error Message
    |--------------------------------------------------------------------------
    */

    $_SESSION['error'] = $e->getMessage();
}
/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: index.php?tipe={$tipe}&id={$id}");
exit;