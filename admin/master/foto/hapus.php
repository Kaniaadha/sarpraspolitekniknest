<?php

declare(strict_types=1);

/**
 * ==========================================================
 * Delete Gallery Photo
 * Sistem Informasi Sarana dan Prasarana (SISARPRAS)
 * Politeknik NEST
 * ----------------------------------------------------------
 * Menghapus foto gallery selain cover.
 *
 * ==========================================================
 */

session_start();


/*
|--------------------------------------------------------------------------
| Cek Login Admin
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['id_admin']) ||
    !is_numeric($_SESSION['id_admin'])
) {

    $_SESSION['error'] =
        'Sesi admin tidak ditemukan. Silakan login kembali.';

    header("Location: ../../../login.php");
    exit;
}


$idAdminLogin =
    (int) $_SESSION['id_admin'];


/*
|--------------------------------------------------------------------------
| Load Dependencies
|--------------------------------------------------------------------------
*/

require_once '../../../config/database.php';
require_once '../../../helpers/activity_log.php';
require_once 'service.php';
require_once 'config.php';
require_once 'helper.php';


/*
|--------------------------------------------------------------------------
| Validasi Request
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    exit('Invalid Request.');

}


/*
|--------------------------------------------------------------------------
| Validasi Parameter
|--------------------------------------------------------------------------
*/

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


$photoId = filter_input(
    INPUT_GET,
    'foto',
    FILTER_VALIDATE_INT
);


if (!$id || !$photoId) {

    $_SESSION['error'] = uploadError(
        $config,
        'required'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;

}


/*
|--------------------------------------------------------------------------
| Ambil Data Foto
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        {$primaryKey},
        {$foreignKey},
        nama_file,
        is_cover
    FROM {$table}
    WHERE {$primaryKey} = ?
      AND {$foreignKey} = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    $_SESSION['error'] =
        'Gagal menyiapkan query database.';

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;

}


$stmt->bind_param(
    "ii",
    $photoId,
    $id
);


$stmt->execute();


$result = $stmt->get_result();


$photo = $result->fetch_assoc();


if (!$photo) {

    $stmt->close();

    $_SESSION['error'] = uploadError(
        $config,
        'photo_not_found'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;

}


/*
|--------------------------------------------------------------------------
| Cegah Menghapus Cover
|--------------------------------------------------------------------------
*/

if ((int) $photo['is_cover'] === 1) {

    $stmt->close();

    $_SESSION['error'] = uploadError(
        $config,
        'cover_delete'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;

}


$stmt->close();


/*
|--------------------------------------------------------------------------
| File Path
|--------------------------------------------------------------------------
*/

$filePath =
    '../../../assets/uploads/'
    . $uploadFolder
    . DIRECTORY_SEPARATOR
    . $photo['nama_file'];


/*
|--------------------------------------------------------------------------
| Database Transaction
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();


try {

    /*
    |--------------------------------------------------------------------------
    | Delete Database
    |--------------------------------------------------------------------------
    */

    $sql = "
        DELETE FROM {$table}
        WHERE {$primaryKey} = ?
          AND {$foreignKey} = ?
    ";


    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        throw new Exception(
            'Gagal menyiapkan query database.'
        );

    }


    $stmt->bind_param(
        "ii",
        $photoId,
        $id
    );


    if (!$stmt->execute()) {

        throw new Exception(
            uploadError(
                $config,
                'delete_failed'
            )
        );

    }


    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | Delete Physical File
    |--------------------------------------------------------------------------
    */

    if (!deletePhysicalFile($filePath)) {

        throw new Exception(
            uploadError(
                $config,
                'delete_failed'
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Rapikan Urutan Gallery
    |--------------------------------------------------------------------------
    */

    if (
        !reorderGallery(
            $conn,
            $table,
            $primaryKey,
            $foreignKey,
            $id
        )
    ) {

        throw new Exception(
            'Gagal merapikan urutan gallery.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    $conn->commit();


    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    $namaModul =
        ucfirst(
            str_replace(
                '_',
                ' ',
                $module
            )
        );


    simpanActivityLog(
        $conn,
        $idAdminLogin,
        "Menghapus Foto {$namaModul}",
        $table,
        $id
    );


    /*
    |--------------------------------------------------------------------------
    | Success Message
    |--------------------------------------------------------------------------
    */

    $_SESSION['success'] = uploadSuccess(
        $config,
        'delete_success'
    );


} catch (Exception $e) {


    /*
    |--------------------------------------------------------------------------
    | Rollback
    |--------------------------------------------------------------------------
    */

    $conn->rollback();


    $_SESSION['error'] =
        $e->getMessage();

}


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header(
    "Location: index.php?tipe={$tipe}&id={$id}"
);

exit;

?>