<?php

declare(strict_types=1);

/**
 * ==========================================================
 * Gallery Helper
 * Sistem Informasi Sarana dan Prasarana (SISARPRAS)
 * Politeknik NEST
 * ----------------------------------------------------------
 * Kumpulan helper function reusable untuk modul gallery.
 *
 * Digunakan oleh:
 * - upload.php
 * - cover.php
 * - hapus.php
 * - index.php
 *
 * Author  : SISARPRAS Team
 * Version : 2.0
 * ==========================================================
 */


/**
 * Generate unique filename.
 *
 * Contoh:
 * IMG_20260720153010_a1b2c3d4e5f6g7h8.webp
 */
function generateUniqueFileName(string $extension): string
{
    return sprintf(
        'IMG_%s_%s.%s',
        date('YmdHis'),
        bin2hex(random_bytes(8)),
        strtolower($extension)
    );
}


/**
 * Mengambil extension file.
 */
function getFileExtension(string $filename): string
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}


/**
 * Memastikan folder upload tersedia.
 */
function ensureUploadDirectory(string $directory): bool
{
    if (is_dir($directory)) {
        return true;
    }

    return mkdir($directory, 0755, true);
}


/**
 * Validasi file gambar.
 *
 * @return true|string
 */
function validateImage(array $file, array $config)
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return 'Upload file tidak valid.';
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return $config['messages']['upload_failed'];
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        return $config['messages']['upload_failed'];
    }

    if ($file['size'] <= 0) {
        return $config['messages']['required'];
    }

    if ($file['size'] > $config['upload']['max_size']) {
        return $config['messages']['max_size'];
    }

    $extension = getFileExtension($file['name']);

    if (!in_array(
        $extension,
        $config['upload']['allowed_extensions'],
        true
    )) {
        return $config['messages']['invalid_extension'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    if ($finfo === false) {
        return $config['messages']['upload_failed'];
    }

    $mime = finfo_file($finfo, $file['tmp_name']);

    finfo_close($finfo);

    if (!in_array(
        $mime,
        $config['upload']['allowed_mime_types'],
        true
    )) {
        return $config['messages']['invalid_mime'];
    }

    return true;
}


/**
 * Menghitung jumlah foto.
 */
function getPhotoCount(
    mysqli $conn,
    string $table,
    string $foreignKey,
    int $id
): int {

    $sql = "
        SELECT COUNT(*) AS total
        FROM {$table}
        WHERE {$foreignKey} = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return 0;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    return (int) ($result['total'] ?? 0);
}


/**
 * Mengambil urutan berikutnya.
 */
function getNextOrder(
    mysqli $conn,
    string $table,
    string $foreignKey,
    int $id
): int {

    $sql = "
        SELECT MAX(urutan) AS urutan
        FROM {$table}
        WHERE {$foreignKey} = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return 1;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    return ((int) ($result['urutan'] ?? 0)) + 1;
}


/**
 * Mengambil foto cover.
 */
function getCoverPhoto(
    mysqli $conn,
    string $table,
    string $foreignKey,
    int $id
): ?array {

    $sql = "
        SELECT *
        FROM {$table}
        WHERE {$foreignKey} = ?
        AND is_cover = 1
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $photo = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    return $photo ?: null;
}


/**
 * Menghapus file fisik.
 */
function deletePhysicalFile(string $path): bool
{
    if (!file_exists($path)) {
        return true;
    }

    return @unlink($path);
}


/**
 * Mengambil pesan sukses.
 */
function uploadSuccess(array $config, string $key): string
{
    return $config['messages'][$key] ?? 'Berhasil.';
}


/**
 * Mengambil pesan error.
 */
function uploadError(array $config, string $key): string
{
    return $config['messages'][$key] ?? 'Terjadi kesalahan.';
}

/**
 * Merapikan kembali urutan gallery.
 */
function reorderGallery(
    mysqli $conn,
    string $table,
    string $primaryKey,
    string $foreignKey,
    int $id
): bool {

    $sql = "
        SELECT {$primaryKey}
        FROM {$table}
        WHERE {$foreignKey} = ?
        ORDER BY urutan ASC, {$primaryKey} ASC
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    $urutan = 1;

    while ($row = $result->fetch_assoc()) {

        $update = $conn->prepare("
            UPDATE {$table}
            SET urutan = ?
            WHERE {$primaryKey} = ?
        ");

        if (!$update) {
            $stmt->close();
            return false;
        }

        $update->bind_param(
            "ii",
            $urutan,
            $row[$primaryKey]
        );

        if (!$update->execute()) {
            $update->close();
            $stmt->close();
            return false;
        }

        $update->close();

        $urutan++;
    }

    $stmt->close();

    return true;
}