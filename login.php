<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SISARPRAS</title>

    <link rel="stylesheet" href="assets/dist/css/adminlte.css">
</head>

<body class="login-page bg-body-secondary">

<div class="login-box">

    <div class="login-logo">
        <b>SISARPRAS</b>
    </div>

    <div class="card">

        <div class="card-body login-card-body">

            <p class="login-box-msg">
                Silakan login untuk melanjutkan
            </p>

            <form action="proses_login.php" method="POST">

                <div class="mb-3">
                    <input
                        type="text"
                        class="form-control"
                        name="username"
                        placeholder="Username"
                        required>
                </div>

                <div class="mb-3">
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="Password"
                        required>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

<script src="assets/dist/js/adminlte.js"></script>

</body>
</html>