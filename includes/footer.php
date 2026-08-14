<footer class="app-footer sisarpras-footer">

    <div class="footer-left">
        <strong>
            &copy; 2026 <span>SISARPRAS</span>
        </strong>
        <small>
            Sistem Informasi Sarana dan Prasarana
        </small>
    </div>

    <div class="footer-right">
        <span class="footer-version">
            <i class="bi bi-code-slash me-1"></i>
            Version 1.0
        </span>
    </div>

</footer>

<style>
/* =========================================
   FOOTER SISARPRAS
   ========================================= */

.sisarpras-footer {
    background: #ffffff;
    border-top: 1px solid #f0dfe5;
    min-height: 65px;
    padding: 12px 24px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    color: #6b7280;
}


/* Bagian kiri */
.sisarpras-footer .footer-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sisarpras-footer .footer-left strong {
    color: #374151;
    font-size: 14px;
}

.sisarpras-footer .footer-left strong span {
    color: #ec4899;
}

.sisarpras-footer .footer-left small {
    color: #9ca3af;
    font-size: 12px;
    padding-left: 12px;
    border-left: 1px solid #e5e7eb;
}


/* Versi */
.sisarpras-footer .footer-version {
    display: inline-flex;
    align-items: center;

    background: #fff1f6;
    color: #ec4899;

    padding: 6px 11px;
    border-radius: 20px;

    font-size: 12px;
    font-weight: 500;
}


/* =========================================
   MOBILE
   ========================================= */

@media (max-width: 575.98px) {

    .sisarpras-footer {
        min-height: auto;
        padding: 14px 16px;

        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .sisarpras-footer .footer-left {
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
    }

    .sisarpras-footer .footer-left small {
        padding-left: 0;
        border-left: none;
    }

    .sisarpras-footer .footer-version {
        font-size: 11px;
        padding: 5px 9px;
    }

}
</style>

</div>