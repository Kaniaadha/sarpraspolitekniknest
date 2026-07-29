<?php

declare(strict_types=1);

/**
 * ==========================================================
 * Set Cover Gallery
 * Sistem Informasi Sarana dan Prasarana (SISARPRAS)
 * Politeknik NEST
 * ----------------------------------------------------------
 * Mengubah foto yang dipilih menjadi cover gallery.
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
| Cek Apakah Foto Ada
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        {$primaryKey},
        {$foreignKey},
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

if ($result->num_rows === 0) {

    $stmt->close();

    $_SESSION['error'] = uploadError(
        $config,
        'photo_not_found'
    );

    header("Location: index.php?tipe={$tipe}&id={$id}");
    exit;
}

$stmt->close();

/*
|--------------------------------------------------------------------------
| Database Transaction
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();

try {

    /*
    |--------------------------------------------------------------------------
    | Reset Seluruh Cover
    |--------------------------------------------------------------------------
    */

    $sql = "
        UPDATE {$table}
        SET is_cover = 0
        WHERE {$foreignKey} = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Gagal memperbarui status cover.');
    }

    $stmt->bind_param(
        "i",
        $id
    );

    if (!$stmt->execute()) {
        throw new Exception('Gagal memperbarui status cover.');
    }

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | Set Cover Baru
    |--------------------------------------------------------------------------
    */

    $sql = "
        UPDATE {$table}
        SET is_cover = 1
        WHERE {$primaryKey} = ?
          AND {$foreignKey} = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Gagal menetapkan cover baru.');
    }

    $stmt->bind_param(
        "ii",
        $photoId,
        $id
    );

    if (!$stmt->execute()) {
        throw new Exception('Gagal menetapkan cover baru.');
    }

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    $conn->commit();

    $_SESSION['success'] = uploadSuccess(
        $config,
        'cover_success'
    );

} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | Rollback
    |--------------------------------------------------------------------------
    */

    $conn->rollback();

    $_SESSION['error'] = $e->getMessage();
}
/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: index.php?tipe={$tipe}&id={$id}");
exit;