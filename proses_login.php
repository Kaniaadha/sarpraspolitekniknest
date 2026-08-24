<?php

session_start();

require_once 'config/database.php';


// ======================================================
// Ambil Data
// ======================================================

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';


// ======================================================
// Validasi Input
// ======================================================

if ($username === '' || $password === '') {

    $_SESSION['error'] = "Username dan password wajib diisi.";

    header("Location: login.php");
    exit;

}


// ======================================================
// Cari Admin
// ======================================================

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM admin
     WHERE username = ?"
);


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $username
);


mysqli_stmt_execute($stmt);


$result = mysqli_stmt_get_result($stmt);


// ======================================================
// Cek Admin
// ======================================================

if (mysqli_num_rows($result) === 1) {

    $admin = mysqli_fetch_assoc($result);


    // ==================================================
    // Cek Password
    // ==================================================

    if (password_verify($password, $admin['password'])) {


        // ==================================================
        // CEK STATUS ADMIN
        // ==================================================

        if ($admin['status'] !== 'Aktif') {

            $_SESSION['error'] =
                "Akun admin sedang nonaktif. Silakan hubungi administrator.";

            header("Location: login.php");
            exit;

        }


        // ==================================================
        // LOGIN BERHASIL
        // ==================================================

        $_SESSION['id_admin'] = $admin['id_admin'];

        $_SESSION['nama_admin'] = $admin['nama_admin'];

        $_SESSION['username'] = $admin['username'];


        header("Location: admin/dashboard.php");
        exit;

    }

}


// ======================================================
// LOGIN GAGAL
// ======================================================

$_SESSION['error'] = "Username atau password salah.";

header("Location: login.php");
exit;

?>