<?php

/**
 * ==========================================================
 * Gallery Configuration
 * Sistem Informasi Sarana dan Prasarana (SISARPRAS)
 * Politeknik NEST
 * ----------------------------------------------------------
 * Pusat konfigurasi modul gallery.
 *
 * Digunakan oleh:
 * - helper.php
 * - upload.php
 * - cover.php
 * - hapus.php
 * - index.php
 *
 * File ini TIDAK boleh berisi:
 * - Query Database
 * - Function
 * - Proses Upload
 *
 * Author  : SISARPRAS Team
 * Version : 2.0
 * ==========================================================
 */

$config = [

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    */
    'upload' => [

        // Maksimal jumlah foto setiap data
        'max_photo' => 10,

        // Maksimal ukuran file (5 MB)
        'max_size' => 5 * 1024 * 1024,

        // Format file yang diperbolehkan
        'allowed_extensions' => [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ],

        // MIME Type yang diperbolehkan
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/webp'
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Image Configuration
    |--------------------------------------------------------------------------
    */
    'image' => [

        // Kualitas gambar jika nanti menggunakan kompresi
        'quality' => 90,

        // Nama default cover (opsional untuk pengembangan)
        'default_cover_name' => 'cover'

    ],

    /*
    |--------------------------------------------------------------------------
    | Gallery Configuration
    |--------------------------------------------------------------------------
    */
    'gallery' => [

        // Upload pertama otomatis menjadi cover
        'auto_cover_first_upload' => true,

        // Cover tidak boleh dihapus
        'prevent_delete_cover' => true,

        // Gallery diurutkan berdasarkan field urutan
        'order_by' => 'urutan',

        // Arah sorting
        'order_direction' => 'ASC'

    ],

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */
    'messages' => [

        // Success
        'upload_success' => 'Foto berhasil diupload.',
        'delete_success' => 'Foto berhasil dihapus.',
        'cover_success' => 'Cover berhasil diperbarui.',

        // Error
        'required' => 'Silakan pilih foto terlebih dahulu.',
        'invalid_extension' => 'Format file tidak didukung.',
        'invalid_mime' => 'File bukan gambar yang valid.',
        'max_size' => 'Ukuran foto maksimal 5 MB.',
        'max_photo' => 'Jumlah foto telah mencapai batas maksimal.',
        'upload_failed' => 'Upload foto gagal.',
        'delete_failed' => 'Gagal menghapus foto.',
        'cover_delete' => 'Foto cover tidak dapat dihapus.',
        'module_invalid' => 'Modul gallery tidak valid.',
        'photo_not_found' => 'Foto tidak ditemukan.'

    ]

];