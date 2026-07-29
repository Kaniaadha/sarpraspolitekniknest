<?php

/**
 * ==========================================================
 * Gallery Service
 * Sistem Informasi Sarana dan Prasarana (SISARPRAS)
 * Politeknik NEST
 * ----------------------------------------------------------
 * File ini berfungsi sebagai pusat konfigurasi seluruh modul
 * gallery.
 *
 * Digunakan oleh:
 * - index.php
 * - upload.php
 * - cover.php
 * - hapus.php
 *
 * Author  : SISARPRAS Team
 * Version : 2.0
 * ==========================================================
 */

if (!isset($_GET['tipe']) || empty($_GET['tipe'])) {
    exit('Modul tidak ditemukan.');
}

$tipe = strtolower(trim($_GET['tipe']));

/**
 * ==========================================================
 * Mapping Modul Gallery
 * ==========================================================
 */

$galleryModules = [

    'lokasi' => [
        'module_name'   => 'Lokasi',
        'table'         => 'foto_lokasi',
        'primary_key'   => 'id_foto_lokasi',
        'foreign_key'   => 'id_lokasi',
        'upload_folder' => 'lokasi'
    ],

    'ruangan' => [
        'module_name'   => 'Ruangan',
        'table'         => 'foto_ruangan',
        'primary_key'   => 'id_foto_ruangan',
        'foreign_key'   => 'id_ruangan',
        'upload_folder' => 'ruangan'
    ],

    'public_space' => [
        'module_name'   => 'Public Space',
        'table'         => 'foto_public_space',
        'primary_key'   => 'id_foto_public_space',
        'foreign_key'   => 'id_public_space',
        'upload_folder' => 'public_space'
    ]

];

/**
 * ==========================================================
 * Validasi Modul
 * ==========================================================
 */

if (!array_key_exists($tipe, $galleryModules)) {
    exit('Modul gallery tidak valid.');
}

/**
 * ==========================================================
 * Ambil Konfigurasi Modul
 * ==========================================================
 */

$moduleConfig = $galleryModules[$tipe];
$module = $tipe;
/**
 * ==========================================================
 * Variable Shortcut
 * ==========================================================
 */

$moduleName   = $moduleConfig['module_name'];
$table        = $moduleConfig['table'];
$primaryKey   = $moduleConfig['primary_key'];
$foreignKey   = $moduleConfig['foreign_key'];
$uploadFolder = $moduleConfig['upload_folder'];

