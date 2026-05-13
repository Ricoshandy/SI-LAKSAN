@extends('Dosen.Components.sidebar')
@section('main-content')

    <div class="header">
        <h1>Form Revisi Usul Kenaikan Jabatan</h1>
    </div>
    <div style="padding: 0 28px;">
        
        @if ($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 style="text-align: left; font-size: 24px; color: #333; background-color:rgb(136, 239, 255); border-radius: 8px; padding: 10px 10px;">Rumpun: {{ $pengajuan->getFormPengajuan->rumpun }}</h1>
        <h1 style="text-align: left; font-size: 24px; color: #333; background-color:rgb(190, 245, 255); border-radius: 8px; padding: 10px 10px;">Usulan: Ke {{ $pengajuan->getFormPengajuan->usul }}</h1>
        <p style="text-align: left; font-size: 16px; color: #333; background-color: white; border-radius: 8px; padding: 10px 10px;">Pengajuan dapat disimpan terlebih dahulu tanpa harus melengkapi semua form</p>


        <form action="{{ route('pengajuan.edit.submit', ['id' => $pengajuan->id]) }}" method="POST" enctype="multipart/form-data" style="max-width: 100%; margin-top: 20px;">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; width: 100%;">

            @foreach ($pengajuan->getFormPengajuan->getFormPengajuanDetails()->orderBy('order', 'ASC')->get() as $key)
                @php
                    $column = $key->key;    
                @endphp

                @php
                
                $lastVersion = $pengajuan->getReviewPengajuans()
                        ->where('key', $key->key)
                    ->max('version');

                $reviewPengajuan = $pengajuan->getReviewPengajuans()
                    ->where('key', $key->key)
                    ->where('version', $lastVersion)
                    ->first();
                @endphp

                <div class="file-upload-container" id="container-{{ $key->key }}" style="margin-bottom: 16px; {{ !empty($reviewPengajuan) && $reviewPengajuan->status === 'revisi' ? 'background-color:rgb(248, 113, 113);' : ($pengajuan->$column == null ? 'background-color:rgb(236, 252, 255);' : 'background-color:rgb(255, 255, 255, 0.5);') }} border-radius: 8px; padding: 14px 14px;">
    <label for="{{ $key->key }}" style="font-weight: bold; display: block; margin-bottom: 5px;">
        {{ $key->title }}
    </label>

                    @if (!empty($key->description))
                        <p style="font-size: 14px; color: #666; margin-bottom: 5px;">
                            {{ $key->description }}
                        </p>
                    @endif

                    @if ( !empty($reviewPengajuan) && !$reviewPengajuan->is_verified)
                        <p>Perlu Perbaikan : {{ $reviewPengajuan->keterangan }}</p>
                    @endif  

                    @if ($pengajuan->$column !== null)
                        <iframe src="/{{$pengajuan->$column}}" frameborder="0" height="100px"></iframe>
                        <div>
                            <input type="checkbox" class="update-checkbox" name="" id="check{{ $key->key }}" data-target="{{ $key->key }}">
                            <label for="check{{ $key->key }}">Update Berkas?</label>
                        </div>
                        <input type="file" name="{{ $key->key }}" id="{{ $key->key }}" class="file-input" data-container="container-{{ $key->key }}" 
                            style="padding: 6px 0; display: none; width: 100%; font-size: 18px; color: #111827; border: 1px solid #d1d5db; border-radius: 8px; cursor: pointer; background-color: #f9fafb;">
                    @else
                    
                    <input type="file" name="{{ $key->key }}" id="{{ $key->key }}" class="file-input" data-container="container-{{ $key->key }}" 
                        style="padding: 6px 0; display: block; width: 100%; font-size: 18px; color: #111827; border: 1px solid #d1d5db; border-radius: 8px; cursor: pointer; background-color: #f9fafb;">
                    @endif
                </div>
            @endforeach

            </div>

            <div style="display: flex; justify-content: space-around; width: 100%;">

                <button type="submit" style="background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                    Simpan Pengajuan
                </button>

                <button type="submit" name="pengajuan" value="true" style="background-color:rgb(14, 121, 0); color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
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
                    container.style.backgroundColor = "lightgreen"; // Warna hijau jika file dipilih
                } else {
                    container.style.backgroundColor = "rgb(245, 255, 245)"; // Warna default
                }
            });
        });

        document.querySelectorAll(".update-checkbox").forEach(checkbox => {
            checkbox.addEventListener("change", function() {
                let fileInput = document.getElementById(this.dataset.target);
                fileInput.style.display = this.checked ? "block" : "none";
            });
        });
        
    });
</script>

@endsection