@php use Carbon\Carbon; @endphp
@extends('Kepegawaian.Components.sidebar')
@section('main-content')

<style>
    /* General Styles */
    .header {
        margin-top: -200px;
        margin-bottom: 50px;
    }

    .header-left {
        font-size: 18px;
        font-weight: 700;
    }

    .header-left p {
        color: #666;
    }

    /* Search & Action Wrapper */
    .search-action-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        gap: 12px;
    }

    .search-container {
        position: relative;
        width: 280px;
    }

    .search-box {
        width: 100%;
        padding: 10px 16px 10px 40px;
        background: rgba(255, 255, 255, 0.9);
        border: 1.5px solid #e0e0e0;
        border-radius: 25px;
        font-size: 14px;
        color: #333;
        transition: all 0.3s ease;
    }

    .search-box::placeholder {
        color: #999;
    }

    .search-box:focus {
        outline: none;
        background: white;
        border-color: #3b7ee1;
        box-shadow: 0 4px 12px rgba(59, 126, 225, 0.15);
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #999;
        pointer-events: none;
        transition: color 0.3s;
    }

    .search-box:focus + .search-icon {
        color: #3b7ee1;
    }

    .btn-add-modern {
        background: linear-gradient(to right, #3b7ee1, #80deea);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 126, 225, 0.3);
        white-space: nowrap;
    }

    .btn-add-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 126, 225, 0.4);
    }

    .content-wrapper {
        padding: 0 24px;
    }

    /* Table Container */
    .table-container {
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .table-scroll {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    /* Table Header */
    .data-table thead tr {
        background: linear-gradient(to right, #3b7ee1, #80deea);
        color: hsl(0, 0%, 0%);
        height: 56px;
    }

    .data-table th {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
        font-weight: 600;
    }

    .data-table th:first-child {
        text-align: left;
        padding-left: 20px;
    }

    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .data-table tbody tr:nth-child(odd) {
        background-color: #ffffffc3;
    }

    .data-table tbody tr:nth-child(even) {
        background-color: #ffffffc3;
    }

    .data-table tbody tr:hover {
        background-color: #f0f8ff !important;
    }

    .data-table tbody tr.hidden {
        display: none;
    }

    .data-table td {
        padding: 14px 12px;
        text-align: center;
        vertical-align: middle;
    }

    .data-table td:first-child {
        text-align: left;
        padding-left: 20px;
        font-weight: 600;
        color: #3b7ee1;
    }

    .data-table td:nth-child(2) {
        font-weight: 600;
        color: #333;
    }

    /* Badge Styles */
    .badge {
        border-radius: 10px;
        padding: 6px 12px;
        font-weight: 600;
        color: white;
        display: inline-block;
        font-size: 13px;
    }

    .badge-date-start {
        background-color: #6f42c1;
    }

    .badge-date-end {
        background-color: #007bff;
    }

    .badge-status {
        border-radius: 10px;
        padding: 6px 12px;
        font-weight: 600;
        color: white;
        display: inline-block;
        font-size: 13px;
    }

    .badge-status-aktif {
        background-color: #28a746c9;
    }

    .badge-status-belum {
        background-color: #007bff;
    }

    .badge-status-berakhir {
        background-color: #dc3546d6;
    }

    /* Action Buttons */
    .action-buttons {
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: center;
        position: relative;
        z-index: 10;
    }

    .action-btn {
        color: white;
        padding: 8px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
        position: relative;
        z-index: 10;
    }

    .action-btn:hover {
        opacity: 0.85;
        transform: translateY(-1px);
    }

    .action-btn svg {
        width: 20px;
        height: 20px;
    }

    .btn-view {
        background-color: #007bff;
    }

    .btn-edit {
        background-color: #28a745;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    /* No Results */
    .no-results {
        display: none;
        text-align: center;
        padding: 60px 20px;
        background: rgba(255, 255, 255, 0.9);
    }

    .no-results.show {
        display: block;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 999;
        top: 0;
        left: 0;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 32px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: relative;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #f0f0f0;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: #e0e0e0;
        transform: rotate(90deg);
    }

    .modal-content h2 {
        margin: 0 0 24px 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b7ee1;
        box-shadow: 0 0 0 3px rgba(59, 126, 225, 0.1);
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(to right, #3b7ee1, #80deea);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 126, 225, 0.4);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 126, 225, 0.6);
    }

    .btn-cancel {
        width: 100%;
        margin-top: 12px;
        padding: 12px;
        background: #f0f0f0;
        color: #666;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    .btn-delete-confirm {
        padding: 12px 32px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .btn-delete-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
        background: #c82333;
    }

    .modal-actions {
        display: flex;
        gap: 16px;
        align-items: center;
        justify-content: center;
        margin-top: 24px;
    }

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

    .card-item.hidden {
        display: none;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-number {
        font-weight: 700;
        font-size: 16px;
        color: #3b7ee1;
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

    .card-value {
        font-weight: 600;
        font-size: 13px;
        color: #333;
    }

    .card-actions {
        margin-top: 16px;
        padding-top: 12px;
        border-top: 2px solid #e5e7eb;
    }

    /* Responsive untuk Mobile */
    @media (max-width: 768px) {
        .search-action-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .search-container {
            width: 100%;
        }

        .btn-add-modern {
            width: 100%;
            text-align: center;
        }

        .table-container {
            background: transparent;
            box-shadow: none;
        }

        .header-left h1 {
            font-size: 24px;
        }

        .header-left p {
            font-size: 14px;
        }

        .content-wrapper {
            padding: 0 12px;
        }

        .table-scroll {
            display: none;
        }

        .card-view {
            display: block;
            padding: 0;
        }

        .action-buttons {
            gap: 8px;
        }

        .action-btn {
            padding: 10px;
        }
    }
</style>

<div class="header">
    <div class="header-left">
        <h1>List Periode Pengajuan</h1>
        <p style="font-size: 14px;">Berikut daftar seluruh periode pengajuan kenaikan jabatan</p>
    </div>
</div>

<div class="content-wrapper">
    <!-- Search & Add Button -->
    <div class="search-action-wrapper">
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" id="searchInput" class="search-box" placeholder="Cari periode...">
        </div>
        <button onclick="document.getElementById('modal').style.display='flex'" class="btn-add-modern">
            ➕ Tambah Periode Baru
        </button>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Label</th>
                        <th>Periode Dimulai</th>
                        <th>Periode Berakhir</th>
                        <th>Status Periode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    @foreach ($periodes as $i => $period)
                    <tr class="data-row">
                        <td>{{ $i + 1 }}</td>
                        <td class="periode-name">{{ $period->name }}</td>
                        <td>
                            <span class="badge badge-date-start">
                                {{ \Carbon\Carbon::parse($period->date_start)->translatedFormat('d F Y') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-date-end">
                                {{ \Carbon\Carbon::parse($period->date_end)->translatedFormat('d F Y') }}
                            </span>
                        </td>
                        <td class="status-data">
                            @php
                                $now = Carbon::now();
                                $start = Carbon::parse($period->date_start);
                                $end = Carbon::parse($period->date_end);
                                $status = '';
                                $statusClass = '';
                                if ($now->between($start, $end)) {
                                    $status = 'Aktif';
                                    $statusClass = 'badge-status-aktif';
                                } elseif ($now->lt($start)) {
                                    $status = 'Belum Dimulai';
                                    $statusClass = 'badge-status-belum';
                                } else {
                                    $status = 'Berakhir';
                                    $statusClass = 'badge-status-berakhir';
                                }
                            @endphp
                            <span class="badge badge-status {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('kepegawaian.periode.view', $period->id) }}" title="Lihat Pengajuan" class="action-btn btn-view">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                                <button onclick="document.getElementById('modal-{{ $period->id }}').style.display='flex'" title="Edit Periode" class="action-btn btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button onclick="document.getElementById('delete-modal-{{ $period->id }}').style.display='flex'" title="Hapus Periode" class="action-btn btn-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div id="modal-{{ $period->id }}" class="modal-overlay">
                        <div class="modal-content">
                            <button onclick="this.parentElement.parentElement.style.display='none'" class="modal-close">×</button>
                            
                            <h2>✏️ Edit Periode {{ $period->name }}</h2>
                            <form method="POST" action="{{ route('periode.edit', ['id' => $period->id]) }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Nama Periode</label>
                                    <input value="{{ $period->name }}" type="text" name="name" placeholder="Masukkan nama periode" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input value="{{ $period->date_start }}" type="date" name="date_start" class="form-input" required />
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tanggal Berakhir</label>
                                    <input value="{{ $period->date_end }}" type="date" name="date_end" class="form-input" required />
                                </div>
                                <button type="submit" class="btn-submit">Simpan Perubahan</button>
                                <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="btn-cancel">Batal</button>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div id="delete-modal-{{ $period->id }}" class="modal-overlay">
                        <div class="modal-content">
                            <button onclick="this.parentElement.parentElement.style.display='none'" class="modal-close">×</button>
                            
                            <h2>🗑️ Hapus Periode {{ $period->name }}?</h2>
                            <p style="text-align: center; color: #666; margin-bottom: 24px;">Apakah Anda yakin ingin menghapus periode ini? Tindakan ini tidak dapat dibatalkan.</p>
                            
                            <div class="modal-actions">
                                <a href="{{ route('periode.delete', ['id' => $period->id]) }}" class="btn-delete-confirm">
                                    Hapus Periode
                                </a>
                                <button onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="btn-cancel" style="width: auto; margin-top: 0; padding: 12px 32px;">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            <div class="no-results" id="noResults">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <div>Tidak ada data yang cocok dengan pencarian Anda</div>
            </div>
        </div>

        <!-- Card View untuk Mobile -->
        <div class="card-view" id="cardView">
            @foreach ($periodes as $i => $period)
            <div class="card-item card-data" 
                 data-name="{{ strtolower($period->name) }}" 
                 data-status="{{ strtolower($status ?? '') }}">
                <div class="card-header">
                    <span class="card-number">{{ $i + 1 }}</span>
                    @php
                        $now = Carbon::now();
                        $start = Carbon::parse($period->date_start);
                        $end = Carbon::parse($period->date_end);
                        $status = '';
                        $statusClass = '';
                        if ($now->between($start, $end)) {
                            $status = 'Aktif';
                            $statusClass = 'badge-status-aktif';
                        } elseif ($now->lt($start)) {
                            $status = 'Belum Dimulai';
                            $statusClass = 'badge-status-belum';
                        } else {
                            $status = 'Berakhir';
                            $statusClass = 'badge-status-berakhir';
                        }
                    @endphp
                    <span class="badge badge-status {{ $statusClass }}">
                        {{ $status }}
                    </span>
                </div>
                <div class="card-row">
                    <span class="card-label">Label</span>
                    <span class="card-value">{{ $period->name }}</span>
                </div>
                <div class="card-row">
                    <span class="card-label">Periode Dimulai</span>
                    <span class="badge badge-date-start">
                        {{ \Carbon\Carbon::parse($period->date_start)->translatedFormat('d M Y') }}
                    </span>
                </div>
                <div class="card-row">
                    <span class="card-label">Periode Berakhir</span>
                    <span class="badge badge-date-end">
                        {{ \Carbon\Carbon::parse($period->date_end)->translatedFormat('d M Y') }}
                    </span>
                </div>
                <div class="card-actions">
                    <div class="action-buttons">
                        <a href="{{ route('kepegawaian.periode.view', $period->id) }}" title="Lihat Pengajuan" class="action-btn btn-view">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                        <button onclick="document.getElementById('modal-{{ $period->id }}').style.display='flex'" title="Edit Periode" class="action-btn btn-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                        <button onclick="document.getElementById('delete-modal-{{ $period->id }}').style.display='flex'" title="Hapus Periode" class="action-btn btn-delete">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="no-results" id="noResultsMobile">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <div>Tidak ada data yang cocok dengan pencarian Anda</div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Period Modal -->
<div id="modal" class="modal-overlay">
    <div class="modal-content">
        <button onclick="this.parentElement.parentElement.style.display='none'" class="modal-close">×</button>
        
        <h2>➕ Tambah Periode Pengajuan</h2>
        <form method="POST" action="{{ route('periode.add') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Periode</label>
                <input type="text" name="name" placeholder="Masukkan nama periode" class="form-input" required />
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="date_start" class="form-input" required />
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Berakhir</label>
                <input type="date" name="date_end" class="form-input" required />
            </div>
            <button type="submit" class="btn-submit">Simpan</button>
            <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="btn-cancel">Batal</button>
        </form>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const tableRows = document.querySelectorAll('.data-row');
const cardItems = document.querySelectorAll('.card-data');
const noResults = document.getElementById('noResults');
const noResultsMobile = document.getElementById('noResultsMobile');

searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    let visibleRowCount = 0;
    let visibleCardCount = 0;

    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.classList.remove('hidden');
            visibleRowCount++;
        } else {
            row.classList.add('hidden');
        }
    });

    cardItems.forEach(card => {
        const name = card.dataset.name;
        const status = card.dataset.status;
        if (name.includes(searchTerm) || status.includes(searchTerm)) {
            card.classList.remove('hidden');
            visibleCardCount++;
        } else {
            card.classList.add('hidden');
        }
    });

    if (visibleRowCount === 0) noResults.classList.add('show');
    else noResults.classList.remove('show');

    if (visibleCardCount === 0) noResultsMobile.classList.add('show');
    else noResultsMobile.classList.remove('show');
});
</script>

@endsection