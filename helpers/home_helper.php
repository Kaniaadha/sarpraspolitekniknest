<?php

/**
 * ==========================================================
 * BANNER
 * ==========================================================
 */

function getActiveBanner($conn)
{
    $sql = "SELECT * FROM banner
            WHERE status='aktif'
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);
}

function getBannerPhotos($conn, $idBanner)
{
    $sql = "SELECT *
            FROM foto_banner
            WHERE id_banner='$idBanner'
            ORDER BY urutan ASC";

    $result = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

/**
 * ==========================================================
 * STATISTIK
 * ==========================================================
 */

function getStatistik($conn)
{
    return [

        'lokasi' => mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) AS total FROM lokasi WHERE status='aktif'")
        )['total'],

        'ruangan' => mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) AS total FROM ruangan WHERE status='aktif'")
        )['total'],

        'public_space' => mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) AS total FROM public_space WHERE status='aktif'")
        )['total'],

        'inventaris' => mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT COUNT(*) AS total FROM inventaris")
        )['total']

    ];
}

/**
 * ==========================================================
 * GEDUNG / LOKASI
 * ==========================================================
 */

function getLatestLokasi($conn, $limit = 4)
{
    $sql = "
        SELECT
            l.*,

            fl.nama_file,

            (
                SELECT COUNT(*)
                FROM lantai lt
                WHERE lt.id_lokasi = l.id_lokasi
            ) AS jumlah_lantai,

            (
                SELECT COUNT(*)
                FROM ruangan r
                INNER JOIN lantai lt
                    ON lt.id_lantai = r.id_lantai
                WHERE lt.id_lokasi = l.id_lokasi
            ) AS jumlah_ruangan

        FROM lokasi l

        LEFT JOIN foto_lokasi fl
            ON l.id_lokasi = fl.id_lokasi
            AND fl.is_cover = 1

        WHERE l.status='aktif'

        ORDER BY l.id_lokasi DESC

        LIMIT $limit
    ";

    $result = mysqli_query($conn, $sql);

    $data = [];

    while($row = mysqli_fetch_assoc($result)){

        $data[] = $row;

    }

    return $data;
}
/**
 * ==========================================================
 * PUBLIC SPACE
 * ==========================================================
 */

function getLatestPublicSpace($conn, $limit = 4)
{
    $sql = "
        SELECT
            ps.*,
            fps.nama_file
        FROM public_space ps
        LEFT JOIN foto_public_space fps
            ON ps.id_public_space = fps.id_public_space
            AND fps.is_cover = 1
        WHERE ps.status='aktif'
        ORDER BY ps.id_public_space DESC
        LIMIT $limit
    ";

    $result = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

/**
 * ==========================================================
 * INVENTARIS
 * ==========================================================
 */

function getLatestInventaris($conn, $limit = 4)
{
    $sql = "
        SELECT *
        FROM inventaris
        ORDER BY id_inventaris DESC
        LIMIT $limit
    ";

    $result = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}