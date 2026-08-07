<?php
session_start();

require_once "../../config/database.php";
require_once "../../helpers/activity_log.php";

// Validasi request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: admin.php");
    exit;
}

// Mengambil data dari form
$id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Validasi input
if (
    empty($password_lama) ||
    empty($password_baru) ||
    empty($konfirmasi_password)
) {

    echo "
        <script>
            alert('Semua field password wajib diisi.');
            window.history.back();
        </script>
    ";

    exit;
}

// Validasi password baru
if (strlen($password_baru) < 8) {

    echo "
        <script>
            alert('Password baru minimal 8 karakter.');
            window.history.back();
        </script>
    ";

    exit;
}

if ($password_baru != $konfirmasi_password) {

    echo "
        <script>
            alert('Konfirmasi password tidak sesuai.');
            window.history.back();
        </script>
    ";

    exit;
}

// Mengambil data admin
$queryAdmin = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE id_admin = '$id_admin'
");

if (mysqli_num_rows($queryAdmin) == 0) {

    echo "
        <script>
            alert('Data admin tidak ditemukan.');
            window.location='admin.php';
        </script>
    ";

    exit;
}

$dataAdmin = mysqli_fetch_assoc($queryAdmin);

// Validasi password lama
if (!password_verify($password_lama, $dataAdmin['password'])) {

    echo "
        <script>
            alert('Password lama tidak sesuai.');
            window.history.back();
        </script>
    ";

    exit;
}

// Hash password baru
$passwordHash = password_hash(
    $password_baru,
    PASSWORD_DEFAULT
);

// Memperbarui password
$update = mysqli_query($conn, "
    UPDATE admin
    SET
        password = '$passwordHash',
        updated_at = NOW()
    WHERE id_admin = '$id_admin'
");

if ($update) {

    // Menyimpan activity log
    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengubah Password Admin",
        "admin",
        $id_admin
    );

    $_SESSION['success'] = "Password berhasil diperbarui.";

    header("Location: admin.php");
    exit;
}

$_SESSION['error'] = "Password gagal diperbarui.";

header("Location: admin.php");
exit;