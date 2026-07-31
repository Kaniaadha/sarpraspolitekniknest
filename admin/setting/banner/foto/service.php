<?php

require_once "../../../../config/database.php";

/**
 * Ambil data banner berdasarkan ID
 */
function getBanner($id_banner)
{
    global $conn;

    $id_banner = (int) $id_banner;

    $query = mysqli_query($conn, "
        SELECT *
        FROM banner
        WHERE id_banner = '$id_banner'
        LIMIT 1
    ");

    return mysqli_fetch_assoc($query);
}

/**
 * Ambil semua foto banner
 */
function getBannerPhotos($id_banner)
{
    global $conn;

    $id_banner = (int) $id_banner;

    return mysqli_query($conn, "
        SELECT *
        FROM foto_banner
        WHERE id_banner = '$id_banner'
        ORDER BY is_cover DESC, urutan ASC, id_foto_banner ASC
    ");
}

/**
 * Hitung jumlah foto banner
 */
function countBannerPhotos($id_banner)
{
    global $conn;

    $id_banner = (int) $id_banner;

    $query = mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM foto_banner
        WHERE id_banner = '$id_banner'
    ");

    $row = mysqli_fetch_assoc($query);

    return (int) $row['total'];
}

/**
 * Ambil cover banner
 */
function getBannerCover($id_banner)
{
    global $conn;

    $id_banner = (int) $id_banner;

    $query = mysqli_query($conn, "
        SELECT *
        FROM foto_banner
        WHERE id_banner = '$id_banner'
        AND is_cover = 1
        LIMIT 1
    ");

    return mysqli_fetch_assoc($query);
}

/**
 * Ambil satu foto berdasarkan ID
 */
function getPhoto($id_foto_banner)
{
    global $conn;

    $id_foto_banner = (int) $id_foto_banner;

    $query = mysqli_query($conn, "
        SELECT *
        FROM foto_banner
        WHERE id_foto_banner = '$id_foto_banner'
        LIMIT 1
    ");

    return mysqli_fetch_assoc($query);
}