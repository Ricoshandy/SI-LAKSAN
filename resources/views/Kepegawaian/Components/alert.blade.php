
@if (session('success'))

    @if (strtolower(trim((string) session('success'))) === 'login berhasil')

        <style>
            .silaksan-login-toast {
                position: fixed;
                top: 24px;
                left: 50%;
                transform: translateX(-50%);
                width: min(420px, calc(100% - 32px));
                z-index: 99999;
                pointer-events: none;
            }

            .silaksan-login-toast-card {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 18px 20px;
                border-radius: 16px;
                border: 1px solid #bbf7d0;
                background: #ffffff;
                box-shadow: 0 16px 45px rgba(15, 23, 42, 0.18);
                pointer-events: auto;

                animation:
                    silaksanToastDown .55s cubic-bezier(.2,.8,.2,1) both,
                    silaksanToastUp .45s ease 3.5s forwards;
            }

            .silaksan-toast-icon {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-shrink: 0;
                width: 44px;
                height: 44px;
                border-radius: 13px;
                background: #dcfce7;
                color: #16a34a;
                font-size: 24px;
                font-weight: 700;
            }

            .silaksan-toast-content {
                flex: 1;
                min-width: 0;
            }

            .silaksan-toast-title {
                font-size: 16px;
                font-weight: 700;
                color: #166534;
                margin: 0 0 4px;
            }

            .silaksan-toast-description {
                font-size: 12px;
                line-height: 1.5;
                color: #64748b;
                margin: 0;
            }

            .silaksan-toast-close {
                background: transparent;
                border: 0;
                color: #94a3b8;
                font-size: 22px;
                cursor: pointer;
                align-self: flex-start;
            }

            @keyframes silaksanToastDown {
                from {
                    opacity: 0;
                    transform: translateY(-90px) scale(.95);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes silaksanToastUp {
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
                .silaksan-login-toast-card {
                    animation: none;
                }
            }
        </style>

        <div id="silaksanLoginToast"
             class="silaksan-login-toast"
             role="status"
             aria-live="polite">

            <div class="silaksan-login-toast-card">

                <div class="silaksan-toast-icon">
                    ✓
                </div>

                <div class="silaksan-toast-content">
                    <h4 class="silaksan-toast-title">
                        Login Berhasil!
                    </h4>

                    <p class="silaksan-toast-description">
                        Selamat datang kembali di SI-LAKSAN.
                    </p>
                </div>

                <button type="button"
                        class="silaksan-toast-close"
                        aria-label="Tutup notifikasi"
                        onclick="document.getElementById('silaksanLoginToast')?.remove()">
                    &times;
                </button>

            </div>
        </div>

        <script>
            setTimeout(function () {
                const toast = document.getElementById('silaksanLoginToast');

                if (toast) {
                    toast.remove();
                }
            }, 4100);
        </script>

    @else

        {{-- Notifikasi sukses untuk aksi selain login --}}
        <div style="
            position: fixed;
            inset: 0;
            z-index: 999;
            background: rgba(0,0,0,.35);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        ">
            <div style="
                background: white;
                border-radius: 12px;
                padding: 24px;
                max-width: 500px;
                width: 100%;
                box-shadow: 0 10px 30px rgba(0,0,0,.15);
            ">
                <h2 style="margin: 0 0 10px;">
                    Action Success!
                </h2>

                <p style="color: #15803d;">
                    {{ session('success') }}
                </p>

                <button type="button"
                        onclick="this.parentElement.parentElement.remove()"
                        style="
                            margin-top: 16px;
                            padding: 10px 18px;
                            background: #2563eb;
                            color: white;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                        ">
                    Tutup Notifikasi
                </button>
            </div>
        </div>

    @endif

@endif
