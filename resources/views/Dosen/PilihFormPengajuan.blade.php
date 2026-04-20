@php use Carbon\Carbon; @endphp
@extends('Dosen.Components.sidebar')
@section('main-content')

<style>
    .header-h1 {
        font-size: 24px;
        font-weight: 700;
        padding-bottom: 100px;
        margin-left: 30px;
        font-family: sans-serif;
    }

    /* Box Periode Aktif */
    .periode-box {
        margin-left: 40px;
        margin-right: 40px;
        background-color: white;
        padding: 24px;
        text-align: center;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        font-family: sans-serif;
        margin-bottom: 20px;
    }

    .periode-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .periode-date {
        font-size: 16px;
        color: #4b5563;
    }

    .periode-warning {
        font-size: 16px;
        color: #b91c1c;
        font-weight: 500;
        line-height: 1.6;
    }

    .periode-warning span {
        background-color: #fee2e2;
        padding: 2px 4px;
        border-radius: 4px;
    }

    /* GRID - Responsive */
    .option-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin: auto;
        padding: 10px 28px;
    }

    /* Tablet - 3 kolom */
    @media (max-width: 1024px) {
        .option-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
    }

    /* Tablet kecil - 2 kolom */
    @media (max-width: 768px) {
        .header-h1 {
            margin-left: 20px;
            font-size: 20px;
            padding-bottom: 40px;
        }

        .periode-box {
            margin-left: 20px;
            margin-right: 20px;
            padding: 16px;
        }

        .periode-title {
            font-size: 18px;
        }

        .periode-date {
            font-size: 14px;
        }

        .periode-warning {
            font-size: 14px;
        }

        .option-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 10px 20px;
        }
    }

    /* Mobile - 1 kolom */
    @media (max-width: 480px) {
        .header-h1 {
            margin-left: 16px;
            font-size: 18px;
            padding-bottom: 30px;
        }

        .periode-box {
            margin-left: 16px;
            margin-right: 16px;
            padding: 12px;
        }

        .periode-title {
            font-size: 16px;
        }

        .periode-date {
            font-size: 13px;
        }

        .periode-warning {
            font-size: 13px;
        }

        .option-grid {
            grid-template-columns: 1fr;
            gap: 12px;
            padding: 10px 16px;
        }
    }

    .option-card {
        padding: 10px;
        text-align: center;
    }

    .option-link {
        display: block;
        padding: 16px;
        border-radius: 12px;
        text-decoration: none;
        color: #1f2937;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .option-link:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    /* Warna background berdasarkan rumpun */
    .bg-umum {
        background: linear-gradient(135deg, #ffffffc1 0%, #67aaf8 100%);
    }

    .bg-agama {
        background: linear-gradient(135deg, #ffffffc1 0%, #19cb57b6 100%);
    }

    .option-link.disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }

    .option-link.disabled:hover {
        transform: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .option-inner {
        text-align: left;
        font-weight: 400;
        font-family: sans-serif;
    }

    .option-inner div {
        margin-bottom: 8px;
        font-size: 14px;
    }

    .option-inner div:last-child {
        margin-bottom: 0;
    }

    .option-inner span {
        font-weight: 600;
    }
</style>

<div>
    <div class="header">
        <h1 style="padding-bottom: 100px;">Pilih Jenis Pengajuan</h1>
    </div>

    <!-- PERIODE AKTIF BOX -->
    <div class="periode-box">
        @if ($activePeriode !== null)
            <h2 class="periode-title">
                Periode Aktif: {{ $activePeriode->name }}
            </h2>

            <p class="periode-date">
                {{ Carbon::parse($activePeriode->date_start)->translatedFormat('d F Y') }}
                –
                {{ Carbon::parse($activePeriode->date_end)->translatedFormat('d F Y') }}
            </p>
        @else
            <p class="periode-warning">
                Tidak ada <u>periode pengajuan</u> yang aktif.<br>
                Silakan <span>hubungi staff kepegawaian</span>.<br>
                <i>Periode pengajuan hanya dapat dibuat oleh <u>staff kepegawaian</u>.</i>
            </p>
        @endif
    </div>

    <!-- GRID OPSI -->
    <div class="option-grid">
        @foreach ($options as $option)
            <div class="option-card">
                <a href="{{ $activePeriode ? route('form', ['id' => $option->id]) : '#' }}"
                   class="option-link {{ $option->rumpun == 'UMUM' ? 'bg-umum' : 'bg-agama' }} {{ !$activePeriode ? 'disabled' : '' }}">
                    <div class="option-inner">
                        <div>Rumpun: <span>{{ $option->rumpun }}</span></div>
                        <div>Usulan: <span>{{ $option->usul }}</span></div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

@endsection