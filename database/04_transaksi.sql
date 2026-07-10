/*
=================================================
TRANSACTION TABLE
SISARPRAS POLITEKNIK NEST

File : 04_transaksi.sql
=================================================
*/

USE db_sisarpras;

CREATE TABLE peminjaman (
    id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
    kode_peminjaman VARCHAR(15) NOT NULL UNIQUE,

    id_admin INT NULL,

    nama_peminjam VARCHAR(100) NOT NULL,
    nim_nip VARCHAR(30),
    no_hp VARCHAR(20),
    email VARCHAR(100),

    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE NOT NULL,

    tujuan_peminjaman TEXT,

    status ENUM(
        'Menunggu',
        'Disetujui',
        'Ditolak',
        'Dipinjam',
        'Selesai'
    ) DEFAULT 'Menunggu',

    catatan_admin TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE detail_peminjaman (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,

    id_peminjaman INT NOT NULL,
    id_inventaris INT NOT NULL,

    jumlah INT NOT NULL DEFAULT 1,

    kondisi_sebelum ENUM(
        'Baik',
        'Rusak Ringan',
        'Rusak Berat'
    ),

    kondisi_sesudah ENUM(
        'Baik',
        'Rusak Ringan',
        'Rusak Berat'
    ),

    catatan TEXT
);

CREATE TABLE activity_log (
    id_log INT AUTO_INCREMENT PRIMARY KEY,

    id_admin INT NOT NULL,

    aktivitas VARCHAR(255) NOT NULL,

    tabel_terkait VARCHAR(100),

    id_data INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

