<?php
session_start();

require_once "../../../config/database.php";

// Ambil data dari form
$nama_admin = trim($_POST['nama_admin']);
$username   = trim($_POST['username']);
$email      = trim($_POST['email']);
$no_hp      = trim($_POST['no_hp']);
$password   = $_POST['password'];
$konfirmasi = $_POST['konfirmasi_password'];
$status     = $_POST['status'];

// Simpan input sementara
$_SESSION['old'] = [
    'nama_admin' => $nama_admin,
    'username'   => $username,
    'email'      => $email,
    'no_hp'      => $no_hp,
    'status'     => $status
];

// ==============================
// Validasi field kosong
// ==============================
if (
    empty($nama_admin) ||
    empty($username) ||
    empty($email) ||
    empty($no_hp) ||
    empty($password) ||
    empty($konfirmasi) ||
    empty($status)
) {
    $_SESSION['error'] = "Semua field wajib diisi.";
    header("Location: tambah.php");
    exit;
}

// ==============================
// Username harus unik
// ==============================
$cekUsername = mysqli_query($conn, "
    SELECT id_admin
    FROM admin
    WHERE username='$username'
");

if (mysqli_num_rows($cekUsername) > 0) {
    $_SESSION['error'] = "Username sudah digunakan.";
    header("Location: tambah.php");
    exit;
}

// ==============================
// Email harus unik
// ==============================
$cekEmail = mysqli_query($conn, "
    SELECT id_admin
    FROM admin
    WHERE email='$email'
");

if (mysqli_num_rows($cekEmail) > 0) {
    $_SESSION['error'] = "Email sudah digunakan.";
    header("Location: tambah.php");
    exit;
}

// ==============================
// Password & Konfirmasi
// ==============================
if ($password != $konfirmasi) {
    $_SESSION['error'] = "Konfirmasi password tidak sesuai.";
    header("Location: tambah.php");
    exit;
}

// ==============================
// Validasi Password
// Minimal 8 karakter
// Huruf besar
// Huruf kecil
// Angka
// ==============================
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {

    $_SESSION['error'] =
    "Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, serta angka.";

    header("Location: tambah.php");
    exit;
}

// ==============================
// Enkripsi Password
// ==============================
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// ==============================
// Simpan Database
// ==============================
$query = mysqli_query($conn, "
    INSERT INTO admin
    (
        nama_admin,
        username,
        password,
        email,
        no_hp,
        status,
        created_at,
        updated_at
    )
    VALUES
    (
        '$nama_admin',
        '$username',
        '$passwordHash',
        '$email',
        '$no_hp',
        '$status',
        NOW(),
        NOW()
    )
");

// ==============================
// Hasil
// ==============================
if ($query) {

    $idAdminBaru = mysqli_insert_id($conn);

    mysqli_query($conn, "
        INSERT INTO activity_log
        (
            id_admin,
            aktivitas,
            tabel_terkait,
            id_data
        )
        VALUES
        (
            '{$_SESSION['id_admin']}',
            'Menambah Admin',
            'admin',
            '$idAdminBaru'
        )
    ");

    $_SESSION['success'] = "Admin berhasil ditambahkan.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Admin gagal ditambahkan.";

    header("Location: tambah.php");
    exit;

}