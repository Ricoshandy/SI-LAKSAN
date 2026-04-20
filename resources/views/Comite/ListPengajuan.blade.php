@extends('Comite.Components.sidebar')
@section('main-content')

<style>
    /* General Styles */
  

    .header-h1{
        font-size: 22px;
        font-weight: 700;
        padding-bottom: 3px;
        margin-left: 12px;
        font-family: sans-serif;
    }

    .header p {
        color: #666;
        margin-left: 12px;
    }

    .content-wrapper {
        padding: 0 24px;
    }

    /* Table Container */
    .table-container {
        background: rgba(255, 255, 255, 0.138);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        overflow: hidden;
        backdrop-filter: blur(6px);
    }

    .table-scroll {
        overflow-x: auto;
    }

    /* Data Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    /* Table Header */
    .data-table thead tr {
        background: linear-gradient(to right, #3b7ee1, #80deea);
        color: hsl(0, 0%, 0%);
    }

    .data-table th {
        padding: 14px 12px;
        text-align: left;
    }

    .data-table th:not(:first-child) {
        text-align: center;
    }

    /* Table Body */
    .data-table tbody tr {
        transition: all 0.2s ease;
    }

    .data-table tbody tr:nth-child(odd) {
        background-color: #ffffffc3;
    }

    .data-table tbody tr:nth-child(even) {
        background-color: #ffffffc3;
    }

    .data-table tbody tr:hover {
        background-color: #ffffffc3;
    }

    .data-table td {
        padding: 10px 12px;
    }

    .data-table .text-center {
        text-align: center;
    }

    /* Badges */
    .badge {
        border-radius: 10px;
        padding: 6px 10px;
        font-weight: 600;
        color: white;
        display: inline-block;
        font-size: 13px;
    }

    .badge-rumpun-agama {
        background-color: #ffebcc;
        color: #d35400;
    }

    .badge-rumpun-umum {
        background-color: #e7f1ff;
        color: #007bff;
    }

    .badge-usul {
        font-style: italic;
    }

    .badge-usul-asisten_ahli { background-color: #6f42c1; }
    .badge-usul-lektor { background-color: #007bff; }
    .badge-usul-lektor_kepala { background-color: #17a2b8; }
    .badge-usul-guru_besar { background-color: #dc3545; }

    .badge-status {
        border-radius: 8px;
    }
    .badge-status-draft { background-color: #6c757d; }
    .badge-status-baru { background-color: #007bff; }
    .badge-status-dalam_proses { background-color: #17a2b8; }
    .badge-status-disetujui { background-color: #28a746c9; }
    .badge-status-ditolak { background-color: #dc3546d6; }
    .badge-status-revisi { background-color: #d9ab20d3; }

    /* Action Buttons */
    .action-buttons {
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .action-btn {
        color: white;
        padding: 8px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.2s;
        text-decoration: none;
    }

    .action-btn:hover {
        opacity: 0.8;
    }

    .action-btn svg {
        width: 20px;
        height: 20px;
    }

    .btn-view { background-color: #28a745; }
    .btn-upload { background-color: #007bff; }

    /* Card View untuk Mobile */
    .card-view {
        display: none;
    }

    .card-item {
        background: #ffffffc3;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-dosen {
        font-weight: 700;
        font-size: 16px;
        color: #1f2937;
    }

    .card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .card-row:last-child {
        border-bottom: none;
    }

    .card-label {
        font-weight: 600;
        color: #6b7280;
        font-size: 13px;
    }

    .card-actions {
        margin-top: 16px;
        padding-top: 12px;
        border-top: 2px solid #e5e7eb;
    }

    /* Responsive Breakpoints */
    @media (max-width: 1024px) {
        .header h1 {
            font-size: 22px;
            margin-left: 30px;
            padding-bottom: 80px;
        }

        .content-wrapper {
            padding: 0 16px;
        }
    }

    @media (max-width: 768px) {
        .header h1 {
            font-size: 20px;
            margin-left: 20px;
            padding-bottom: 60px;
        }

        .header p {
            margin-left: 20px;
            font-size: 14px;
            padding-bottom: 50px;
        }

        .content-wrapper {
            padding: 0 12px;
        }

        /* Hide table, show cards */
        .table-scroll {
            display: none;
        }

        .card-view {
            display: block;
            padding: 16px;
        }

        .action-buttons {
            gap: 8px;
        }

        .action-btn {
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .header h1 {
            font-size: 18px;
            margin-left: 16px;
            padding-bottom: 40px;
        }

        .header p {
            margin-left: 16px;
            font-size: 13px;
            padding-bottom: 30px;
        }

        .content-wrapper {
            padding: 0 8px;
        }

        .card-item {
            padding: 12px;
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .action-btn svg {
            width: 18px;
            height: 18px;
        }
    }
</style>

    <div class="header-h1">

    <h1>List Usul Kenaikan Jabatan</h1>
    </div>
    <p style="padding-bottom: 117px; padding-left: 13px;">Berikut daftar seluruh pengajuan kenaikan jabatan yang perlu ditinjau</p>
       



<div class="content-wrapper">
    <div class="table-container">
        <!-- Table View (Desktop) -->
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Dosen</th>
                        <th>Rumpun</th>
                        <th>Usul</th>
                        <th>Status</th>
                        <th>Tahap</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pengajuans as $i => $pengajuan)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="text-center">{{ $pengajuan->getUser->name }}</td>

                        <td class="text-center">
                            <span class="badge {{ $pengajuan->getFormPengajuan->rumpun == 'AGAMA' ? 'badge-rumpun-agama' : 'badge-rumpun-umum' }}">
                                {{ $pengajuan->getFormPengajuan->rumpun }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-usul badge-usul-{{ strtolower($pengajuan->getFormPengajuan->usul) }}">
                                {{ $pengajuan->getFormPengajuan->usul }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="badge badge-status badge-status-{{ strtolower($pengajuan->status) }}">
                                {{ $pengajuan->status }}
                            </span>
                        </td>

                        <td class="text-center">{{ $pengajuan->tahap }}</td>

                        <td class="text-center">
                            <div class="action-buttons">
                                @if ($pengajuan->tahap == 'SIDANG_KOMITE' && $pengajuan->status != 'DITOLAK')
                                    <a href="{{ route('comite.pengajuan.view', ['id' => $pengajuan->id]) }}" title="Lihat Berkas" class="action-btn btn-view">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('sidang.comite.view', ['id' => $pengajuan->id]) }}" title="Upload Berita Acara" class="action-btn btn-upload">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Card View (Mobile) -->
        <div class="card-view">
            @foreach ($pengajuans as $i => $pengajuan)
            <div class="card-item">
                <div class="card-header">
                    <span class="card-dosen">{{ $pengajuan->getUser->name }}</span>
                    <span class="badge badge-status badge-status-{{ strtolower($pengajuan->status) }}">
                        {{ $pengajuan->status }}
                    </span>
                </div>

                <div class="card-row">
                    <span class="card-label">Rumpun</span>
                    <span class="badge {{ $pengajuan->getFormPengajuan->rumpun == 'AGAMA' ? 'badge-rumpun-agama' : 'badge-rumpun-umum' }}">
                        {{ $pengajuan->getFormPengajuan->rumpun }}
                    </span>
                </div>

                <div class="card-row">
                    <span class="card-label">Usul</span>
                    <span class="badge badge-usul badge-usul-{{ strtolower($pengajuan->getFormPengajuan->usul) }}">
                        {{ $pengajuan->getFormPengajuan->usul }}
                    </span>
                </div>

                <div class="card-row">
                    <span class="card-label">Tahap</span>
                    <span style="font-weight: 600; font-size: 13px;">{{ $pengajuan->tahap }}</span>
                </div>

                <div class="card-actions">
                    <div class="action-buttons">
                        @if ($pengajuan->tahap == 'SIDANG_KOMITE' && $pengajuan->status != 'DITOLAK')
                            <a href="{{ route('comite.pengajuan.view', ['id' => $pengajuan->id]) }}" title="Lihat Berkas" class="action-btn btn-view">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>

                            <a href="{{ route('sidang.comite.view', ['id' => $pengajuan->id]) }}" title="Upload Berita Acara" class="action-btn btn-upload">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection