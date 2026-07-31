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
                 <a href="<?= BASE_URL; ?>/admin/dashboard.php"
                    class="nav-link <?= (isset($menu) && $menu == 'dashboard') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                  </a>
                </li>

                <!-- MASTER -->
                <li class="nav-header">MASTER</li>

                <a href="<?= BASE_URL; ?>/admin/master/admin/index.php"
                    class="nav-link <?= (isset($menu) && $menu == 'admin') ? 'active' : ''; ?>">
                    <i class="nav-icon bi bi-person"></i>
                    <p>Admin</p>
                </a>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/master/lokasi/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'lokasi') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-geo-alt-fill"></i>
                        <p>Lokasi</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/master/lantai/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'lantai') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-layers-fill"></i>
                        <p>Lantai</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/master/ruangan/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'ruangan') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-door-open-fill"></i>
                        <p>Ruangan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/master/public_space/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'public_space') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-building-fill"></i>
                        <p>Public Space</p>
                    </a>
                </li>

                <li class="nav-item">
                   <a href="<?= BASE_URL; ?>/admin/master/kategori/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'kategori') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-tags-fill"></i>
                        <p>Kategori</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/master/inventaris/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'inventaris') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Inventaris</p>
                    </a>
                </li>

                <!-- TRANSAKSI -->
                <li class="nav-header">TRANSAKSI</li>

                <li class="nav-item">
                   <a href="<?= BASE_URL; ?>/admin/transaksi/peminjaman/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'peminjaman') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-arrow-left-right"></i>
                        <p>Peminjaman</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/transaksi/stock_opname/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'stock_opname') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-clipboard-check-fill"></i>
                        <p>Stock Opname</p>
                    </a>
                </li>

                <!-- LAPORAN -->
                <li class="nav-header">LAPORAN</li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/laporan/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'laporan') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-file-earmark-bar-graph-fill"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                <!-- SETTING -->
                <li class="nav-header">SETTING</li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/setting/banner/index.php"
                        class="nav-link <?= (isset($menu) && $menu == 'banner') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-image-fill"></i>
                        <p>Banner</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= BASE_URL; ?>/admin/activity_log.php"
                        class="nav-link <?= (isset($menu) && $menu == 'banner') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-clock-history"></i>
                        <p>Activity Log</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href= "<?= BASE_URL; ?>/admin/setting/admin.php"
                        class="nav-link <?= (isset($menu) && $menu == 'setting') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Profil</p>
                    </a>
                </li>

            </ul>

        </nav>

    </div>

</aside>