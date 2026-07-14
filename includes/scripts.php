<!-- OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"></script>

<!-- Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<script>
document.addEventListener("DOMContentLoaded", function () {

    const tables = document.querySelectorAll(".datatable");

    tables.forEach(function (table) {

        new DataTable(table, {

            responsive: true,

            autoWidth:false,

            pageLength: 10,

            columnDefs: [
                {
                    orderable: false,
                    targets: -1
                }
            ],

            language: {
                search: "🔍 Cari :",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "›",
                    previous: "‹"
                }
            }

        });

    });

});
</script>

<?php if (isset($_SESSION['success'])) : ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= $_SESSION['success']; ?>',
    confirmButtonColor: '#0d6efd'
});
</script>

<?php
unset($_SESSION['success']);
endif;
?>

<?php if (isset($_SESSION['error'])) : ?>

<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: '<?= $_SESSION['error']; ?>',
    confirmButtonColor: '#dc3545'
});
</script>

<?php
unset($_SESSION['error']);
endif;
?>

</body>
</html>