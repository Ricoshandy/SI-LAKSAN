@extends('Dosen.Components.sidebar')

@section('main-content')

<div class="header">
    <h1>Form Revisi Usul Kenaikan Jabatan</h1>
</div>

<div style="padding: 0 28px;">

    {{-- Notifikasi validasi --}}
    @if ($errors->any())
        <div style="
            background:#fee2e2;
            border:1px solid #ef4444;
            color:#991b1b;
            padding:15px;
            margin-bottom:20px;
            border-radius:10px;
        ">
            <strong>Revisi gagal disimpan!</strong>

            <ul style="margin:8px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1 style="
        text-align:left;
        font-size:24px;
        color:#333;
        background-color:rgb(136, 239, 255);
        border-radius:8px;
        padding:10px;
    ">
        Rumpun: {{ $pengajuan->getFormPengajuan->rumpun }}
    </h1>

    <h1 style="
        text-align:left;
        font-size:24px;
        color:#333;
        background-color:rgb(190, 245, 255);
        border-radius:8px;
        padding:10px;
    ">
        Usulan: Ke {{ $pengajuan->getFormPengajuan->usul }}
    </h1>

    <p style="
        text-align:left;
        font-size:16px;
        color:#333;
        background-color:white;
        border-radius:8px;
        padding:10px;
    ">
        Pengajuan dapat disimpan terlebih dahulu tanpa harus melengkapi semua form.
        Berkas wajib berformat PDF dengan ukuran maksimal 250kb.
    </p>

    <form
        action="{{ route('pengajuan.edit.submit', ['id' => $pengajuan->id]) }}"
        method="POST"
        enctype="multipart/form-data"
        style="max-width:100%; margin-top:20px;"
    >
        @csrf

        <div class="upload-grid" style="
            display:grid;
            grid-template-columns:repeat(3, minmax(0, 1fr));
            gap:16px;
            width:100%;
        ">

            @foreach (
                $pengajuan->getFormPengajuan
                    ->getFormPengajuanDetails()
                    ->orderBy('order', 'ASC')
                    ->get()
                as $detail
            )
                @php
                    $column = $detail->key;

                    $lastVersion = $pengajuan
                        ->getReviewPengajuans()
                        ->where('key', $detail->key)
                        ->max('version');

                    $reviewPengajuan = $pengajuan
                        ->getReviewPengajuans()
                        ->where('key', $detail->key)
                        ->where('version', $lastVersion)
                        ->first();

                    $needsRevision =
                        !empty($reviewPengajuan) &&
                        $reviewPengajuan->status === 'revisi';

                    $backgroundColor = $needsRevision
                        ? 'rgb(248, 113, 113)'
                        : (
                            empty($pengajuan->$column)
                                ? 'rgb(236, 252, 255)'
                                : 'rgba(255, 255, 255, 0.5)'
                        );
                @endphp

                <div
                    class="file-upload-container"
                    id="container-{{ $detail->key }}"
                    data-default-color="{{ $backgroundColor }}"
                    style="
                        margin-bottom:16px;
                        background-color:{{ $backgroundColor }};
                        border-radius:8px;
                        padding:14px;
                        box-sizing:border-box;
                        min-width:0;
                    "
                >
                    <label
                        for="{{ $detail->key }}"
                        style="
                            font-weight:bold;
                            display:block;
                            margin-bottom:5px;
                        "
                    >
                        {{ $detail->title }}
                    </label>

                    @if (!empty($detail->description))
                        <p style="
                            font-size:14px;
                            color:#666;
                            margin-bottom:5px;
                        ">
                            {{ $detail->description }}
                        </p>
                    @endif

                    @if ($needsRevision)
                        <div style="
                            background:#fff;
                            color:#991b1b;
                            padding:8px;
                            margin:8px 0;
                            border-radius:6px;
                        ">
                            <strong>Perlu perbaikan:</strong>

                            {{ $reviewPengajuan->keterangan
                                ?: 'Silakan perbarui berkas ini.' }}
                        </div>
                    @endif

                    @if (!empty($pengajuan->$column))
                        <div style="margin:10px 0;">
                            <iframe
                                src="{{ route(
                                    'dosen.pengajuan.file',
                                    [
                                        'id' => $pengajuan->id,
                                        'key' => $detail->key
                                    ]
                                ) }}"
                                title="{{ $detail->title }}"
                                frameborder="0"
                                width="100%"
                                height="120"
                            ></iframe>
                        </div>

                        <div style="
                            display:flex;
                            align-items:center;
                            gap:7px;
                            margin-bottom:8px;
                        ">
                            <input
                                type="checkbox"
                                class="update-checkbox"
                                id="check-{{ $detail->key }}"
                                data-target="{{ $detail->key }}"
                            >

                            <label for="check-{{ $detail->key }}">
                                Update berkas?
                            </label>
                        </div>

                        <input
                            type="file"
                            name="{{ $detail->key }}"
                            id="{{ $detail->key }}"
                            class="file-input"
                            data-container="container-{{ $detail->key }}"
                            accept=".pdf,application/pdf"
                            style="
                                padding:6px 0;
                                display:none;
                                width:100%;
                                font-size:16px;
                                color:#111827;
                                border:1px solid #d1d5db;
                                border-radius:8px;
                                cursor:pointer;
                                background-color:#f9fafb;
                                box-sizing:border-box;
                            "
                        >
                    @else
                        <input
                            type="file"
                            name="{{ $detail->key }}"
                            id="{{ $detail->key }}"
                            class="file-input"
                            data-container="container-{{ $detail->key }}"
                            accept=".pdf,application/pdf"
                            style="
                                padding:6px 0;
                                display:block;
                                width:100%;
                                font-size:16px;
                                color:#111827;
                                border:1px solid #d1d5db;
                                border-radius:8px;
                                cursor:pointer;
                                background-color:#f9fafb;
                                box-sizing:border-box;
                            "
                        >
                    @endif

                    <small style="
                        display:block;
                        margin-top:7px;
                        color:#555;
                    ">
                        Format PDF, maksimal 250 KB.
                    </small>

                    <div
                        class="file-error"
                        style="
                            display:none;
                            color:#b91c1c;
                            background:#fee2e2;
                            padding:7px;
                            margin-top:7px;
                            border-radius:6px;
                            font-size:13px;
                        "
                    ></div>
                </div>
            @endforeach
        </div>

        <div style="
            display:flex;
            justify-content:center;
            flex-wrap:wrap;
            gap:16px;
            width:100%;
            margin-top:20px;
        ">
            <button
                type="submit"
                style="
                    background-color:#007bff;
                    color:white;
                    padding:10px 15px;
                    border:none;
                    border-radius:4px;
                    cursor:pointer;
                    font-size:16px;
                "
            >
                Simpan Pengajuan
            </button>

            <button
                type="submit"
                name="pengajuan"
                value="true"
                style="
                    background-color:rgb(14, 121, 0);
                    color:white;
                    padding:10px 15px;
                    border:none;
                    border-radius:4px;
                    cursor:pointer;
                    font-size:16px;
                "
            >
                Simpan dan Ajukan Kenaikan Jabatan
            </button>
        </div>
    </form>
</div>

<style>
@media (max-width: 992px) {
    .upload-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

@media (max-width: 768px) {
    .upload-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const maximumSize = 500 * 500; // 500 kb

    function restoreContainerColor(container) {
        if (!container) {
            return;
        }

        container.style.backgroundColor =
            container.dataset.defaultColor || "";
    }

    document.querySelectorAll(".file-input").forEach(input => {
        input.addEventListener("change", function () {
            const container = document.getElementById(
                this.dataset.container
            );

            const errorElement = container
                ? container.querySelector(".file-error")
                : null;

            const file = this.files[0];

            if (errorElement) {
                errorElement.style.display = "none";
                errorElement.textContent = "";
            }

            restoreContainerColor(container);

            if (!file) {
                return;
            }

            const fileName = file.name.toLowerCase();

            const isPdf =
                file.type === "application/pdf" ||
                fileName.endsWith(".pdf");

            if (!isPdf) {
                const message =
                    "Berkas wajib menggunakan format PDF.";

                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = "block";
                }

                alert("Upload revisi gagal!\n" + message);

                this.value = "";
                return;
            }

            if (file.size > maximumSize) {
                const fileSizeMb = (
                    file.size / 500 / 500
                ).toFixed(2);

                const message =
                    "Ukuran berkas maksimal 250KB. " +
                    "Ukuran file yang dipilih: " +
                    fileSizeMb +
                    " KB.";

                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = "block";
                }

                alert("Upload revisi gagal!\n" + message);

                this.value = "";
                return;
            }

            if (container) {
                container.style.backgroundColor = "lightgreen";
            }
        });
    });

    document.querySelectorAll(".update-checkbox").forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            const fileInput = document.getElementById(
                this.dataset.target
            );

            if (!fileInput) {
                return;
            }

            fileInput.style.display = this.checked
                ? "block"
                : "none";

            if (!this.checked) {
                fileInput.value = "";

                const container = document.getElementById(
                    fileInput.dataset.container
                );

                const errorElement = container
                    ? container.querySelector(".file-error")
                    : null;

                if (errorElement) {
                    errorElement.style.display = "none";
                    errorElement.textContent = "";
                }

                restoreContainerColor(container);
            }
        });
    });
});
</script>

@endsection