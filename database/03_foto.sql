/*
=================================================
PHOTO TABLE
SISARPRAS POLITEKNIK NEST

File : 03_foto.sql
=================================================
*/

USE db_sisarpras;

CREATE TABLE foto_lokasi (
    id_foto_lokasi INT AUTO_INCREMENT PRIMARY KEY,
    id_lokasi INT NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE foto_ruangan (
    id_foto_ruangan INT AUTO_INCREMENT PRIMARY KEY,
    id_ruangan INT NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE foto_public_space (
    id_foto_public_space INT AUTO_INCREMENT PRIMARY KEY,
    id_public_space INT NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE foto_inventaris (
    id_foto_inventaris INT AUTO_INCREMENT PRIMARY KEY,
    id_inventaris INT NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
