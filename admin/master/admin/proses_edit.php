<?php
session_start();

require_once "../../../config/database.php";

// ==============================
// Ambil Data
// ==============================

$id_admin    = (int) $_POST['id_admin'];
$nama_admin  = trim($_POST['nama_admin']);
$username    = trim($_POST['username']);
$email       = trim($_POST['email']);
$no_hp       = trim($_POST['no_hp']);
$status      = $_POST['status'];

$password        = $_POST['password'] ?? "";
$konfirmasi      = $_POST['konfirmasi_password'] ?? "";
$ubah_password   = isset($_POST['ubah_password']);

// ==============================
// Simpan input sementara
// ==============================

$_SESSION['old'] = [
    'nama_admin' => $nama_admin,
    'username'   => $username,
    'email'      => $email,
    'no_hp'      => $no_hp,
    'status'     => $status
];

// ==============================
// Validasi Field Kosong
// ==============================

if (
    empty($nama_admin) ||
    empty($username) ||
    empty($email) ||
    empty($no_hp) ||
    empty($status)
) {

    $_SESSION['error'] = "Semua field wajib diisi.";

    header("Location: edit.php?id=$id_admin");
    exit;
}

// ==============================
// Username Unik
// ==============================

$cekUsername = mysqli_query($conn, "
    SELECT id_admin
    FROM admin
    WHERE username = '$username'
    AND id_admin != '$id_admin'
");

if (mysqli_num_rows($cekUsername) > 0) {

    $_SESSION['error'] = "Username sudah digunakan.";

    header("Location: edit.php?id=$id_admin");
    exit;
}

// ==============================
// Email Unik
// ==============================

$cekEmail = mysqli_query($conn, "
    SELECT id_admin
    FROM admin
    WHERE email = '$email'
    AND id_admin != '$id_admin'
");

if (mysqli_num_rows($cekEmail) > 0) {

    $_SESSION['error'] = "Email sudah digunakan.";

    header("Location: edit.php?id=$id_admin");
    exit;
}

// ==============================
// Jika Password Diubah
// ==============================

if ($ubah_password) {

    if (empty($password) || empty($konfirmasi)) {

        $_SESSION['error'] = "Password dan Konfirmasi Password wajib diisi.";

        header("Location: edit.php?id=$id_admin");
        exit;
    }

    if ($password != $konfirmasi) {

        $_SESSION['error'] = "Konfirmasi Password tidak sesuai.";

        header("Location: edit.php?id=$id_admin");
        exit;
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {

        $_SESSION['error'] = "Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, serta angka.";

        header("Location: edit.php?id=$id_admin");
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $query = mysqli_query($conn, "
        UPDATE admin
        SET
            nama_admin = '$nama_admin',
            username = '$username',
            password = '$passwordHash',
            email = '$email',
            no_hp = '$no_hp',
            status = '$status',
            updated_at = NOW()
        WHERE id_admin = '$id_admin'
    ");

} else {

    // ==============================
    // Tanpa Mengubah Password
    // ==============================

    $query = mysqli_query($conn, "
        UPDATE admin
        SET
            nama_admin = '$nama_admin',
            username = '$username',
            email = '$email',
            no_hp = '$no_hp',
            status = '$status',
            updated_at = NOW()
        WHERE id_admin = '$id_admin'
    ");

}

// ==============================
// Hasil
// ==============================

if ($query) {

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
            'Mengubah Admin',
            'admin',
            '$id_admin'
        )
    ");

    unset($_SESSION['old']);

    $_SESSION['success'] = "Data admin berhasil diperbarui.";

    header("Location: index.php");
    exit;

} else {

    $_SESSION['error'] = "Data admin gagal diperbarui.";

    header("Location: edit.php?id=$id_admin");
    exit;
}