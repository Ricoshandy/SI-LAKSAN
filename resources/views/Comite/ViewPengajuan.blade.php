@extends('Comite.Components.sidebar')
@section('main-content')

<style>
    /* Header Styles */
    .header {
        margin-bottom: 24px;
    }

    .header h1 {
        font-size:40px;
        font-weight: bold;
        padding-bottom: 35px;
        margin-left: 40px;
        font-family: sans-serif;
    }

    .header > p {
        text-align: left;
        margin-left: 40px;
        margin-bottom: 12px;
        font-weight: 600;
        color: #374151;
    }

    /* Profile Card */
    .profile-card {
        text-align: left;
        box-shadow: inset 3px 2px 15px rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        width: fit-content;
        padding: 12px;
        margin-left: 40px;
        background-color: white;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .profile-icon {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
    }

    .profile-info {
        border-bottom: 1px solid black;
        padding: 3px;
        margin-left: 8px;
    }

    .profile-name {
        margin: 2px 0;
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
    }

    .profile-email {
        margin: 2px 0;
        font-size: 12px;
        white-space: nowrap;
    }

    .profile-badges {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }

    .badge-rumpun,
    .badge-usulan {
        font-size: 12px;
        color: #333;
        border-radius: 8px;
        padding: 6px 10px;
        font-weight: 600;
    }

    .badge-rumpun {
        background-color: rgb(136, 239, 255);
    }

    .badge-usulan {
        background-color: rgb(190, 245, 255);
    }

    /* Main Content */
    .content-wrapper {
        margin-left: 40px;
        margin-right: 40px;
        margin-top: 24px;
    }

    /* Files Grid */
    .files-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: rgb(245, 255, 245);
        border-radius: 8px;
        padding: 14px;
        min-height: 60px;
    }

    .file-label {
        font-weight: 700;
        font-size: 14px;
        color: #1f2937;
        flex: 1;
    }

    .file-status {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .icon-button {
        background-color: var(--btn-bg, green);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.2s;
    }

    .icon-button:hover {
        opacity: 0.85;
    }

    .icon-button svg {
        width: 20px;
        height: 20px;
    }

    .file-missing {
        color: red;
        font-size: 13px;
        font-weight: 600;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        top: 0;
        left: 0;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        padding: 24px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #ef4444;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }

    .modal-close:hover {
        background-color: #dc2626;
    }

    .modal-title {
        margin: 0 40px 16px 0;
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    }

    .modal-iframe {
        width: 100%;
        height: 600px;
        border: none;
        border-radius: 8px;
        background-color: #f9fafb;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .modal-button {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-download {
        background-color: #10b981;
        color: white;
    }

    .btn-download:hover {
        background-color: #059669;
    }

    .btn-close {
        background-color: rgb(0, 60, 255);
        color: white;
    }

    .btn-close:hover {
        opacity: 0.9;
    }

    .modal-button svg {
        width: 20px;
        height: 20px;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .content-wrapper {
            margin-left: 30px;
            margin-right: 30px;
        }

        .files-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }
    }

    @media (max-width: 768px) {
        .header > p {
            margin-left: 20px;
        }

        .profile-card {
            margin-left: 20px;
            margin-right: 20px;
            width: auto;
        }

        .profile-header {
            flex-wrap: wrap;
        }

        .profile-info {
            flex: 1;
            min-width: 200px;
        }

        .profile-name,
        .profile-email {
            white-space: normal;
            word-break: break-word;
        }

        .content-wrapper {
            margin-left: 20px;
            margin-right: 20px;
        }

        .files-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .modal-content {
            padding: 20px;
            max-height: 85vh;
        }

        .modal-title {
            font-size: 18px;
            margin-right: 36px;
        }

        .modal-iframe {
            height: 400px;
        }
    }

    @media (max-width: 480px) {
        .header > p {
            margin-left: 16px;
            font-size: 14px;
        }

        .profile-card {
            margin-left: 16px;
            margin-right: 16px;
            padding: 10px;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
        }

        .profile-name {
            font-size: 13px;
        }

        .profile-email {
            font-size: 11px;
        }

        .badge-rumpun,
        .badge-usulan {
            font-size: 11px;
            padding: 5px 8px;
        }

        .content-wrapper {
            margin-left: 16px;
            margin-right: 16px;
        }

        .file-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            padding: 12px;
        }

        .file-label {
            font-size: 13px;
        }

        .file-status {
            width: 100%;
            justify-content: flex-end;
        }

        .modal-overlay {
            padding: 10px;
        }

        .modal-content {
            padding: 16px;
        }

        .modal-close {
            width: 28px;
            height: 28px;
            font-size: 18px;
        }

        .modal-title {
            font-size: 16px;
            margin-right: 32px;
        }

        .modal-iframe {
            height: 300px;
        }

        .modal-button {
            width: 100%;
        }

        .modal-actions {
            flex-direction: column;
        }
    }
</style>

<div class="header">
    <h1>View Usul Kenaikan Jabatan</h1>
    <p>Pengajuan Oleh:</p>

    <div class="profile-card">
        <div class="profile-header">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="black" class="profile-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <div class="profile-info">
                <p class="profile-name">{{ $pengajuan->getUser->name }}</p>
                <p class="profile-email">{{ $pengajuan->getUser->email }}</p>
            </div>
        </div>
        <div class="profile-badges">
            <span class="badge-rumpun">Rumpun {{ $pengajuan->getFormPengajuan->rumpun }}</span>
            <span class="badge-usulan">Usulan Ke {{ $pengajuan->getFormPengajuan->usul }}</span>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <div class="files-grid">
      @foreach ($pengajuan->getFormPengajuan->getFormPengajuanDetails()->orderBy('order', 'ASC')->get() as $key)
    @php
    $column = $key->key;
    $isVerified = $verifiedFiles->contains('key', $column);
    @endphp

    @if ($isVerified)
    <div class="file-item">
        <span class="file-label">{{ $key->title }}</span>

        <div class="file-status">
            @if ($pengajuan->$column !== null)
                <button onclick="show('{{ route('comite.pengajuan.file', ['id' => $pengajuan->id, 'key' => $key->key]) }}', '{{ $key->title }}')" class="icon-button" style="--btn-bg: green;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </button>
                  <span style="font-size: 11px; color: green; font-weight: 600; margin-left: 6px;">✅ Terverifikasi</span>
            @else
                <span class="file-missing">*Berkas Belum di Upload*</span>
            @endif
        </div>
    </div>
    @endif
@endforeach
    </div>
</div>

<!-- Modal -->
<div id="modal" class="modal-overlay">
    <div class="modal-content">
        <button onclick="closeModal()" class="modal-close">&times;</button>
        
        <h2 id="title" class="modal-title"></h2>
        <iframe id="container" src="" class="modal-iframe"></iframe>
        
        <div class="modal-actions">
            <button onclick="downloadFile()" class="modal-button btn-download">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download File
            </button>
            <button onclick="closeModal()" class="modal-button btn-close">Tutup</button>
        </div>
    </div>
</div>

!<!-- Button Download ZIP -->
<div style="padding: 16px 28px; text-align: center;">
    <a href="{{ route('comite.pengajuan.download', ['id' => $pengajuan->id]) }}" 
       style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background-color: #17a2b8; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Download Semua Berkas Terverifikasi (.zip)
    </a>
</div>

<script>
    let currentFileUrl = '';

    function show(value, title) {
        document.getElementById('modal').style.display = 'flex';
        document.getElementById("title").innerText = "File " + title;
        
        // Store the current file URL for download
        currentFileUrl = value;
        
        // Use local PDF.js viewer
        let container = document.getElementById("container");
        let viewerUrl = '/pdfjs/web/viewer.html?file=' + encodeURIComponent(value);
        
        container.src = viewerUrl;
        console.log('Loading PDF viewer from local...');
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
        // Clear iframe src when closing to stop loading
        document.getElementById("container").src = '';
    }

    function downloadFile() {
        if (currentFileUrl) {
            // Create temporary link to download file
            const link = document.createElement('a');
            link.href = currentFileUrl;
            link.download = currentFileUrl.split('/').pop(); // Get filename from URL
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // Close modal when clicking outside
    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

@endsection