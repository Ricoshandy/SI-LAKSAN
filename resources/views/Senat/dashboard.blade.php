
@extends('Senat.Components.sidebar')

@section('main-content')

<h1>Dashboard Senat</h1>

{{-- Alert --}}
@includeWhen(
    View::exists('Senat.Components.alert'),
    'Senat.Components.alert'
)

{{-- ========================================
     KARTU STATISTIK LAMA
======================================== --}}

<div class="cards">

    <div class="card">
        <h2>Total Dosen</h2>
        <p>{{ $totalDosen ?? 0 }}</p>
    </div>

    <div class="card">
        <h2>Total Pengajuan</h2>
        <p>{{ $totalPengajuan ?? 0 }}</p>
    </div>

    <div class="card">
        <h2>Berkas Ditolak</h2>
        <p>{{ $berkasDitolak ?? 0 }}</p>
    </div>

    <div class="card">
        <h2>Berkas Diterima</h2>
        <p>{{ $berkasDiterima ?? 0 }}</p>
    </div>

</div>


{{-- ========================================
     STATISTIK PENGAJUAN PER PERIODE
======================================== --}}

<div class="statistik-container">

    <h2>📈 Statistik Pengajuan Per Periode</h2>

    <p style="
        margin-top: 8px;
        font-size: 13px;
        color: #475569;
    ">
        Total pengajuan seluruh dosen berdasarkan periode pembukaan.
    </p>

    {{-- Filter tahun --}}
    <form method="GET" style="margin: 20px 0;">

        <label for="filterTahun" style="
            font-weight: 600;
            margin-right: 8px;
        ">
            Pilih Tahun:
        </label>

        <select
            id="filterTahun"
            name="tahun"
            onchange="this.form.submit()"
            style="
                padding: 9px 18px;
                border: 1px solid #93c5fd;
                border-radius: 10px;
                background: white;
                color: #0d47a1;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                outline: none;
            "
        >

            @forelse ($daftarTahun ?? [] as $tahun)

                <option
                    value="{{ $tahun }}"
                    {{ (int) $tahun === (int) ($tahunDipilih ?? 0)
                        ? 'selected'
                        : '' }}
                >
                    {{ $tahun }}
                </option>

            @empty

                <option value="{{ now()->year }}">
                    {{ now()->year }}
                </option>

            @endforelse

        </select>

    </form>


    {{-- Grafik --}}
    <canvas id="statistikChart"></canvas>


    {{-- Status pembaruan --}}
    <p id="statistikLastUpdate" style="
        margin-top: 16px;
        font-size: 12px;
        color: #475569;
    ">
        Data otomatis diperbarui setiap 30 detik.
    </p>

</div>


{{-- ========================================
     CHART.JS
======================================== --}}

{{-- Jika Chart.js sudah dimuat di sidebar,
     tag CDN ini boleh dihapus untuk menghindari duplikasi. --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
(function () {

    const canvas = document.getElementById('statistikChart');

    if (!canvas || typeof Chart === 'undefined') {
        console.error('Chart.js atau canvas tidak ditemukan.');
        return;
    }

    // ========================================
    // 1. DATA AWAL DARI LARAVEL
    // ========================================

    const periodeLabels = @json($periodeLabels ?? []);
    const jumlahPengajuan = @json($jumlahPengajuan ?? []);
    const tahunDipilih = @json($tahunDipilih ?? now()->year);

    const ctx = canvas.getContext('2d');


    // ========================================
    // 2. GRAFIK PENGAJUAN SENAT
    // ========================================

    const statistikChart = new Chart(ctx, {

        type: 'bar',

        data: {

            labels: periodeLabels,

            datasets: [{

                label: 'Total Pengajuan',

                data: jumlahPengajuan,

                backgroundColor: [
                    'rgba(75,192,192,0.7)',
                    'rgba(153,102,255,0.7)',
                    'rgba(255,159,64,0.7)',
                    'rgba(255,99,132,0.7)',
                    'rgba(54,162,235,0.7)',
                    'rgba(255,206,86,0.7)'
                ],

                borderRadius: 10,
                borderWidth: 2

            }]
        },

        options: {

            responsive: true,

            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            },

            plugins: {

                legend: {
                    display: false
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total Pengajuan: ' +
                                context.parsed.y;
                        }
                    }
                }
            }
        }
    });


    // ========================================
    // 3. REFRESH OTOMATIS DARI DATABASE
    // ========================================

    let sedangMemperbarui = false;

    const statusUpdate = document.getElementById(
        'statistikLastUpdate'
    );

    async function refreshStatistikSenat() {

        if (sedangMemperbarui || document.hidden) {
            return;
        }

        sedangMemperbarui = true;

        try {

            // Menggunakan URL dashboard saat ini.
            // Tidak memerlukan route baru.
            const url = new URL(window.location.href);

            url.searchParams.set('statistik', '1');
            url.searchParams.set('tahun', tahunDipilih);

            const response = await fetch(url.toString(), {

                method: 'GET',
                cache: 'no-store',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }

            });

            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            const hasil = await response.json();

            if (
                !Array.isArray(hasil.labels) ||
                !Array.isArray(hasil.jumlah)
            ) {
                throw new Error(
                    'Format data statistik tidak sesuai.'
                );
            }

            // Update grafik tanpa reload
            statistikChart.data.labels = hasil.labels;

            statistikChart.data.datasets[0].data =
                hasil.jumlah;

            statistikChart.update();

            if (statusUpdate) {
                statusUpdate.textContent =
                    'Data terakhir diperbarui pukul ' +
                    hasil.updated_at;
            }

        } catch (error) {

            console.error(
                'Statistik Senat SI-LAKSAN:',
                error
            );

            if (statusUpdate) {
                statusUpdate.textContent =
                    'Pembaruan otomatis gagal. ' +
                    'Data terakhir tetap ditampilkan.';
            }

        } finally {

            sedangMemperbarui = false;

        }
    }


    // ========================================
    // 4. REFRESH SETIAP 30 DETIK
    // ========================================

    setInterval(refreshStatistikSenat, 30000);

})();
</script>

@endsection
