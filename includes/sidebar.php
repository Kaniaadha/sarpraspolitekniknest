<aside class="app-sidebar shadow" data-bs-theme="dark">

<div class="sidebar-brand">

    <a href="<?= BASE_URL; ?>/admin/dashboard.php" class="brand-link">

        <img
            src="<?= BASE_URL; ?>/assets/img/logo/logo-polnest.png"
            alt="Logo Polnest"
            class="brand-logo">

        <div class="brand-text-wrapper">

            <span class="brand-title">
                SISARPRAS
            </span>

            <span class="brand-subtitle">
                Politeknik Nest
            </span>

        </div>

    </a>

</div>

    <!-- Sidebar -->
    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">

                <li class="nav-item">
                  <a href="<?= BASE_URL; ?>/admin/dashboard.php" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                  </a>
                </li>

                <!-- MASTER -->
                <li class="nav-header">MASTER</li>

                <a href="<?= BASE_URL; ?>/admin/master/admin/index.php"
                class="nav-link">
                    <i class="nav-icon bi bi-person"></i>
                    <p>Admin</p>
                </a>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-geo-alt-fill"></i>
                        <p>Lokasi</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-layers-fill"></i>
                        <p>Lantai</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-door-open-fill"></i>
                        <p>Ruangan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-building-fill"></i>
                        <p>Public Space</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-tags-fill"></i>
                        <p>Kategori</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Inventaris</p>
                    </a>
                </li>

                <!-- TRANSAKSI -->
                <li class="nav-header">TRANSAKSI</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-arrow-left-right"></i>
                        <p>Peminjaman</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-check-fill"></i>
                        <p>Stock Opname</p>
                    </a>
                </li>

                <!-- LAPORAN -->
                <li class="nav-header">LAPORAN</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-file-earmark-bar-graph-fill"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                <!-- SETTING -->
                <li class="nav-header">SETTING</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-image-fill"></i>
                        <p>Banner</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clock-history"></i>
                        <p>Activity Log</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Profil</p>
                    </a>
                </li>

            </ul>

        </nav>

    </div>

</aside>