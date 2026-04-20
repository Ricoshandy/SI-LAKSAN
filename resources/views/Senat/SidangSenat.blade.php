@extends('Senat.Components.sidebar')
@section('main-content')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .sidang-container {
        padding: 32px;
        background: transparent;
        min-height: 100vh;
    }

    .page-header {
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 12px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
    }

    .page-subtitle {
        font-size: 16px;
        color: #475569;
        margin-bottom: 24px;
    }

    /* Profile Card */
    .profile-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 40px rgba(30, 64, 175, 0.15);
        margin-bottom: 24px;
        animation: fadeInUp 0.5s ease;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        flex-shrink: 0;
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

    /* View Berkas Button */
    .view-berkas-section {
        margin-bottom: 24px;
    }

    .view-berkas-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 24px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
    }

    .view-berkas-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .view-berkas-btn svg {
        width: 20px;
        height: 20px;
    }

    /* Form Card */
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 10px 40px rgba(30, 64, 175, 0.15);
        animation: fadeInUp 0.6s ease;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-label {
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 12px;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        width: 100%;
        padding: 14px;
        border: 2px dashed #3b82f6;
        border-radius: 12px;
        background: #f0f9ff;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .file-input:hover {
        border-color: #1e40af;
        background: #e0f2fe;
    }

    .file-input::file-selector-button {
        padding: 10px 20px;
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        margin-right: 12px;
        transition: all 0.3s ease;
    }

    .file-input::file-selector-button:hover {
        transform: scale(1.05);
    }

    /* Radio Buttons */
    .radio-group {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .radio-option {
        flex: 1;
        min-width: 160px;
    }

    .radio-label {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        background: #f0f9ff;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        gap: 12px;
    }

    .radio-label:hover {
        border-color: #3b82f6;
        background: #dbeafe;
    }

    .radio-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .radio-option input[type="radio"] {
        display: none;
    }

    .radio-option input[type="radio"]:checked + label {
        border-color: #3b82f6;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(30, 64, 175, 0.15) 100%);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .radio-option.lulus input[type="radio"]:checked + label {
        border-color: #10b981;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%);
    }

    .radio-option.tidak-lulus input[type="radio"]:checked + label {
        border-color: #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
    }

    /* Submit Button */
    .submit-section {
        margin-top: 32px;
        text-align: center;
    }

    .submit-btn {
        padding: 16px 48px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .submit-btn:active {
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidang-container {
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .profile-card,
        .form-card {
            padding: 20px;
        }

        .profile-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .radio-group {
            flex-direction: column;
        }

        .radio-option {
            min-width: 100%;
        }

        .submit-btn {
            width: 100%;
        }
    }
</style>

<div class="sidang-container">
    <div class="page-header">
        <h1 class="page-title">🏛️ Sidang Senat</h1>
        <p class="page-subtitle">Proses verifikasi dan sidang pengajuan kenaikan jabatan</p>
    </div>

    <!-- Profile Card -->
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

    <!-- View Berkas Button -->
    <div class="view-berkas-section">
        <a href="{{ route('senat.pengajuan.view', ['id' => $pengajuan->id]) }}" class="view-berkas-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            View Berkas Terverifikasi
        </a>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('action.sidang.senat', ['pengajuanId' => $pengajuan->id]) }}" enctype="multipart/form-data">
            @csrf

            <!-- Upload Berita Acara -->
            <div class="form-section">
                <label class="form-label">📄 Upload Berkas Sidang (Berita Acara)</label>
                <input type="file" name="berita_acara" class="file-input" required accept=".pdf,.doc,.docx">
                <input type="hidden" name="sidang" value="SENAT">
            </div>

            <!-- Hasil Sidang -->
            <div class="form-section">
                <label class="form-label">✅ Hasil Sidang</label>
                <div class="radio-group">
                    <div class="radio-option lulus">
                        <input type="radio" name="status" value="LULUS" id="lulus" required>
                        <label for="lulus" class="radio-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px; color: #10b981;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>LULUS</span>
                        </label>
                    </div>

                    <div class="radio-option tidak-lulus">
                        <input type="radio" name="status" value="TIDAK_LULUS" id="tidaklulus" required>
                        <label for="tidaklulus" class="radio-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px; color: #ef4444;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>TIDAK LULUS</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="submit-section">
                <button type="submit" class="submit-btn">
                    🚀 Submit Berkas Sidang
                </button>
            </div>
        </form>
    </div>
</div>

@endsection