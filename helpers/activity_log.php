<?php

/*
|--------------------------------------------------------------------------
| Activity Log Helper
|--------------------------------------------------------------------------
*/

if (!function_exists('simpanActivityLog')) {

    function simpanActivityLog(
        mysqli $conn,
        int $id_admin,
        string $aktivitas,
        ?string $tabel_terkait = null,
        ?int $id_data = null
    ): bool {

        $aktivitas = mysqli_real_escape_string($conn, $aktivitas);

        $tabel = $tabel_terkait !== null
            ? "'" . mysqli_real_escape_string($conn, $tabel_terkait) . "'"
            : "NULL";

        $id = $id_data !== null
            ? (int) $id_data
            : "NULL";

        return mysqli_query($conn, "
            INSERT INTO activity_log
            (
                id_admin,
                aktivitas,
                tabel_terkait,
                id_data
            )
            VALUES
            (
                '$id_admin',
                '$aktivitas',
                $tabel,
                $id
            )
        ");
    }

}