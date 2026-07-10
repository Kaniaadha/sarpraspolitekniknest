/*
=================================================
WEBSITE TABLE
SISARPRAS POLITEKNIK NEST

File : 05_website.sql
=================================================
*/

USE db_sisarpras;

CREATE TABLE banner (
    id_banner INT AUTO_INCREMENT PRIMARY KEY,

    judul VARCHAR(150) NOT NULL,

    subjudul VARCHAR(200) NOT NULL,

    deskripsi TEXT,

    urutan INT DEFAULT 1,

    status ENUM('Aktif','Nonaktif') DEFAULT 'Aktif',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

