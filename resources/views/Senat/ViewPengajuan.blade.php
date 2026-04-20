@extends('Senat.Components.sidebar')
@section('main-content')

<style>
    .header {
        margin-bottom: 24px;
    }

    .header h1 {
        font-size: 24px;
        font-weight: 700;
        padding-bottom: 20px;
        margin-left: 40px;
        font-family: sans-serif;
    }

    .header p {
        color: #666;
        margin-left: 40px;
    }

    .profile-card {
        margin: 0 40px 24px 40px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e0f2fe;
        margin-bottom: 16px;
    }

    .profile-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-icon svg {
        width: 32px;
        height: 32px;
        stroke: white;
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .profile-email {
        font-size: 14px;
        color: #64748b;
    }

    .profile-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-rumpun {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
    }

    .badge-usulan {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }

    .content-wrapper {
        padding: 0 40px;
    }

    .files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .file-item {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .file-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.15);
    }

    .file-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 12px;
    }

    .file-status {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .icon-button {
        padding: 10px;
        border-radius: 8px;
        background-color: #10b981;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .icon-button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .icon-button svg {
        width: 20px;
        height: 20px;
        stroke: white;
    }

    .file-missing {
        font-size: 12px;
        color: #ef4444;
        font-style: italic;
    }

    .verified-badge {
        font-size: 11px;
        color: #10b981;
        font-weight: 600;
        margin-left: 6px;
    }

    /* Download ZIP Button */
    .download-section {
        text-align: center;
        padding: 24px 0;
    }

    .download-zip-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        transition: all 0.3s ease;
    }

    .download-zip-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
    }

    .download-zip-btn svg {
        width: 20px;
        height: 20px;
    }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 900px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #ef4444;
        color: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 24px;
        line-height: 1;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    .modal-title {
        padding: 24px;
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        border-bottom: 2px solid #e0f2fe;
    }

    .modal-iframe {
        flex: 1;
        border: none;
        min-height: 500px;
    }

    .modal-actions {
        padding: 16px 24px;
        border-top: 2px solid #e0f2fe;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .modal-button {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-download {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-close {
        background: #e5e7eb;
        color: #1e293b;
    }

    .btn-close:hover {
        background: #d1d5db;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header h1,
        .header p {
            margin-left: 20px;
        }

        .profile-card,
        .content-wrapper {
            margin-left: 20px;
            margin-right: 20px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .files-grid {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 95%;
        }
    }
</style>

<div class="header">
    <h1>View Usul Kenaikan Jabatan</h1>
    <p>Pengajuan Oleh:</p>
</div>

<div class="profile-card">
    <div class="profile-header">
        <div class="profile-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        </div>
        <div class="profile-info">
            <div class="profile-name">{{ $pengajuan->getUser->name }}</div>
            <div class="profile-email">{{ $pengajuan->getUser->email }}</div>
        </div>
    </div>
    <div class="profile-badges">
        <span class="badge badge-rumpun">Rumpun {{ $pengajuan->getFormPengajuan->rumpun }}</span>
        <span class="badge badge-usulan">Usulan Ke {{ $pengajuan->getFormPengajuan->usul }}</span>
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
                        <button onclick="show('{{ route('senat.pengajuan.file', ['id' => $pengajuan->id, 'key' => $key->key]) }}', '{{ $key->title }}')" class="icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                        <span class="verified-badge">✅ Terverifikasi Kepegawaian</span>
                    @else
                        <span class="file-missing">*Berkas Belum di Upload*</span>
                    @endif
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>

<!-- Download ZIP Button -->
<div class="download-section">
    <a href="{{ route('senat.pengajuan.download', ['id' => $pengajuan->id]) }}" class="download-zip-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Download Semua Berkas Terverifikasi (.zip)
    </a>
</div>

<!-- Modal -->
<div id="modal" class="modal-overlay">
    <div class="modal-content">
        <button onclick="closeModal()" class="modal-close">&times;</button>
        
        <h2 id="title" class="modal-title"></h2>
        <iframe id="container" src="" class="modal-iframe"></iframe>
        
        <div class="modal-actions">
            <button onclick="downloadFile()" class="modal-button btn-download">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download File
            </button>
            <button onclick="closeModal()" class="modal-button btn-close">Tutup</button>
        </div>
    </div>
</div>

<script>
    let currentFileUrl = '';

    function show(value, title) {
        document.getElementById('modal').style.display = 'flex';
        document.getElementById("title").innerText = "File " + title;
        
        currentFileUrl = value;
        
        let container = document.getElementById("container");
        let viewerUrl = '/pdfjs/web/viewer.html?file=' + encodeURIComponent(value);
        
        container.src = viewerUrl;
        console.log('Loading PDF viewer from local...');
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
        document.getElementById("container").src = '';
    }

    function downloadFile() {
        if (currentFileUrl) {
            const link = document.createElement('a');
            link.href = currentFileUrl;
            link.download = currentFileUrl.split('/').pop();
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

@endsection