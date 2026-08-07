<?php

/* ==========================================================
| GEDUNG HELPER
| Halaman : user/gedung.php
========================================================== */


/* ==========================================================
| DAFTAR GEDUNG
========================================================== */

function getAllGedung($conn)
{
    $sql = "
        SELECT
            l.*,
            fl.nama_file,

            (
                SELECT COUNT(*)
                FROM lantai lt
                WHERE lt.id_lokasi = l.id_lokasi
                AND lt.status = 'Aktif'
            ) AS jumlah_lantai,

            (
                SELECT COUNT(*)
                FROM ruangan r
                INNER JOIN lantai lt
                    ON lt.id_lantai = r.id_lantai
                WHERE lt.id_lokasi = l.id_lokasi
                AND r.status = 'Aktif'
            ) AS jumlah_ruangan,

            (
                SELECT COALESCE(SUM(i.jumlah),0)

                FROM inventaris i

                LEFT JOIN ruangan r
                    ON r.id_ruangan = i.id_ruangan

                LEFT JOIN public_space ps
                    ON ps.id_public_space = i.id_public_space

                LEFT JOIN lantai lr
                    ON lr.id_lantai = r.id_lantai

                LEFT JOIN lantai lp
                    ON lp.id_lantai = ps.id_lantai

                WHERE
                    (
                        lr.id_lokasi = l.id_lokasi
                        OR
                        lp.id_lokasi = l.id_lokasi
                    )
                AND i.status='Aktif'

            ) AS jumlah_inventaris

        FROM lokasi l

        LEFT JOIN foto_lokasi fl
            ON fl.id_lokasi = l.id_lokasi
            AND fl.is_cover = 1

        WHERE l.status='Aktif'

        ORDER BY l.kode_lokasi ASC
    ";

    $query = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    return $data;
}


/* ==========================================================
| STATISTIK
========================================================== */

function countGedung($conn)
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM lokasi
        WHERE status='Aktif'
    ";

    $query = mysqli_query($conn, $sql);
    $data  = mysqli_fetch_assoc($query);

    return (int)$data['total'];
}


function countLantai($conn)
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM lantai
        WHERE status='Aktif'
    ";

    $query = mysqli_query($conn, $sql);
    $data  = mysqli_fetch_assoc($query);

    return (int)$data['total'];
}


function countRuangan($conn)
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM ruangan
        WHERE status='Aktif'
    ";

    $query = mysqli_query($conn, $sql);
    $data  = mysqli_fetch_assoc($query);

    return (int)$data['total'];
}


function countInventaris($conn)
{
    $sql = "
        SELECT COALESCE(SUM(jumlah),0) AS total
        FROM inventaris
        WHERE status='Aktif'
    ";

    $query = mysqli_query($conn, $sql);
    $data  = mysqli_fetch_assoc($query);

    return (int)$data['total'];
}
/* ==========================================================
| DETAIL GEDUNG
========================================================== */

function getGedungById($conn, $idLokasi)
{
    $idLokasi = (int)$idLokasi;

    $sql = "
        SELECT *
        FROM lokasi
        WHERE id_lokasi = $idLokasi
        LIMIT 1
    ";

    $query = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($query);
}
/* ==========================================================
| FOTO COVER
========================================================== */

function getFotoCoverGedung($conn, $idLokasi)
{
    $idLokasi = (int)$idLokasi;

    $sql = "
        SELECT *
        FROM foto_lokasi
        WHERE id_lokasi = $idLokasi
        AND is_cover = 1
        LIMIT 1
    ";

    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query) > 0){
        return mysqli_fetch_assoc($query);
    }

    return null;
}
/* ==========================================================
| GALLERY GEDUNG
========================================================== */

function getGalleryGedung($conn, $idLokasi)
{
    $idLokasi = (int)$idLokasi;

    $sql = "
        SELECT *
        FROM foto_lokasi
        WHERE id_lokasi = $idLokasi
        ORDER BY urutan ASC
    ";

    $query = mysqli_query($conn,$sql);

    $data = [];

    while($row = mysqli_fetch_assoc($query)){
        $data[] = $row;
    }

    return $data;
}

/* ==========================================================
| STATISTIK GEDUNG
========================================================== */

function getJumlahLantai($conn,$idLokasi)
{
    $idLokasi = (int)$idLokasi;

    $sql="
        SELECT COUNT(*) total
        FROM lantai
        WHERE id_lokasi=$idLokasi
        AND status='Aktif'
    ";

    return mysqli_fetch_assoc(mysqli_query($conn,$sql))['total'];
}


function getJumlahRuangan($conn,$idLokasi)
{
    $idLokasi=(int)$idLokasi;

    $sql="
        SELECT COUNT(*) total
        FROM ruangan r
        JOIN lantai l
        ON r.id_lantai=l.id_lantai
        WHERE l.id_lokasi=$idLokasi
        AND r.status='Aktif'
    ";

    return mysqli_fetch_assoc(mysqli_query($conn,$sql))['total'];
}


function getJumlahPublicSpace($conn,$idLokasi)
{
    $idLokasi=(int)$idLokasi;

    $sql="
        SELECT COUNT(*) total
        FROM public_space p
        JOIN lantai l
        ON p.id_lantai=l.id_lantai
        WHERE l.id_lokasi=$idLokasi
        AND p.status='Aktif'
    ";

    return mysqli_fetch_assoc(mysqli_query($conn,$sql))['total'];
}


function getJumlahInventaris($conn,$idLokasi)
{
    $idLokasi=(int)$idLokasi;

    $sql="
        SELECT COALESCE(SUM(i.jumlah),0) total

        FROM inventaris i

        LEFT JOIN ruangan r
        ON i.id_ruangan=r.id_ruangan

        LEFT JOIN public_space p
        ON i.id_public_space=p.id_public_space

        LEFT JOIN lantai lr
        ON r.id_lantai=lr.id_lantai

        LEFT JOIN lantai lp
        ON p.id_lantai=lp.id_lantai

        WHERE
        (
            lr.id_lokasi=$idLokasi
            OR
            lp.id_lokasi=$idLokasi
        )

        AND i.status='Aktif'
    ";

    return mysqli_fetch_assoc(mysqli_query($conn,$sql))['total'];
}

/* ==========================================================
| LANTAI GEDUNG
========================================================== */

function getLantaiGedung($conn, $idLokasi)
{
    $idLokasi = (int)$idLokasi;

    $sql = "
        SELECT *
        FROM lantai
        WHERE id_lokasi = $idLokasi
        AND status = 'Aktif'
        ORDER BY nomor_lantai ASC
    ";

    $query = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    return $data;
}


/* ==========================================================
| RUANGAN PER LANTAI
========================================================== */

function getRuanganPerLantai($conn, $idLantai)
{
    $idLantai = (int)$idLantai;

    $sql = "
        SELECT *
        FROM ruangan
        WHERE id_lantai = $idLantai
        AND status = 'Aktif'
        ORDER BY nama_ruangan ASC
    ";

    $query = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    return $data;
}


/* ==========================================================
| PUBLIC SPACE PER LANTAI
========================================================== */

function getPublicSpacePerLantai($conn, $idLantai)
{
    $idLantai = (int)$idLantai;

    $sql = "
        SELECT *
        FROM public_space
        WHERE id_lantai = $idLantai
        AND status = 'Aktif'
        ORDER BY nama_public_space ASC
    ";

    $query = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    return $data;
}


/* ==========================================================
| INVENTARIS RUANGAN
========================================================== */

function getInventarisRuangan($conn, $idRuangan)
{
    $idRuangan = (int)$idRuangan;

    $sql = "
        SELECT *
        FROM inventaris
        WHERE id_ruangan = $idRuangan
        AND status = 'Aktif'
        ORDER BY nama_barang ASC
    ";

    $query = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    return $data;
}


/* ==========================================================
| INVENTARIS PUBLIC SPACE
========================================================== */

function getInventarisPublicSpace($conn, $idPublicSpace)
{
    $idPublicSpace = (int)$idPublicSpace;

    $sql = "
        SELECT *
        FROM inventaris
        WHERE id_public_space = $idPublicSpace
        AND status = 'Aktif'
        ORDER BY nama_barang ASC
    ";

    $query = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    return $data;
}