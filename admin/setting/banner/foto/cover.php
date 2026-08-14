<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Load Dependencies
|--------------------------------------------------------------------------
*/

require_once "../../../../config/database.php";
require_once "../../../../helpers/activity_log.php";
require_once "service.php";
require_once "config.php";
require_once "helper.php";

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

$idBanner = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

$idFoto = filter_input(
    INPUT_GET,
    'foto',
    FILTER_VALIDATE_INT
);

if (!$idBanner || !$idFoto) {

    $_SESSION['error'] = "Data tidak valid.";

    header("Location: ../index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Validasi Banner
|--------------------------------------------------------------------------
*/

$banner = getBanner($idBanner);

if (!$banner) {

    $_SESSION['error'] = "Data tidak ditemukan.";

    header("Location: ../index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Validasi Foto
|--------------------------------------------------------------------------
*/

$photo = getPhoto($idFoto);

if (
    !$photo ||
    (int)$photo['id_banner'] !== (int)$idBanner
) {

    $_SESSION['error'] = "Data tidak ditemukan.";

    header("Location: index.php?id={$idBanner}");
    exit;
}

/*
|--------------------------------------------------------------------------
| Database Transaction
|--------------------------------------------------------------------------
*/

mysqli_begin_transaction($conn);

try {

    /*
    |--------------------------------------------------------------------------
    | Reset Cover Lama
    |--------------------------------------------------------------------------
    */

    $idBanner = (int) $idBanner;

    $query = mysqli_query($conn, "
        UPDATE foto_banner
        SET
            is_cover = 0,
            updated_at = NOW()
        WHERE id_banner = '{$idBanner}'
    ");

    if (!$query) {
        throw new Exception('Gagal memperbarui cover.');
    }

    /*
    |--------------------------------------------------------------------------
    | Set Cover Baru
    |--------------------------------------------------------------------------
    */

    $idFoto = (int) $idFoto;

    $query = mysqli_query($conn, "
        UPDATE foto_banner
        SET
            is_cover = 1,
            updated_at = NOW()
        WHERE id_foto_banner = '{$idFoto}'
        LIMIT 1
    ");

    if (!$query) {
        throw new Exception('Gagal memperbarui cover.');
    }

    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    mysqli_commit($conn);

    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Menjadikan Foto Banner sebagai Cover",
        "foto_banner",
        $idFoto
    );

    $_SESSION['success'] = "Cover banner berhasil diperbarui.";

} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | Rollback
    |--------------------------------------------------------------------------
    */

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();
}

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: index.php?id={$idBanner}");
exit;