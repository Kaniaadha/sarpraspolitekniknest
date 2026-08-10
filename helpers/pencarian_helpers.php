<?php

/**
 * ==========================================================
 * PENCARIAN SARANA & PRASARANA
 * ==========================================================
 *
 * Mencari data dari:
 * 1. Lokasi / Gedung
 * 2. Ruangan
 * 3. Public Space
 * 4. Inventaris
 */


/**
 * ==========================================================
 * SEARCH UTAMA
 * ==========================================================
 */

function cariFasilitas($conn, $keyword)
{
    $keyword = trim($keyword);

    /*
     * Jika keyword kosong,
     * langsung kembalikan array kosong.
     */
    if ($keyword === '') {
        return [];
    }


    /*
     * Amankan keyword untuk query SQL.
     */
    $keyword = mysqli_real_escape_string(
        $conn,
        $keyword
    );

    $search = "%{$keyword}%";


    $hasil = [];


    /*
     * ======================================================
     * 1. GEDUNG / LOKASI
     * ======================================================
     */

    $queryLokasi = mysqli_query(
        $conn,
        "
        SELECT
            id_lokasi,
            kode_lokasi,
            nama_lokasi,
            alamat,
            deskripsi
        FROM lokasi
        WHERE status = 'Aktif'
        AND (
            nama_lokasi LIKE '$search'
            OR kode_lokasi LIKE '$search'
            OR alamat LIKE '$search'
            OR deskripsi LIKE '$search'
        )
        ORDER BY nama_lokasi ASC
        "
    );


    if ($queryLokasi) {

        while ($row = mysqli_fetch_assoc($queryLokasi)) {

            $hasil[] = [

                'jenis' => 'Gedung',

                'id' => $row['id_lokasi'],

                'judul' => $row['nama_lokasi'],

                'kode' => $row['kode_lokasi'],

                'deskripsi' => $row['deskripsi'],

                'info' => $row['alamat'],

                'url' => 'detail_gedung.php?id='
                    . $row['id_lokasi']

            ];

        }

    }


    /*
     * ======================================================
     * 2. RUANGAN
     * ======================================================
     */

    $queryRuangan = mysqli_query(
        $conn,
        "
        SELECT
            id_ruangan,
            kode_ruangan,
            nama_ruangan,
            luas,
            kapasitas,
            deskripsi
        FROM ruangan
        WHERE status = 'Aktif'
        AND (
            nama_ruangan LIKE '$search'
            OR kode_ruangan LIKE '$search'
            OR deskripsi LIKE '$search'
        )
        ORDER BY nama_ruangan ASC
        "
    );


    if ($queryRuangan) {

        while ($row = mysqli_fetch_assoc($queryRuangan)) {

            $hasil[] = [

                'jenis' => 'Ruangan',

                'id' => $row['id_ruangan'],

                'judul' => $row['nama_ruangan'],

                'kode' => $row['kode_ruangan'],

                'deskripsi' => $row['deskripsi'],

                'info' => !empty($row['kapasitas'])
                    ? 'Kapasitas ' . $row['kapasitas'] . ' orang'
                    : '',

                'url' => 'detail_ruangan.php?id='
                    . $row['id_ruangan']

            ];

        }

    }


    /*
     * ======================================================
     * 3. PUBLIC SPACE
     * ======================================================
     */

    $queryPublicSpace = mysqli_query(
        $conn,
        "
        SELECT
            id_public_space,
            kode_public_space,
            nama_public_space,
            luas,
            deskripsi
        FROM public_space
        WHERE status = 'Aktif'
        AND (
            nama_public_space LIKE '$search'
            OR kode_public_space LIKE '$search'
            OR deskripsi LIKE '$search'
        )
        ORDER BY nama_public_space ASC
        "
    );


    if ($queryPublicSpace) {

        while ($row = mysqli_fetch_assoc($queryPublicSpace)) {

            $hasil[] = [

                'jenis' => 'Public Space',

                'id' => $row['id_public_space'],

                'judul' => $row['nama_public_space'],

                'kode' => $row['kode_public_space'],

                'deskripsi' => $row['deskripsi'],

                'info' => !empty($row['luas'])
                    ? 'Luas ' . $row['luas'] . ' m²'
                    : '',

                'url' => 'detail_public_space.php?id='
                    . $row['id_public_space']

            ];

        }

    }


    /*
     * ======================================================
     * 4. INVENTARIS
     * ======================================================
     */

    $queryInventaris = mysqli_query(
        $conn,
        "
        SELECT
            i.id_inventaris,
            i.kode_inventaris,
            i.nama_barang,
            i.merk,
            i.spesifikasi,
            i.jumlah,
            i.kondisi,
            k.nama_kategori
        FROM inventaris i

        LEFT JOIN kategori k
            ON i.id_kategori = k.id_kategori

        WHERE i.status = 'Aktif'

        AND (
            i.nama_barang LIKE '$search'
            OR i.kode_inventaris LIKE '$search'
            OR i.merk LIKE '$search'
            OR i.spesifikasi LIKE '$search'
            OR k.nama_kategori LIKE '$search'
        )

        ORDER BY i.nama_barang ASC
        "
    );


    if ($queryInventaris) {

        while ($row = mysqli_fetch_assoc($queryInventaris)) {

            $hasil[] = [

                'jenis' => 'Inventaris',

                'id' => $row['id_inventaris'],

                'judul' => $row['nama_barang'],

                'kode' => $row['kode_inventaris'],

                'deskripsi' => $row['spesifikasi'],

                'info' => $row['nama_kategori']
                    . ' • '
                    . $row['jumlah']
                    . ' unit',

                'merk' => $row['merk'],

                'kondisi' => $row['kondisi'],

                'url' => 'inventaris.php?id='
                .$row['id_inventaris']

            ];

        }

    }


    /*
     * ======================================================
     * RETURN
     * ======================================================
     */

    return $hasil;
}