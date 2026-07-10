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

CREATE TABLE lantai (
    id_lantai INT AUTO_INCREMENT PRIMARY KEY,
    id_lokasi INT NOT NULL,
    kode_lantai VARCHAR(10) NOT NULL UNIQUE,
    nama_lantai VARCHAR(100) NOT NULL,
    nomor_lantai INT NOT NULL,
    deskripsi TEXT,
    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE ruangan (
    id_ruangan INT AUTO_INCREMENT PRIMARY KEY,
    id_lantai INT NOT NULL,
    kode_ruangan VARCHAR(10) NOT NULL UNIQUE,
    nama_ruangan VARCHAR(100) NOT NULL,
    luas DECIMAL(8,2),
    kapasitas INT,
    deskripsi TEXT,
    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE public_space (
    id_public_space INT AUTO_INCREMENT PRIMARY KEY,
    id_lokasi INT NOT NULL,
    kode_public_space VARCHAR(10) NOT NULL UNIQUE,
    nama_public_space VARCHAR(100) NOT NULL,
    luas DECIMAL(8,2),
    deskripsi TEXT,
    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE inventaris (
    id_inventaris INT AUTO_INCREMENT PRIMARY KEY,
    kode_inventaris VARCHAR(10) NOT NULL UNIQUE,
    id_kategori INT NOT NULL,
    id_ruangan INT NULL,
    id_public_space INT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    merk VARCHAR(100),
    spesifikasi TEXT,
    jumlah INT NOT NULL DEFAULT 1,
    kondisi ENUM('Baik','Rusak Ringan','Rusak Berat') DEFAULT 'Baik',
    tahun_perolehan YEAR,
    sumber_perolehan VARCHAR(100),
    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

