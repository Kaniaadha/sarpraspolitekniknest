<?php

require_once "config.php";

/**
 * Membuat nama file unik
 */
function generateFileName($extension)
{
    return uniqid('banner_', true) . '.' . strtolower($extension);
}

/**
 * Mengambil ekstensi file
 */
function getExtension($fileName)
{
    return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
}

/**
 * Validasi ekstensi file
 */
function isAllowedExtension($extension)
{
    return in_array($extension, ALLOWED_EXTENSION);
}

/**
 * Validasi ukuran file
 */
function isAllowedSize($size)
{
    return $size <= MAX_FILE_SIZE;
}

/**
 * Mendapatkan path upload banner
 */
function uploadPath($fileName = '')
{
    return UPLOAD_PATH . $fileName;
}

/**
 * Menghapus file jika ada
 */
function deleteFile($fileName)
{
    $path = uploadPath($fileName);

    if (is_file($path)) {
        return @unlink($path);
    }

    return false;
}

/**
 * Mengecek apakah file gambar valid
 */
function isImage($tmpName)
{
    return getimagesize($tmpName) !== false;
}

/**
 * Merapikan urutan foto banner
 */
function reorderBannerGallery($conn, $idBanner)
{
    $idBanner = (int) $idBanner;

    $query = mysqli_query($conn, "
        SELECT id_foto_banner
        FROM foto_banner
        WHERE id_banner = '$idBanner'
        ORDER BY urutan ASC, id_foto_banner ASC
    ");

    $urutan = 1;

    while ($row = mysqli_fetch_assoc($query)) {

        $idFoto = (int) $row['id_foto_banner'];

        mysqli_query($conn, "
            UPDATE foto_banner
            SET
                urutan = '$urutan',
                updated_at = NOW()
            WHERE id_foto_banner = '$idFoto'
        ");

        $urutan++;
    }

    return true;
}
/**
 * Menjadikan foto pertama sebagai cover
 */
function setFirstCover($conn, $idBanner)
{
    $idBanner = (int) $idBanner;

    $query = mysqli_query($conn, "
        SELECT id_foto_banner
        FROM foto_banner
        WHERE id_banner = '$idBanner'
        ORDER BY urutan ASC, id_foto_banner ASC
        LIMIT 1
    ");

    if ($row = mysqli_fetch_assoc($query)) {

        $idFoto = (int) $row['id_foto_banner'];

        mysqli_query($conn, "
            UPDATE foto_banner
            SET
                is_cover = 1,
                updated_at = NOW()
            WHERE id_foto_banner = '$idFoto'
        ");
    }

    return true;
}