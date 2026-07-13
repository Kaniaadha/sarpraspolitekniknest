<!-- OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"></script>

<!-- Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="<?= BASE_URL; ?>/assets/dist/js/adminlte.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const sidebarWrapper = document.querySelector(".sidebar-wrapper");

    if (
        sidebarWrapper &&
        typeof OverlayScrollbarsGlobal !== "undefined"
    ) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
                theme: "os-theme-light",
                autoHide: "leave",
                clickScroll: true
            }
        });
    }

});
</script>

</body>
</html>