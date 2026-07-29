<?php

function generateKode($conn, $table, $field, $prefix)
{
    $query = "SELECT MAX($field) AS kode_terakhir FROM $table";
    $result = mysqli_query($conn, $query);

    $nomor = 1;

    if ($result && mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        if (!empty($row['kode_terakhir'])) {

            $nomor = (int) substr($row['kode_terakhir'], strlen($prefix));
            $nomor++;

        }

    }

    return $prefix . str_pad($nomor, 3, "0", STR_PAD_LEFT);
}