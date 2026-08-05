<?php

require_once 'config/database.php';

require_once 'helpers/home_helper.php';

$baseUrl = '';

$pageTitle = 'Beranda';

$currentPage = 'home';

require_once 'includes/user/header.php';

require_once 'includes/user/navbar.php';
$banner = getActiveBanner($conn);

$bannerPhotos = [];

if ($banner) {
    $bannerPhotos = getBannerPhotos($conn, $banner['id_banner']);
}

$statistik = getStatistik($conn);

$lokasiList = getLatestLokasi($conn);

$publicSpaceList = getLatestPublicSpace($conn);

$inventarisList = getLatestInventaris($conn);
$heroTitle = $banner['judul'] ?? 'Sistem Informasi Sarana & Prasarana';
$heroDescription = $banner['deskripsi'] ?? 'Temukan informasi gedung, ruangan, public space, dan inventaris Politeknik Nest.';
require_once 'includes/user/components/hero.php';

require_once 'includes/user/components/search.php';

require_once 'includes/user/components/statistik.php';

require_once 'includes/user/components/gedung_section.php';

require_once 'includes/user/components/ruangan_section.php';

require_once 'includes/user/components/public_space_section.php';

require_once 'includes/user/components/inventaris_section.php';

require_once 'includes/user/components/informasi_section.php';

require_once 'includes/user/footer.php';

?>