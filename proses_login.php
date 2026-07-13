<?php
session_start();

require_once 'config/database.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

if (mysqli_num_rows($query) == 1) {

    $admin = mysqli_fetch_assoc($query);

    if (password_verify($password, $admin['password'])) {

        $_SESSION['id_admin'] = $admin['id_admin'];
        $_SESSION['nama_admin'] = $admin['nama_admin'];
        $_SESSION['username'] = $admin['username'];

        header("Location: admin/dashboard.php");
        exit;

    } else {

        echo "Password salah.";

    }

} else {

    echo "Username tidak ditemukan.";

}