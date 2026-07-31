<?php

session_start();

if (isset($_SESSION['id_admin'])) {
    header("Location: admin/dashboard.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login Admin | SISARPRAS Politeknik NEST</title>

    <!-- AdminLTE -->
    <link rel="stylesheet"
          href="assets/dist/css/adminlte.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Login CSS -->
    <link rel="stylesheet"
          href="assets/css/login.css">

</head>

<body>

<?php if (isset($_SESSION['error'])) : ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

window.addEventListener("load", function () {

    Swal.fire({

        icon: "error",

        title: "Login Gagal",

        text: "<?= htmlspecialchars($_SESSION['error']); ?>",

        confirmButtonColor: "#ff8a00"

    });

});

</script>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>

<main class="login-page">

    <div class="login-overlay">

        <div class="login-card">

            <!-- HEADER -->

            <div class="login-header">

                <img
                    src="assets/img/logo/logo-polnest.png"
                    class="login-logo"
                    alt="Logo Politeknik NEST">

                <span class="admin-badge">

                    ADMIN PORTAL

                </span>

                <h1>SISARPRAS</h1>

                <h2>Politeknik NEST</h2>

                <p>
                    Sistem Informasi Sarana dan Prasarana
                </p>

            </div>

            <!-- BODY -->

            <div class="login-body">

                <form
                    action="proses_login.php"
                    method="POST"
                    id="loginForm">

                    <!-- USERNAME -->

                    <div class="input-group-custom">

                        <label for="username">

                            Username

                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-person"></i>

                            <input

                                type="text"

                                id="username"

                                name="username"

                                placeholder="Masukkan username"

                                autocomplete="username"

                                required>

                        </div>

                    </div>

                    <!-- PASSWORD -->

                    <div class="input-group-custom">

                        <label for="password">

                            Password

                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-lock"></i>

                            <input

                                type="password"

                                id="password"

                                name="password"

                                placeholder="Masukkan password"

                                autocomplete="current-password"

                                required>

                            <button

                                type="button"

                                id="togglePassword"

                                class="toggle-password">

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>

                    <!-- BUTTON -->

                    <button

                        type="submit"

                        id="loginButton"

                        class="login-button">

                        LOGIN ADMIN

                    </button>

                </form>

            </div>

            <!-- FOOTER -->

            <div class="login-footer">

                <a
                    href="index.php"
                    class="back-home">

                    <i class="bi bi-arrow-left"></i>

                    Kembali ke Beranda

                </a>

                <p>

                    © <?= date('Y'); ?> Politeknik NEST

                </p>

            </div>

        </div>

    </div>

</main>

<!-- AdminLTE -->
<script src="assets/dist/js/adminlte.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Login JS -->
<script src="assets/js/login.js"></script>

</body>
</html>