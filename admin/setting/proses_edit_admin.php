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
$id_admin   = mysqli_real_escape_string($conn, $_POST['id_admin']);
$nama_admin = mysqli_real_escape_string($conn, trim($_POST['nama_admin']));
$username   = mysqli_real_escape_string($conn, trim($_POST['username']));
$email      = mysqli_real_escape_string($conn, trim($_POST['email']));
$no_hp      = mysqli_real_escape_string($conn, trim($_POST['no_hp']));

// Validasi input
if (empty($nama_admin)) {

    echo "
        <script>
            alert('Nama admin wajib diisi.');
            window.history.back();
        </script>
    ";

    exit;
}

if (empty($username)) {

    echo "
        <script>
            alert('Username wajib diisi.');
            window.history.back();
        </script>
    ";

    exit;
}

// Validasi username
$cekUsername = mysqli_query($conn, "
    SELECT id_admin
    FROM admin
    WHERE username = '$username'
    AND id_admin != '$id_admin'
");

if (mysqli_num_rows($cekUsername) > 0) {

    echo "
        <script>
            alert('Username sudah digunakan.');
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

$fotoLama = $dataAdmin['foto'];
$namaFoto = $fotoLama;

// Menentukan folder upload
$folderUpload = "../../assets/uploads/admin/";

if (!is_dir($folderUpload)) {
    mkdir($folderUpload, 0777, true);
}

// Proses upload foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

    $namaFile = $_FILES['foto']['name'];
    $tmpFile  = $_FILES['foto']['tmp_name'];
    $ukuran   = $_FILES['foto']['size'];

    $extensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    $allowed = [
        'jpg',
        'jpeg',
        'png'
    ];

    // Validasi ekstensi
    if (!in_array($extensi, $allowed)) {

        echo "
            <script>
                alert('Format foto harus JPG, JPEG atau PNG.');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Validasi ukuran
    if ($ukuran > 10 * 1024 * 1024) {

        echo "
            <script>
                alert('Ukuran foto maksimal 10 MB.');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Membuat nama file baru
    $namaFoto = "admin_" . $id_admin . "_" . time() . "." . $extensi;

    // Upload foto
    if (!move_uploaded_file($tmpFile, $folderUpload . $namaFoto)) {

        echo "
            <script>
                alert('Upload foto gagal.');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Menghapus foto lama
    if (!empty($fotoLama) && file_exists($folderUpload . $fotoLama)) {
        unlink($folderUpload . $fotoLama);
    }
}

// Memperbarui data admin
$update = mysqli_query($conn, "
    UPDATE admin
    SET
        nama_admin = '$nama_admin',
        username = '$username',
        email = '$email',
        no_hp = '$no_hp',
        foto = '$namaFoto',
        updated_at = NOW()
    WHERE id_admin = '$id_admin'
");

if ($update) {

    // Memperbarui session
    $_SESSION['nama_admin'] = $nama_admin;
    $_SESSION['username'] = $username;

    // Menyimpan activity log
    simpanActivityLog(
        $conn,
        $_SESSION['id_admin'],
        "Mengubah Profil Admin",
        "admin",
        $id_admin
    );

    $_SESSION['success'] = "Profil berhasil diperbarui.";

    header("Location: admin.php");
    exit;
}

$_SESSION['error'] = "Profil gagal diperbarui.";

header("Location: admin.php");
exit;