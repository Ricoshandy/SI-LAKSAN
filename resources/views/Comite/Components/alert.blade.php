
@if (strtolower(trim((string) session('success'))) === 'login berhasil')

<style>
    .dosen-login-toast {
        position: fixed;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        width: min(420px, calc(100% - 32px));
        z-index: 99999;
        pointer-events: none;
    }

    .dosen-login-toast-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ffffff;
        border: 1px solid #bbf7d0;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 16px 45px rgba(15, 23, 42, .18);
        pointer-events: auto;

        animation:
            dosenToastDown .55s cubic-bezier(.2,.8,.2,1) both,
            dosenToastUp .45s ease 3.5s forwards;
    }

    .dosen-toast-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: #dcfce7;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .dosen-toast-content {
        flex: 1;
        min-width: 0;
    }

    .dosen-toast-title {
        margin: 0 0 4px;
        color: #166534;
        font-size: 16px;
        font-weight: 700;
    }

    .dosen-toast-description {
        margin: 0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }

    .dosen-toast-close {
        align-self: flex-start;
        border: 0;
        background: transparent;
        color: #94a3b8;
        font-size: 22px;
        cursor: pointer;
    }

    @keyframes dosenToastDown {
        from {
            opacity: 0;
            transform: translateY(-90px) scale(.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes dosenToastUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-65px);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .dosen-login-toast-card {
            animation: none;
        }
    }
</style>

<div id="dosenLoginToast"
     class="dosen-login-toast"
     role="status"
     aria-live="polite">

    <div class="dosen-login-toast-card">

        <div class="dosen-toast-icon">
            ✓
        </div>

        <div class="dosen-toast-content">
            <h4 class="dosen-toast-title">
                Login Berhasil!
            </h4>

            <p class="dosen-toast-description">
                Selamat datang kembali di SI-LAKSAN.
            </p>
        </div>

        <button type="button"
                class="dosen-toast-close"
                aria-label="Tutup notifikasi"
                onclick="document.getElementById('dosenLoginToast')?.remove()">
            &times;
        </button>

    </div>
</div>

<script>
    setTimeout(function () {
        const toast = document.getElementById('dosenLoginToast');

        if (toast) {
            toast.remove();
        }
    }, 4100);
</script>

@endif
