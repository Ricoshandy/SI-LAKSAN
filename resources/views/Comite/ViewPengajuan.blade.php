@extends('Comite.Components.sidebar')
@section('main-content')

<style>
    .header {
        margin-bottom: 24px;
    }

    .header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 4px 40px;
        padding: 0;
        line-height: 1.2;
        font-family: sans-serif;
        color: #1f2937;
    }

    .header > p {
        text-align: left;
        margin-left: 40px;
        margin-bottom: 12px;
        font-weight: 600;
        color: #374151;
    }

    .profile-card {
        text-align: left;
        box-shadow: inset 3px 2px 15px rgba(0,0,0,0.2);
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

    .profile-icon { width: 46px; height: 46px; flex-shrink: 0; }

    .profile-info {
        border-bottom: 1px solid black;
        padding: 3px;
        margin-left: 8px;
    }

    .profile-name { margin: 2px 0; font-weight: 600; font-size: 14px; white-space: nowrap; }
    .profile-email { margin: 2px 0; font-size: 12px; white-space: nowrap; }

    .profile-badges { display: flex; gap: 4px; flex-wrap: wrap; }

    .badge-rumpun, .badge-usulan {
        font-size: 12px; color: #333; border-radius: 8px;
        padding: 6px 10px; font-weight: 600;
    }
    .badge-rumpun { background-color: rgb(136, 239, 255); }
    .badge-usulan { background-color: rgb(190, 245, 255); }

    /* Content */
    .content-wrapper {
        margin: 24px 40px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e5e7eb;
    }

    /* Berkas Item */
    .file-review-item {
        margin-bottom: 12px;
        background-color: rgb(245, 255, 245);
        border-radius: 8px;
        padding: 14px;
        border: 1px solid #c3d4c3;
    }

    .file-header {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .file-title {
        font-weight: 700;
        font-size: 15px;
        color: #1f2937;
        flex: 1;
        min-width: 200px;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: opacity 0.2s;
        text-decoration: none;
        color: white;
    }
    .action-button:hover { opacity: 0.85; }
    .action-button svg { width: 18px; height: 18px; }
    .btn-expand { background-color: #3b82f6; }
    .btn-new-tab { background-color: #10b981; }

    /* Review Result */
    .review-result {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .review-badge {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: white;
    }
    .badge-diverifikasi { background-color: #10b981; }
    .badge-revisi { background-color: #f59e0b; }

    .review-keterangan {
        font-size: 13px;
        color: #6b7280;
        font-style: italic;
    }

    .review-version {
        font-size: 11px;
        background-color: #e5e7eb;
        color: #374151;
        padding: 3px 8px;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Progres Timeline */
    .timeline {
        position: relative;
        padding-left: 28px;
        margin-top: 8px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 6px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #3b7ee1;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #3b7ee1;
    }

    .timeline-date {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 4px;
    }

    .timeline-content {
        background-color: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    }

    .timeline-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: white;
        margin-bottom: 6px;
    }

    .status-baru { background-color: #3b82f6; }
    .status-draft { background-color: #6c757d; }
    .status-disetujui { background-color: #10b981; }
    .status-ditolak { background-color: #ef4444; }
    .status-revisi { background-color: #f59e0b; }
    .status-dalam_proses { background-color: #17a2b8; }

    .timeline-tahap {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }

    .timeline-keterangan {
        font-size: 13px;
        color: #6b7280;
    }

    .timeline-by {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        width: 100vw; height: 100vh;
        background-color: rgba(0,0,0,0.5);
        z-index: 999; top: 0; left: 0;
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
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .modal-close {
        position: absolute;
        top: 16px; right: 16px;
        background: #ef4444; color: white;
        border: none; width: 32px; height: 32px;
        border-radius: 50%; font-size: 20px;
        font-weight: bold; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
    }
    .modal-close:hover { background-color: #dc2626; }

    .modal-title {
        margin: 0 40px 16px 0;
        font-size: 20px; font-weight: 700; color: #1f2937;
    }
.pengajuan-block { margin-top: 10px; }

.pengajuan-label {
    font-size: 11px;
    font-weight: 700;
    color: #374151;
    background-color: #e5e7eb;
    display: inline-block;
    padding: 3px 10px;
    border-radius: 10px;
    margin-bottom: 6px;
}

.reviewer-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 3px 0;
    flex-wrap: wrap;
}

.reviewer-name {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    min-width: 100px;
}

.badge-ditolak { background-color: #ef4444; }
    .modal-iframe {
        width: 100%; height: 600px;
        border: none; border-radius: 8px;
        background-color: #f9fafb;
    }

    .modal-actions {
        display: flex; gap: 12px; margin-top: 16px;
    }

    .modal-button {
        flex: 1; padding: 12px 20px;
        border: none; border-radius: 8px;
        cursor: pointer; font-size: 15px; font-weight: 600;
        transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-download { background-color: #10b981; color: white; }
    .btn-download:hover { background-color: #059669; }
    .btn-close-modal { background-color: rgb(0,60,255); color: white; }
    .btn-close-modal:hover { opacity: 0.9; }
    .modal-button svg { width: 20px; height: 20px; }

    @media (max-width: 768px) {
        .header h1 { margin-left: 20px; font-size: 22px; }
        .header > p { margin-left: 20px; }
        .profile-card { margin-left: 20px; margin-right: 20px; width: auto; }
        .content-wrapper { margin: 16px 20px; }
        .file-header { flex-direction: column; align-items: flex-start; }
        .action-button { width: 100%; justify-content: center; }
        .modal-iframe { height: 400px; }
        .modal-actions { flex-direction: column; }
    }
</style>

<div class="header">
    <h1>Detail Pengajuan Kenaikan Jabatan</h1>
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

 {{-- TOMBOL DOWNLOAD ZIP --}}
    <div style="margin-bottom: 16px; display: flex; justify-content: flex-end;">
        <a href="{{ route('comite.pengajuan.download', ['id' => $pengajuan->id]) }}"
           style="display: inline-flex; align-items: center; gap: 4px; padding: 15px 20px; background-color: #00b63a; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: opacity 0.2s;"
           onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Download Berkas Terverifikasi (.zip)
        </a>
    </div>
    <div class="section-title">📁 Berkas & Hasil Review</div>

    @php
        $ordinalMap = [1=>'Pertama',2=>'Kedua',3=>'Ketiga',4=>'Keempat',5=>'Kelima'];
        $roleLabel  = ['kepegawaian'=>'Kepegawaian','komite'=>'Komite','senat'=>'Senat','sister'=>'Sister','pakptk'=>'Pakptk'];
        $roleOrder  = ['kepegawaian','komite','senat','sister','pakptk'];
    @endphp

    @foreach ($pengajuan->getFormPengajuan->getFormPengajuanDetails()->orderBy('order','ASC')->get() as $detail)
        @php
            $column  = $detail->key;
            $reviews = $pengajuan->getReviewPengajuans
                        ->where('key', $column)
                        ->sortBy('version')
                        ->groupBy('version');
        @endphp

        <div class="file-review-item">
            <div class="file-header">
                <span class="file-title">{{ $detail->title }}</span>

                @if ($pengajuan->$column !== null)
                    <button type="button" onclick="showModal('{{ route('comite.pengajuan.file', [
                        'id'  => $pengajuan->id,
                        'key' => $detail->key
                    ]) }}', '{{ $detail->title }}')" class="action-button btn-expand">
                        <span>Lihat File</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                    <a href="{{ route('comite.pengajuan.file', ['id' => $pengajuan->id, 'key' => $detail->key]) }}"
                       target="_blank" class="action-button btn-new-tab">
                        <span>Tab Baru</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                @else
                    <span style="color:#dc2626;font-weight:600;font-size:13px;">*Berkas Belum di Upload*</span>
                @endif
            </div>

            @if ($reviews->count() > 0)
                @foreach ($reviews as $version => $versionReviews)
                    <div class="pengajuan-block">
                        <span class="pengajuan-label">Pengajuan {{ $ordinalMap[$version] ?? 'Ke-'.$version }}</span>
                        @foreach ($roleOrder as $role)
                            @php $r = $versionReviews->first(fn($rv) => $rv->reviewer_type === $role); @endphp
                            @if ($r)
                                <div class="reviewer-row">
                                    <span class="reviewer-name">{{ $roleLabel[$role] }}</span>
                                    @if ($r->status === 'approve')
                                        <span class="review-badge badge-diverifikasi">Approve</span>
                                    @elseif ($r->status === 'ditolak')
                                        <span class="review-badge badge-ditolak">Ditolak</span>
                                        @if ($r->keterangan)
                                            <span class="review-keterangan">— {{ $r->keterangan }}</span>
                                        @endif
                                    @else
                                        <span class="review-badge badge-revisi">Revisi</span>
                                        @if ($r->keterangan)
                                            <span class="review-keterangan">— {{ $r->keterangan }}</span>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if (!$loop->last)
                        <hr style="border:none;border-top:1px dashed #e5e7eb;margin:8px 0;">
                    @endif
                @endforeach
            @else
                <div class="review-result">
                    <span style="font-size:13px;color:#9ca3af;font-style:italic;">Belum ada review</span>
                </div>
            @endif
        </div>
    @endforeach

    <div class="section-title" style="margin-top:32px;">🕐 History Progres Pengajuan</div>
    <div class="timeline">
        @forelse ($pengajuan->getProgresPengajuans->sortByDesc('created_at') as $progres)
            <div class="timeline-item">
                <div class="timeline-date">{{ $progres->created_at->format('l, d F Y H:i') }}</div>
                <div class="timeline-content">
                    <div>
                        <span class="timeline-status status-{{ strtolower(str_replace(' ','_',$progres->status)) }}">
                            {{ $progres->status }}
                        </span>
                    </div>
                    <div class="timeline-tahap">{{ str_replace('_',' ',$progres->tahap) }}</div>
                    <div class="timeline-keterangan">{{ $progres->keterangan }}</div>
                    <div class="timeline-by">Oleh: {{ $progres->getUser->name ?? '-' }}</div>
                </div>
            </div>
        @empty
            <p style="color:#9ca3af;font-style:italic;">Belum ada history</p>
        @endforelse
    </div>

</div>

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
            <button onclick="closeModal()" class="modal-button btn-close-modal">Tutup</button>
        </div>
    </div>
</div>

<script>
    let currentFileUrl = '';
    function showModal(value, title) {
        document.getElementById('modal').style.display = 'flex';
        document.getElementById("title").innerText = "File " + title;
        currentFileUrl = value;
        document.getElementById("container").src = '/pdfjs/web/viewer.html?file=' + encodeURIComponent(value);
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
        if (e.target === this) closeModal();
    });
</script>

@endsection