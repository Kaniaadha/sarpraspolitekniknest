<?php
session_start();

require_once "../../config/database.php";

/* =====================================================
   VALIDASI AKSES
===================================================== */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: admin.php");
    exit;

}

/* =====================================================
   AMBIL DATA
===================================================== */

$id_admin              = mysqli_real_escape_string($conn, $_POST['id_admin']);
$password_lama         = $_POST['password_lama'];
$password_baru         = $_POST['password_baru'];
$konfirmasi_password   = $_POST['konfirmasi_password'];

/* =====================================================
   VALIDASI INPUT
===================================================== */

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

/* =====================================================
   PASSWORD BARU MINIMAL 8 KARAKTER
===================================================== */

if (strlen($password_baru) < 8) {

    echo "
    <script>

        alert('Password baru minimal 8 karakter.');

        window.history.back();

    </script>
    ";

    exit;

}

/* =====================================================
   KONFIRMASI PASSWORD
===================================================== */

if ($password_baru != $konfirmasi_password) {

    echo "
    <script>

        alert('Konfirmasi password tidak sesuai.');

        window.history.back();

    </script>
    ";

    exit;

}

/* =====================================================
   AMBIL DATA ADMIN
===================================================== */

$queryAdmin = mysqli_query($conn, "
    SELECT *
    FROM admin
    WHERE id_admin='$id_admin'
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

/* =====================================================
   CEK PASSWORD LAMA
===================================================== */

if (!password_verify($password_lama, $dataAdmin['password'])) {

    echo "
    <script>

        alert('Password lama tidak sesuai.');

        window.history.back();

    </script>
    ";

    exit;

}

/* =====================================================
   HASH PASSWORD BARU
===================================================== */

$passwordHash = password_hash(
    $password_baru,
    PASSWORD_DEFAULT
);

/* =====================================================
   UPDATE PASSWORD
===================================================== */

$update = mysqli_query($conn, "
    UPDATE admin
    SET
        password   = '$passwordHash',
        updated_at = NOW()
    WHERE id_admin = '$id_admin'
");

/* =====================================================
   JIKA UPDATE BERHASIL
===================================================== */

if ($update) {

    /* ===============================================
       SIMPAN ACTIVITY LOG
    =============================================== */

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
            '$id_admin',
            'Mengubah Password Admin',
            'admin',
            '$id_admin'
        )
    ");

    echo "
    <script>

        alert('Password berhasil diperbarui.');

        window.location='admin.php';

    </script>
    ";

    exit;

}

/* =====================================================
   JIKA UPDATE GAGAL
===================================================== */

echo "
<script>

    alert('Password gagal diperbarui.');

    window.history.back();

</script>
";

exit;