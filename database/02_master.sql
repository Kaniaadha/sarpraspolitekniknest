/*
=================================================
MASTER TABLE
SISARPRAS POLITEKNIK NEST

File : 02_master.sql

Berisi:
1. admin
2. lokasi
3. kategori
4. lantai
5. ruangan
6. public_space
7. inventaris
=================================================
*/

USE db_sisarpras;

CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nama_admin VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    no_hp VARCHAR(20),
    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE lokasi (
    id_lokasi INT AUTO_INCREMENT PRIMARY KEY,
    kode_lokasi VARCHAR(10) NOT NULL UNIQUE,
    nama_lokasi VARCHAR(100) NOT NULL,
    alamat TEXT,
    deskripsi TEXT,
    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    kode_kategori VARCHAR(10) NOT NULL UNIQUE,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

