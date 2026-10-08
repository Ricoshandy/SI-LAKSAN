@extends('Kepegawaian.Components.sidebar')
@section('main-content')

<style>
.sister-page{width:min(1120px,calc(100% - 40px));margin:0 auto 48px;color:#172033}.page-title{margin:0;font-size:clamp(26px,3vw,36px)}.page-subtitle{margin:6px 0 22px;color:#536176}.panel{background:rgba(255,255,255,.94);border:1px solid rgba(255,255,255,.72);border-radius:18px;box-shadow:0 12px 32px rgba(9,57,104,.13)}
.applicant{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:20px 22px;margin-bottom:18px}.applicant-main{display:flex;align-items:center;gap:14px;min-width:0}.avatar{width:52px;height:52px;flex:0 0 52px;display:grid;place-items:center;border-radius:15px;color:#fff;background:linear-gradient(135deg,#2563eb,#06b6d4)}.avatar svg{width:29px}.applicant-name{margin:0 0 4px;font-size:17px;font-weight:750}.applicant-id{margin:0;color:#64748b;font-size:13px;overflow-wrap:anywhere}.tags{display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap}.tag{padding:7px 11px;border-radius:999px;background:#e7f7ff;color:#075985;font-size:12px;font-weight:750}
.download{display:flex;align-items:center;gap:14px;padding:18px 20px;margin-bottom:18px}.square-icon{width:44px;height:44px;flex:0 0 44px;display:grid;place-items:center;border-radius:13px;background:#e8f7ff;color:#0284c7;font-size:20px}.download .square-icon{background:#fff4d6;color:#d97706}.download-info{min-width:0;flex:1}.download-info strong{display:block;overflow-wrap:anywhere}.download-info span{display:block;margin-top:3px;color:#64748b;font-size:13px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:42px;padding:10px 16px;border:0;border-radius:10px;font:inherit;font-size:14px;font-weight:750;text-decoration:none;cursor:pointer;transition:.2s}.btn:hover{transform:translateY(-1px)}.btn-primary{color:#fff;background:linear-gradient(135deg,#2563eb,#06b6d4);box-shadow:0 7px 15px rgba(37,99,235,.22)}.btn-secondary{color:#075985;background:#eef7ff;border:1px solid #bae6fd}.btn-danger{color:#fff;background:#dc2626;box-shadow:0 7px 15px rgba(220,38,38,.18)}.btn:disabled{opacity:.55;cursor:not-allowed;transform:none}
.documents{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-bottom:18px}.document-card{padding:18px;min-width:0}.document-head{display:flex;align-items:center;gap:12px}.document-title{flex:1;min-width:0}.document-title h3{margin:0 0 4px;font-size:15px}.document-title p{margin:0;color:#64748b;font-size:12px}.document-list{display:none;max-height:280px;overflow-y:auto;margin-top:14px;padding-top:12px;border-top:1px solid #e5e7eb}.document-list.open{display:grid;gap:7px}.document-link{width:100%;padding:9px 10px;border:1px solid #dbeafe;border-radius:8px;background:#f8fbff;color:#1e3a5f;text-align:left;font:inherit;font-size:12px;cursor:pointer}.document-link:hover{background:#eaf5ff}
.action-panel{padding:22px}.action-panel h2{margin:0 0 5px;font-size:20px}.action-panel>p{margin:0 0 18px;color:#64748b;font-size:14px}.action-grid{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:14px;align-items:end}.file-field label{display:block;margin-bottom:7px;font-weight:700}.file-field input{width:100%;padding:11px;border:1px solid #cbd5e1;border-radius:10px;background:#f8fafc;box-sizing:border-box}.file-help{margin:6px 0 0;color:#64748b;font-size:12px}.file-message{display:none;margin:8px 0 0;padding:8px 10px;border-radius:8px;font-size:13px}.file-message.error{display:block;background:#fee2e2;color:#991b1b}.file-message.success{display:block;background:#dcfce7;color:#166534}.reject-row{display:flex;justify-content:flex-end;margin-top:16px;padding-top:16px;border-top:1px solid #e5e7eb}
.modal-overlay{position:fixed;inset:0;z-index:9999;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(15,23,42,.62);backdrop-filter:blur(3px)}.modal-dialog{position:relative;width:min(760px,100%);max-height:calc(100vh - 40px);padding:22px;overflow:auto;border-radius:16px;background:#fff;box-shadow:0 24px 70px rgba(0,0,0,.3)}.modal-dialog.small{width:min(520px,100%)}.modal-dialog h2{margin:0 44px 16px 0;font-size:20px}.modal-close{position:absolute;top:14px;right:14px;width:34px;height:34px;border:0;border-radius:50%;background:#eef2f7;font-size:20px;cursor:pointer}.preview-frame{width:100%;height:min(66vh,620px);border:1px solid #e5e7eb;border-radius:10px}.reject-textarea{width:100%;min-height:105px;padding:11px;border:1px solid #cbd5e1;border-radius:10px;box-sizing:border-box;resize:vertical}
@media(max-width:900px){.documents{grid-template-columns:1fr}}@media(max-width:768px){.sister-page{width:100%;margin-bottom:28px}.applicant{align-items:flex-start;flex-direction:column}.tags{justify-content:flex-start}.download{align-items:flex-start;flex-wrap:wrap}.download .btn,.action-grid .btn,.reject-row .btn{width:100%}.action-grid{grid-template-columns:1fr}}
</style>

<main class="sister-page">
    <h1 class="page-title">Unggah SK-Jabatan</h1>
    <p class="page-subtitle">Periksa dokumen dan selesaikan penerbitan SK pengajuan.</p>

    @if($errors->any())
        <div class="panel" style="padding:14px 18px;margin-bottom:18px;background:#fff1f2;color:#9f1239">
            <strong>Proses belum berhasil:</strong>
            <ul style="margin:7px 0 0 18px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="panel applicant">
        <div class="applicant-main">
            <div class="avatar"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.68 0-5.22-.59-7.5-1.65Z"/></svg></div>
            <div><p class="applicant-name">{{ $pengajuan->getUser->name }}</p><p class="applicant-id">{{ $pengajuan->getUser->email }}</p></div>
        </div>
        <div class="tags">
            <span class="tag">Rumpun {{ $pengajuan->getFormPengajuan->rumpun }}</span>
            <span class="tag">Usulan {{ str_replace('_',' ',$pengajuan->getFormPengajuan->usul) }}</span>
            <span class="tag">{{ str_replace('_',' ',$pengajuan->tahap) }}</span>
        </div>
    </section>

    <section class="panel download">
        <div class="square-icon">📦</div>
        <div class="download-info"><strong>Pengajuan-{{ $pengajuan->getUser->email }}.zip</strong><span>Paket seluruh dokumen pengajuan dan berita acara yang tersedia.</span></div>
        <a class="btn btn-primary" href="{{ route('download.pengajuan',['id_pengajuan'=>$pengajuan->id]) }}">⬇ Unduh ZIP</a>
    </section>

    <section class="documents">
        <article class="panel document-card">
            <div class="document-head">
                <div class="square-icon">📄</div>
                <div class="document-title"><h3>Berkas Pengajuan Dosen</h3><p>Dokumen persyaratan yang diunggah</p></div>
                <button class="btn btn-secondary" type="button" id="accordion-button" aria-expanded="false">Lihat</button>
            </div>
            <div id="accordion-content" class="document-list">
                @php $hasDocument=false; @endphp
                @foreach($pengajuan->getFormPengajuan->getFormPengajuanDetails()->orderBy('order','ASC')->get() as $detail)
                    @php $column=$detail->key; @endphp
                    @if($pengajuan->$column)
                        @php $hasDocument=true; @endphp
                        <button type="button" class="document-link" data-preview-url="{{ route('kepegawaian.pengajuan.file',['email'=>$pengajuan->getUser->email,'key'=>$column,'file'=>basename($pengajuan->$column)]) }}" data-preview-title="{{ $detail->title }}">{{ $detail->title }}</button>
                    @endif
                @endforeach
                @if(!$hasDocument)<span>Belum ada dokumen.</span>@endif
            </div>
        </article>

        <article class="panel document-card"><div class="document-head">
            <div class="square-icon">✓</div><div class="document-title"><h3>Sidang Komite</h3><p>Berita acara hasil sidang komite</p></div>
            @if($pengajuan->sidangKomiteTerakhir)
                <button type="button" class="btn btn-secondary" data-preview-url="{{ url('sidang/'.$pengajuan->getUser->email.'/'.basename($pengajuan->sidangKomiteTerakhir->berita_acara)) }}" data-preview-title="Berita Acara Sidang Komite">Lihat</button>
            @else<span class="tag" style="background:#f1f5f9;color:#64748b">Belum tersedia</span>@endif
        </div></article>

        <article class="panel document-card"><div class="document-head">
            <div class="square-icon">✓</div><div class="document-title"><h3>Sidang Senat</h3><p>Berita acara hasil sidang senat</p></div>
            @if($pengajuan->sidangSenatTerakhir)
                <button type="button" class="btn btn-secondary" data-preview-url="{{ url('sidang/'.$pengajuan->getUser->email.'/'.basename($pengajuan->sidangSenatTerakhir->berita_acara)) }}" data-preview-title="Berita Acara Sidang Senat">Lihat</button>
            @else<span class="tag" style="background:#f1f5f9;color:#64748b">Belum tersedia</span>@endif
        </div></article>
    </section>

    <section class="panel action-panel">
        <h2>Penerbitan SK Kenaikan Jabatan</h2>
        <p>Unggah SK yang telah diterbitkan atau tolak pengajuan dengan alasan yang jelas.</p>
        <form id="sk-form" enctype="multipart/form-data" method="POST" action="{{ route('kepegawaian.pengajuan.approved',['id_pengajuan'=>$pengajuan->id]) }}">
            @csrf
            <div class="action-grid">
                <div class="file-field"><label for="sk">File SK Kenaikan Jabatan</label><input type="file" required name="sk" id="sk" accept="application/pdf,.pdf"><p class="file-help">Format PDF, maksimal 1 MB.</p><p id="sk-message" class="file-message"></p></div>
                <button id="sk-submit" type="submit" class="btn btn-primary" disabled>⬆ Upload SK</button>
            </div>
        </form>
        <div class="reject-row"><button type="button" id="open-reject" class="btn btn-danger">Tolak Pengajuan</button></div>
    </section>
</main>

<div id="reject-modal" class="modal-overlay"><div class="modal-dialog small">
    <button type="button" class="modal-close" data-close="reject-modal">×</button><h2>Tolak Pengajuan?</h2>
    <form action="{{ route('kepegawaian.pengajuan.rejected',['id_pengajuan'=>$pengajuan->id]) }}" method="POST">@csrf
        <p>Pengajuan milik <strong>{{ $pengajuan->getUser->name }}</strong> akan ditolak. Tuliskan alasannya agar diketahui Dosen.</p>
        <textarea class="reject-textarea" name="keterangan" maxlength="2000" required placeholder="Tuliskan alasan penolakan..."></textarea>
        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px"><button type="button" class="btn btn-secondary" data-close="reject-modal">Batal</button><button type="submit" class="btn btn-danger">Ya, Tolak</button></div>
    </form>
</div></div>

<div id="preview-modal" class="modal-overlay"><div class="modal-dialog">
    <button type="button" class="modal-close" data-close="preview-modal">×</button><h2 id="preview-title">Preview Dokumen</h2>
    <iframe id="preview-frame" class="preview-frame" src="about:blank" title="Preview dokumen"></iframe>
</div></div>

<script>
document.addEventListener('DOMContentLoaded',()=>{
    const accordion=document.getElementById('accordion-content'), accordionBtn=document.getElementById('accordion-button');
    const preview=document.getElementById('preview-modal'), frame=document.getElementById('preview-frame'), previewTitle=document.getElementById('preview-title');
    const reject=document.getElementById('reject-modal'), sk=document.getElementById('sk'), skBtn=document.getElementById('sk-submit'), message=document.getElementById('sk-message');
    accordionBtn?.addEventListener('click',()=>{const open=accordion.classList.toggle('open');accordionBtn.textContent=open?'Tutup':'Lihat';accordionBtn.setAttribute('aria-expanded',String(open))});
    document.querySelectorAll('[data-preview-url]').forEach(button=>button.addEventListener('click',()=>{previewTitle.textContent=button.dataset.previewTitle||'Preview Dokumen';frame.src=button.dataset.previewUrl;preview.style.display='flex'}));
    document.getElementById('open-reject')?.addEventListener('click',()=>reject.style.display='flex');
    document.querySelectorAll('[data-close]').forEach(button=>button.addEventListener('click',()=>closeModal(document.getElementById(button.dataset.close))));
    document.querySelectorAll('.modal-overlay').forEach(modal=>modal.addEventListener('click',event=>{if(event.target===modal)closeModal(modal)}));
    function closeModal(modal){if(!modal)return;modal.style.display='none';if(modal===preview)frame.src='about:blank'}
    sk?.addEventListener('change',()=>{
        const file=sk.files[0];skBtn.disabled=true;message.className='file-message';message.textContent='';if(!file)return;
        const pdf=file.type==='application/pdf'||file.name.toLowerCase().endsWith('.pdf');
        if(!pdf){message.textContent='File SK wajib berformat PDF.';message.classList.add('error');sk.value='';return}
        if(file.size>1024*1024){message.textContent='Ukuran maksimal 1 MB. File dipilih: '+(file.size/1024/1024).toFixed(2)+' MB.';message.classList.add('error');sk.value='';return}
        message.textContent='File siap diunggah: '+file.name;message.classList.add('success');skBtn.disabled=false;
    });
});
</script>
@endsection
