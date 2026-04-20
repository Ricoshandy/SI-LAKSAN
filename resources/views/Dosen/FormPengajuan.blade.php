@extends('Dosen.Components.sidebar')
@section('main-content')

<style>
    /* Header Styles */
    .header {
        margin-bottom: 24px;
    }

    .header h1 {
         font-size: 37px;
        font-weight: bold;
        margin-left: 40px;
        font-family: sans-serif;
    }

    /* Main Container */
    .form-wrapper {
        margin-left: 40px;
        margin-right: 40px;
    }

    /* Info Box */
    .info-box {
        text-align: left;
        font-size: 16px;
        color: #333;
        background-color: white;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Periode Box */
    .periode-box {
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
        margin-bottom: 16px;
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

    /* Rumpun & Usulan Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-top: 16px;
    }

    .info-card {
        text-align: left;
        font-size: 18px;
        color: #333;
        border-radius: 8px;
        padding: 12px 16px;
        font-weight: 600;
    }

    .info-card-rumpun {
        background-color: rgba(111, 209, 224, 0.773);
    }

    .info-card-usulan {
        background-color: rgba(204, 204, 204, 0.718);
    }

    /* Form Styles */
    .upload-form {
        max-width: 100%;
        margin-top: 20px;
    }

    /* File Upload Grid */
    .upload-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .file-upload-container {
        background-color: rgb(245, 255, 245);
        border-radius: 8px;
        padding: 16px;
        transition: background-color 0.3s ease;
    }

    .file-upload-container.file-selected {
        background-color: lightgreen;
    }

    .file-upload-label {
        font-weight: 700;
        display: block;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 2px solid #333;
        font-size: 15px;
        color: #1f2937;
    }

    .file-description {
        font-size: 13px;
        color: #666;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .file-input {
        padding: 8px 0;
        display: block;
        width: 100%;
        font-size: 14px;
        color: #111827;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Button Container */
    .button-container {
        display: flex;
        justify-content: center;
        gap: 300px;
        margin-top: 32px;
        flex-wrap: wrap;
    }

    .btn-submit {
        background-color: #007bff;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-submit:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-submit-final {
        background-color: rgb(14, 121, 0);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .form-wrapper {
            margin-left: 30px;
            margin-right: 30px;
        }

        .upload-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .info-card {
            font-size: 16px;
        }
    }

    @media (max-width: 768px) {
        .form-wrapper {
            margin-left: 20px;
            margin-right: 20px;
        }

        .info-box {
            font-size: 14px;
            padding: 10px 12px;
        }

        .periode-box {
            padding: 20px 16px;
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

        .info-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .info-card {
            font-size: 16px;
            padding: 10px 14px;
        }

        .upload-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .button-container {
            flex-direction: column;
            gap: 12px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px 20px;
        }
    }

    @media (max-width: 480px) {
        .form-wrapper {
            margin-left: 16px;
            margin-right: 16px;
        }

        .info-box {
            font-size: 13px;
            padding: 8px 10px;
        }

        .periode-box {
            padding: 16px 12px;
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

        .info-card {
            font-size: 15px;
            padding: 10px 12px;
        }

        .file-upload-label {
            font-size: 14px;
        }

        .file-description {
            font-size: 12px;
        }

        .btn-submit {
            font-size: 15px;
            padding: 12px 16px;
        }
    }
</style>

<div class="header">
    <h1>Form Usul Kenaikan Jabatan</h1>
</div>

<div class="form-wrapper">
    <p style="padding-bottom: 35px;">
        Pengajuan dapat disimpan terlebih dahulu tanpa harus melengkapi semua form
    </p>

    <!-- Periode Box -->
    <div class="periode-box">
        @if ($activePeriode !== null)
            <h2 class="periode-title">
                Periode Aktif: {{ $activePeriode->name }}
            </h2>
            <p class="periode-date">
                {{ \Carbon\Carbon::parse($activePeriode->date_start)->translatedFormat('d F Y') }}
                –
                {{ \Carbon\Carbon::parse($activePeriode->date_end)->translatedFormat('d F Y') }}
            </p>

            <div class="info-grid">
                <div class="info-card info-card-rumpun">
                    Rumpun: {{ $form->rumpun }}
                </div>
                <div class="info-card info-card-usulan">
                    Usulan: Ke {{ $form->usul }}
                </div>
            </div>
        @else
            <p class="periode-warning">
                Tidak ada <u>periode pengajuan</u> yang aktif.<br>
                Silakan <span>hubungi staff kepegawaian</span> untuk mengkonfirmasi periode pengajuan.<br>
                <i>Periode pengajuan hanya dapat dibuat oleh <u>staff kepegawaian</u>.</i>
            </p>
        @endif
    </div>

    @php
        $formDetails = $form->getFormPengajuanDetails()->orderBy('order', 'ASC')->get();
    @endphp

    <!-- Form Upload -->
    <form action="{{ route('pengajuan.submit', ['id' => $form->id]) }}" method="POST" enctype="multipart/form-data" class="upload-form">
        @csrf
        <input type="hidden" name="periode_id" value="{{ $activePeriode->id }}">

        <div class="upload-grid">
            @foreach ($formDetails as $detail)
            <div class="file-upload-container" id="container-{{ $detail->key }}">
                <label for="{{ $detail->key }}" class="file-upload-label">
                    {{ $detail->title }}
                </label>

                @if (!empty($detail->description))
                    <p class="file-description">
                        {{ $detail->description }}
                    </p>
                @endif

                <input 
                    type="file" 
                    name="{{ $detail->key }}" 
                    id="{{ $detail->key }}" 
                    class="file-input" 
                    data-container="container-{{ $detail->key }}"
                >
            </div>
            @endforeach
        </div>

        <div class="button-container">
            <button type="submit" class="btn-submit">
                Simpan Pengajuan
            </button>

            <button type="submit" name="pengajuan" value="true" class="btn-submit btn-submit-final">
                Simpan dan Ajukan Kenaikan Jabatan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".file-input").forEach(input => {
            input.addEventListener("change", function () {
                let containerId = this.getAttribute("data-container");
                let container = document.getElementById(containerId);

                if (this.files.length > 0) {
                    container.classList.add("file-selected");
                } else {
                    container.classList.remove("file-selected");
                }
            });
        });
    });
</script>

@endsection