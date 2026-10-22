
@extends('Kepegawaian.Components.sidebar')

@section('title', 'Verifikasi Berkas - SI-LAKSAN')
@section('main-content')
@php
    // Controller pengajuan_review() telah mengirim $draftReviews (keyBy('key')).
    $formDetails = $pengajuan->getFormPengajuan
        ->getFormPengajuanDetails()
        ->orderBy('order', 'ASC')
        ->get();
    $draftMap = $draftReviews ?? collect();
@endphp

<style>
    .review-page, .review-page * { box-sizing: border-box; }
    .review-page { max-width: 1200px; margin: 0 auto; padding: 6px 4px 40px; color: #1e293b; }
    .review-page button, .review-page input, .review-page textarea { font: inherit; }
    .review-page button { cursor: pointer; }
    .review-page .page-title { font-size: clamp(23px, 3vw, 30px); line-height: 1.3; font-weight: 700; margin: 0 0 7px; }
    .review-page .page-subtitle { font-size: 13px; color: #475569; margin: 0 0 18px; }

    .review-page .applicant-card {
        background: #fff; display: flex; align-items: center; gap: 14px;
        padding: 17px 19px; border: 1px solid #e2e8f0; border-radius: 16px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, .07); margin-bottom: 22px;
    }
    .review-page .applicant-avatar {
        width: 46px; height: 46px; border-radius: 50%; display: grid; place-items: center;
        flex-shrink: 0; color: #0b70b8; background: #e6f4ff; font-size: 25px;
    }
    .review-page .applicant-details { min-width: 0; flex: 1; }
    .review-page .applicant-name { font-size: 14px; font-weight: 700; overflow-wrap: anywhere; }
    .review-page .applicant-email { font-size: 12px; color: #64748b; overflow-wrap: anywhere; }
    .review-page .applicant-tags { display: flex; gap: 7px; flex-wrap: wrap; margin-top: 8px; }
    .review-page .applicant-tag { padding: 5px 9px; border-radius: 7px; background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 700; }

    .review-page .section-heading { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin: 0 0 12px; }
    .review-page .section-heading h2 { font-size: 18px; margin: 0; font-weight: 700; }
    .review-page .section-hint { font-size: 12px; color: #475569; }
    .review-page .document-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .review-page .document-card {
        min-width: 0; background: #f7fff7; border: 1px solid #d8e9d9;
        border-radius: 14px; padding: 17px; display: flex; flex-direction: column; gap: 12px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
    }
    .review-page .document-top { display: flex; justify-content: space-between; align-items: start; gap: 12px; }
    .review-page .document-title { font-size: 14px; font-weight: 700; line-height: 1.5; overflow-wrap: anywhere; }
    .review-page .document-badge { flex-shrink: 0; font-size: 11px; font-weight: 700; border-radius: 999px; padding: 6px 10px; }
    .review-page .document-badge.pending { color: #64748b; background: #e2e8f0; }
    .review-page .document-badge.approved { color: #166534; background: #dcfce7; }
    .review-page .document-badge.revision { color: #991b1b; background: #fee2e2; }
    .review-page .document-note { font-size: 12px; color: #475569; overflow-wrap: anywhere; white-space: pre-wrap; }
    .review-page .document-note:empty { display: none; }
    .review-page .missing-file { color: #b91c1c; font-size: 12px; font-weight: 600; }
    .review-page .document-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: auto; }
    .review-page .btn {
        display: inline-flex; justify-content: center; align-items: center; gap: 7px;
        padding: 10px 14px; border-radius: 9px; border: 0; font-size: 12px;
        font-weight: 700; line-height: 1.3; text-decoration: none; transition: opacity .15s, transform .15s;
    }
    .review-page .btn:hover { opacity: .9; transform: translateY(-1px); }
    .review-page .btn:disabled { cursor: not-allowed; opacity: .55; transform: none; }
    .review-page .btn-blue { color: #fff; background: #2563eb; }
    .review-page .btn-green { color: #fff; background: #10b981; }
    .review-page .btn-muted { color: #334155; background: #e2e8f0; }
    .review-page .btn-red { color: #fff; background: #dc2626; }

    .review-page .final-panel {
        margin-top: 20px; padding: 18px 20px; background: #fff; border: 1px solid #e2e8f0;
        border-radius: 14px; display: flex; align-items: center; justify-content: space-between;
        gap: 16px; box-shadow: 0 4px 14px rgba(15, 23, 42, .05);
    }
    .review-page .final-panel strong { font-size: 14px; }
    .review-page .final-panel p { font-size: 12px; color: #64748b; margin-top: 5px; }
    .review-page .final-panel .btn { flex-shrink: 0; font-size: 13px; padding: 12px 18px; }
    .review-page .review-alert { border-radius: 10px; padding: 12px 15px; margin-bottom: 15px; font-size: 13px; font-weight: 600; }
    .review-page .review-alert.error { background: #fee2e2; color: #991b1b; }
    .review-page .review-alert.success { background: #dcfce7; color: #166534; }

    /* Popup pemeriksaan per dokumen */
    .review-page .review-modal {
        display: none; position: fixed; inset: 0; z-index: 2000; padding: 16px;
        background: rgba(15, 23, 42, .7); align-items: center; justify-content: center;
    }
    .review-page .review-modal-dialog {
        width: min(960px, 100%); max-height: calc(100dvh - 32px);
        background: #fff; border-radius: 16px; box-shadow: 0 24px 65px rgba(0, 0, 0, .28);
        overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 13px;
    }
    .review-page .modal-head { display: flex; align-items: start; justify-content: space-between; gap: 12px; }
    .review-page .modal-heading { margin: 0; font-size: 18px; font-weight: 700; overflow-wrap: anywhere; }
    .review-page .modal-subtitle { font-size: 12px; margin-top: 4px; color: #64748b; }
    .review-page .modal-close { width: 36px; height: 36px; border: 0; background: #ef4444; color: #fff; border-radius: 50%; font-size: 22px; flex-shrink: 0; }
    .review-page .viewer-box { background: #1f2937; border-radius: 10px; overflow: hidden; height: clamp(220px, 46vh, 480px); }
    .review-page .viewer-box iframe { border: 0; display: block; width: 100%; height: 100%; }
    .review-page .no-preview { height: 100%; display: flex; align-items: center; justify-content: center; color: #fff; padding: 18px; text-align: center; font-size: 13px; }
    .review-page .viewer-tools { display: flex; justify-content: flex-end; }
    .review-page .review-field-title { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; }
    .review-page .choice-row { display: flex; flex-wrap: wrap; gap: 10px; }
    .review-page .choice { position: relative; }
    .review-page .choice input { position: absolute; opacity: 0; pointer-events: none; }
    .review-page .choice span {
        display: inline-flex; padding: 10px 17px; border-radius: 9px; border: 2px solid #cbd5e1;
        background: #f8fafc; color: #334155; font-size: 13px; font-weight: 700; cursor: pointer;
    }
    .review-page .choice input:focus-visible + span { outline: 3px solid #93c5fd; outline-offset: 2px; }
    .review-page .choice input[value="approve"]:checked + span { background: #dcfce7; border-color: #16a34a; color: #166534; }
    .review-page .choice input[value="revisi"]:checked + span { background: #fee2e2; border-color: #dc2626; color: #991b1b; }
    .review-page .choice input:disabled + span { cursor: not-allowed; opacity: .5; }
    .review-page .review-textarea { display: block; width: 100%; min-height: 78px; resize: vertical; border-radius: 9px; padding: 11px 12px; border: 1px solid #cbd5e1; font-size: 13px; }
    .review-page .review-textarea:focus { border-color: #3b82f6; outline: 2px solid #bfdbfe; }
    .review-page .modal-error { font-size: 12px; color: #b91c1c; font-weight: 600; min-height: 16px; overflow-wrap: anywhere; }
    .review-page .modal-buttons { display: flex; gap: 10px; }
    .review-page .modal-buttons .btn { flex: 1; font-size: 13px; padding: 12px; }
    body.review-modal-open { overflow: hidden; }

    @media (max-width: 760px) {
        .review-page .document-grid { grid-template-columns: 1fr; }
        .review-page .applicant-card { align-items: flex-start; }
        .review-page .final-panel { align-items: stretch; flex-direction: column; }
        .review-page .final-panel .btn { width: 100%; }
        .review-page .review-modal { padding: 8px; }
        .review-page .review-modal-dialog { max-height: calc(100dvh - 16px); padding: 14px; }
        .review-page .viewer-box { height: clamp(190px, 34vh, 320px); }
        .review-page .modal-buttons { flex-direction: column; }
    }
</style>

<div class="review-page">
    <h1 class="page-title">Verifikasi Berkas Pengajuan</h1>
    <p class="page-subtitle">Periksa setiap dokumen melalui popup, kemudian simpan hasilnya satu per satu.</p>

    <section class="applicant-card" aria-label="Informasi dosen">
        <div class="applicant-avatar" aria-hidden="true">◉</div>
        <div class="applicant-details">
            <div class="applicant-name">{{ $pengajuan->getUser->name }}</div>
            <div class="applicant-email">{{ $pengajuan->getUser->email }}</div>
            <div class="applicant-tags">
                <span class="applicant-tag">Rumpun {{ $pengajuan->getFormPengajuan->rumpun }}</span>
                <span class="applicant-tag">Usulan ke {{ $pengajuan->getFormPengajuan->usul }}</span>
            </div>
        </div>
    </section>

    @if (session('error'))
        <div class="review-alert error" role="alert">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="review-alert success" role="status">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="review-alert error" role="alert">
            <strong>Periksa kembali:</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="section-heading">
        <h2>Dokumen Persyaratan</h2>
        <span class="section-hint">{{ $formDetails->count() }} dokumen</span>
    </div>

    {{-- Form terakhir tetap mengirim input sesuai validasi action_review() yang sudah dibuat. --}}
    <form id="finalReviewForm" method="POST" action="{{ route('action.review', ['pengajuanId' => $pengajuan->id]) }}">
        @csrf
        <div class="document-grid">
            @foreach ($formDetails as $detail)
                @php
                    $key = $detail->key;
                    $saved = $draftMap->get($key);
                    $hasFile = filled($pengajuan->{$key});
                    $fileUrl = $hasFile ? route('kepegawaian.pengajuan.file', [
                        'email' => $pengajuan->getUser->email,
                        'key' => $key,
                        'file' => basename($pengajuan->{$key}),
                    ]) : '';
                    $savedIsApprove = $saved && $saved->status === 'approve';
                @endphp

                <article class="document-card" data-key="{{ $key }}" data-file="{{ $fileUrl }}" data-has-file="{{ $hasFile ? '1' : '0' }}">
                    <input type="hidden" class="final-status" name="{{ $key }}" value="{{ $saved->status ?? '' }}">
                    <input type="hidden" class="final-note" name="{{ $key }}-keterangan" value="{{ $saved->keterangan ?? '' }}">

                    <div class="document-top">
                        <div class="document-title">{{ $detail->title }}</div>
                        <span class="document-badge {{ !$saved ? 'pending' : ($savedIsApprove ? 'approved' : 'revision') }}">
                            {{ !$saved ? 'Belum diperiksa' : ($savedIsApprove ? 'Terverifikasi' : 'Perlu revisi') }}
                        </span>
                    </div>

                    @unless ($hasFile)
                        <p class="missing-file">*Berkas belum di-upload*</p>
                    @endunless

                    <p class="document-note">@if ($saved && $saved->keterangan)Catatan: {{ $saved->keterangan }}@endif</p>

                    <div class="document-actions">
                        <button type="button" class="btn btn-blue js-open-review">Periksa / Verifikasi</button>
                        @if ($hasFile)
                            <a class="btn btn-green" href="{{ $fileUrl }}" target="_blank" rel="noopener">Tab Baru ↗</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="final-panel">
            <div>
                <strong>Finalisasi Pemeriksaan Berkas</strong>
                <p id="reviewProgress" aria-live="polite">Memeriksa kelengkapan hasil review...</p>
                <p>Hasil yang disimpan per dokumen adalah draft. Klik tombol ini hanya setelah semuanya selesai.</p>
            </div>
            <button type="submit" id="finalSubmitBtn" class="btn btn-green" disabled>Submit Review Berkas</button>
        </div>
    </form>

    {{-- Satu modal untuk semua dokumen; popup tetap menggunakan PDF.js lokal dari project. --}}
    <div id="reviewModal" class="review-modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="review-modal-dialog">
            <div class="modal-head">
                <div>
                    <h2 id="modalTitle" class="modal-heading">Verifikasi File</h2>
                    <p class="modal-subtitle">Periksa dokumen dan simpan keputusan sebelum melanjutkan.</p>
                </div>
                <button type="button" class="modal-close js-close-modal" aria-label="Tutup popup">&times;</button>
            </div>

            <div class="viewer-box">
                <iframe id="reviewFileFrame" title="Preview dokumen" src="about:blank"></iframe>
                <div id="noFilePreview" class="no-preview" style="display:none;">Berkas belum tersedia. Pilih Ditolak (Revisi) agar dosen dapat melengkapinya.</div>
            </div>
            <div class="viewer-tools">
                <a id="modalNewTab" class="btn btn-muted" href="#" target="_blank" rel="noopener">Buka file di tab baru ↗</a>
            </div>

            <form id="documentReviewForm">
                @csrf
                <label class="review-field-title">Hasil pemeriksaan</label>
                <div class="choice-row">
                    <label class="choice">
                        <input type="radio" name="status" value="approve" id="modalApprove">
                        <span>✓ Verifikasi</span>
                    </label>
                    <label class="choice">
                        <input type="radio" name="status" value="revisi" id="modalRevision">
                        <span>✕ Ditolak (Revisi)</span>
                    </label>
                </div>

                <label class="review-field-title" for="modalNote" style="margin-top:13px;">Catatan</label>
                <textarea id="modalNote" class="review-textarea" maxlength="2000" placeholder="Tuliskan alasan revisi atau catatan pemeriksaan (opsional)..."></textarea>
                <p id="modalMessage" class="modal-error" role="status" aria-live="polite"></p>

                <div class="modal-buttons">
                    <button type="submit" id="saveDocumentBtn" class="btn btn-blue">Submit &amp; Simpan Dokumen</button>
                    <button type="button" class="btn btn-muted js-close-modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- jQuery dipakai untuk AJAX. Jika bekerja offline, sediakan versi lokal. --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const saveDraftUrl = @json(route('action.review.draft', ['pengajuanId' => $pengajuan->id]));
    const pdfViewerUrl = @json(asset('pdfjs/web/viewer.html'));
    const $modal = $('#reviewModal');
    const $modalForm = $('#documentReviewForm');
    const $cards = $('.document-card');
    const $submitFinal = $('#finalSubmitBtn');
    const $modalMessage = $('#modalMessage');
    let activeCard = null;
    let saving = false;

    function savedCount() {
        return $cards.filter(function () {
            return $(this).find('.final-status').val() !== '';
        }).length;
    }

    function updateProgress() {
        const complete = savedCount();
        const total = $cards.length;
        $('#reviewProgress').text(complete + ' dari ' + total + ' dokumen sudah tersimpan.');
        $submitFinal.prop('disabled', saving || total === 0 || complete !== total);
    }

    function closeModal() {
        if (saving) return;
        $modal.css('display', 'none').attr('aria-hidden', 'true');
        $('#reviewFileFrame').attr('src', 'about:blank');
        $('body').removeClass('review-modal-open');
        activeCard = null;
    }

    $('.js-open-review').on('click', function () {
        if (saving) return;
        activeCard = $(this).closest('.document-card');
        const title = activeCard.find('.document-title').text().trim();
        const fileUrl = activeCard.attr('data-file') || '';
        const hasFile = activeCard.attr('data-has-file') === '1';
        const savedStatus = activeCard.find('.final-status').val();
        const savedNote = activeCard.find('.final-note').val();

        $('#modalTitle').text('Verifikasi File - ' + title);
        $('#modalNote').val(savedNote);
        $modalMessage.text('');
        $modalForm.find('input[name="status"]').prop('checked', false);
        if (savedStatus === 'approve') {
            $('#modalApprove').prop('checked', true);
        } else if (savedStatus === 'revisi' || savedStatus === 'ditolak') {
            $('#modalRevision').prop('checked', true);
        }

        // Dokumen yang belum diupload tidak dapat langsung diverifikasi.
        $('#modalApprove').prop('disabled', !hasFile);
        if (!hasFile && savedStatus === 'approve') {
            $('#modalApprove').prop('checked', false);
        }

        $('#reviewFileFrame').toggle(hasFile);
        $('#noFilePreview').toggle(!hasFile);
        $('#modalNewTab').toggle(hasFile);

        if (hasFile) {
            $('#modalNewTab').attr('href', fileUrl);
            const isPdf = /\.pdf(?:\?|$)/i.test(fileUrl);
            $('#reviewFileFrame').attr('src', isPdf
                ? pdfViewerUrl + '?file=' + encodeURIComponent(fileUrl)
                : fileUrl);
        } else {
            $('#reviewFileFrame').attr('src', 'about:blank');
            $('#modalNewTab').attr('href', '#');
        }

        $modal.css('display', 'flex').attr('aria-hidden', 'false');
        $('body').addClass('review-modal-open');
        $modal.find('.modal-close').trigger('focus');
    });

    $('.js-close-modal').on('click', closeModal);
    $modal.on('click', function (event) {
        if (event.target === this) closeModal();
    });
    $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && $modal.is(':visible')) closeModal();
    });

    $modalForm.on('submit', function (event) {
        event.preventDefault();
        if (saving || !activeCard) return;

        const status = $modalForm.find('input[name="status"]:checked').val();
        const note = $('#modalNote').val() || '';
        if (!status) {
            $modalMessage.text('Pilih Verifikasi atau Ditolak (Revisi) terlebih dahulu.');
            return;
        }
        if (note.length > 2000) {
            $modalMessage.text('Catatan maksimal 2000 karakter.');
            return;
        }

        const $card = activeCard;
        saving = true;
        $('#saveDocumentBtn').prop('disabled', true).text('Menyimpan...');
        $modalMessage.text('Sedang menyimpan keputusan dokumen...');
        updateProgress();

        $.ajax({
            url: saveDraftUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                _token: $modalForm.find('input[name="_token"]').val(),
                key: $card.attr('data-key'),
                status: status,
                keterangan: note
            }
        }).done(function (response) {
            if (!response || response.success !== true) {
                $modalMessage.text(response && response.message ? response.message : 'Gagal menyimpan dokumen.');
                return;
            }

            // Input tersembunyi ini diperlukan oleh validasi action_review() saat final Submit.
            $card.find('.final-status').val(status);
            $card.find('.final-note').val(note);
            const $badge = $card.find('.document-badge');
            $badge.removeClass('pending approved revision')
                .addClass(status === 'approve' ? 'approved' : 'revision')
                .text(status === 'approve' ? 'Terverifikasi' : 'Perlu revisi');
            $card.find('.document-note').text(note ? 'Catatan: ' + note : '');
            $modalMessage.text('✓ Berhasil tersimpan di database.');
            setTimeout(function () {
                if (activeCard && activeCard[0] === $card[0]) closeModal();
            }, 600);
        }).fail(function (xhr) {
            const message = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Gagal menyimpan. Periksa koneksi atau log Laravel.';
            $modalMessage.text('✕ ' + message);
            console.error('AJAX saveDraft gagal:', xhr.status, xhr.responseText);
        }).always(function () {
            saving = false;
            $('#saveDocumentBtn').prop('disabled', false).text('Submit & Simpan Dokumen');
            updateProgress();
        });
    });

    $('#finalReviewForm').on('submit', function (event) {
        if (saving || $cards.length === 0 || savedCount() !== $cards.length) {
            event.preventDefault();
            alert('Semua dokumen harus berhasil disimpan sebelum finalisasi.');
            return;
        }
        if (!confirm('Finalisasi seluruh review berkas? Setelah ini pengajuan akan diproses ke tahap berikutnya atau dikembalikan untuk revisi.')) {
            event.preventDefault();
            return;
        }
        $submitFinal.prop('disabled', true).text('Memproses...');
    });

    updateProgress();
});
</script>
@endsection
