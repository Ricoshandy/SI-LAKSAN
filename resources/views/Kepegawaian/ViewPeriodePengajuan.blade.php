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

    /* Search Box */
    .search-wrapper {
        display: flex;
        justify-content: flex-end;
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

    /* Rumpun Badges */
    .badge-rumpun-agama {
        background-color: #ffebcc;
        color: #d35400;
    }

    .badge-rumpun-umum {
        background-color: #e7f1ff;
        color: #007bff;
    }

    /* Usul Badges */
    .badge-usul {
        font-style: italic;
    }

    .badge-usul-asisten_ahli {
        background-color: #6f42c1;
    }

    .badge-usul-lektor {
        background-color: #007bff;
    }

    .badge-usul-lektor_kepala {
        background-color: #17a2b8;
    }

    .badge-usul-guru_besar {
        background-color: #dc3545;
    }

    /* Status Badges */
    .badge-status {
        border-radius: 10px;
    }

    .badge-status-draft {
        background-color: #6c757d;
    }

    .badge-status-baru {
        background-color: #007bff;
    }

    .badge-status-dalam_proses {
        background-color: #17a2b8;
    }

    .badge-status-disetujui {
        background-color: #28a746c9;
    }

    .badge-status-ditolak {
        background-color: #dc3546d6;
    }

    .badge-status-revisi {
        background-color: #d9ab20d3;
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: rgba(255, 255, 255, 0.9);
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .empty-state-text {
        color: #666;
        font-size: 16px;
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

    /* Responsive untuk Mobile */
    @media (max-width: 768px) {
        .search-wrapper {
            justify-content: stretch;
        }

        .search-container {
            width: 100%;
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
    }
</style>

<div class="header">
    <div class="header-left">
        <h1>List Pengajuan Per-periode</h1>
        <p style="font-size: 14px;">Berikut daftar seluruh pengajuan kenaikan jabatan yang perlu ditinjau</p>
    </div>
</div>

<div class="content-wrapper">
    <!-- SEARCH BOX -->
    <div class="search-wrapper">
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" id="searchInput" class="search-box" placeholder="Cari data...">
        </div>
    </div>

    <!-- TABLE CONTAINER -->
    <div class="table-container">
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
                    </tr>
                </thead>

                <tbody id="tableBody">
                    @forelse ($pengajuans as $i => $p)
                    <tr class="data-row">
                        <td>{{ $i + 1 }}</td>
                        <td class="dosen-name">{{ $p->user->name }}</td>
                        <td class="text-center rumpun-data">
                            @if($p->rumpun_ilmu)
                                <span class="badge {{ strtoupper($p->rumpun_ilmu) == 'AGAMA' ? 'badge-rumpun-agama' : 'badge-rumpun-umum' }}">
                                    {{ strtoupper($p->rumpun_ilmu) }}
                                </span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td class="text-center usul-data">
                            @if($p->jenis_usulan)
                                <span class="badge badge-usul badge-usul-{{ strtolower(str_replace(' ', '_', $p->jenis_usulan)) }}">
                                    {{ strtoupper(str_replace('_', ' ', $p->jenis_usulan)) }}
                                </span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td class="text-center status-data">
                            <span class="badge badge-status badge-status-{{ strtolower(str_replace(' ', '_', $p->status)) }}">
                                {{ strtoupper(str_replace('_', ' ', $p->status)) }}
                            </span>
                        </td>
                        <td class="text-center tahap-data">{{ $p->tahap }}</td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>

            @if($pengajuans->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <div class="empty-state-text">Belum ada pengajuan pada periode ini</div>
            </div>
            @else
            <div class="no-results" id="noResults">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <div>Tidak ada data yang cocok dengan pencarian Anda</div>
            </div>
            @endif
        </div>

        <!-- CARD VIEW untuk Mobile -->
        <div class="card-view" id="cardView">
            @forelse ($pengajuans as $i => $p)
            <div class="card-item card-data" 
                 data-name="{{ strtolower($p->user->name) }}" 
                 data-rumpun="{{ strtolower($p->rumpun_ilmu ?? '') }}" 
                 data-status="{{ strtolower($p->status) }}"
                 data-usul="{{ strtolower($p->jenis_usulan ?? '') }}"
                 data-tahap="{{ strtolower($p->tahap) }}">
                <div class="card-header">
                    <span class="card-dosen">{{ $p->user->name }}</span>
                    <span class="badge badge-status badge-status-{{ strtolower(str_replace(' ', '_', $p->status)) }}">
                        {{ strtoupper(str_replace('_', ' ', $p->status)) }}
                    </span>
                </div>
                <div class="card-row">
                    <span class="card-label">Rumpun</span>
                    @if($p->rumpun_ilmu)
                        <span class="badge {{ strtoupper($p->rumpun_ilmu) == 'AGAMA' ? 'badge-rumpun-agama' : 'badge-rumpun-umum' }}">
                            {{ strtoupper($p->rumpun_ilmu) }}
                        </span>
                    @else
                        <span>-</span>
                    @endif
                </div>
                <div class="card-row">
                    <span class="card-label">Usul</span>
                    @if($p->jenis_usulan)
                        <span class="badge badge-usul badge-usul-{{ strtolower(str_replace(' ', '_', $p->jenis_usulan)) }}">
                            {{ strtoupper(str_replace('_', ' ', $p->jenis_usulan)) }}
                        </span>
                    @else
                        <span>-</span>
                    @endif
                </div>
                <div class="card-row">
                    <span class="card-label">Tahap</span>
                    <span style="font-weight: 600; font-size: 13px;">{{ $p->tahap }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <div class="empty-state-text">Belum ada pengajuan pada periode ini</div>
            </div>
            @endforelse

            @if(!$pengajuans->isEmpty())
            <div class="no-results" id="noResultsMobile">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <div>Tidak ada data yang cocok dengan pencarian Anda</div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const tableRows = document.querySelectorAll('.data-row');
const cardItems = document.querySelectorAll('.card-data');
const noResults = document.getElementById('noResults');
const noResultsMobile = document.getElementById('noResultsMobile');

if (searchInput && tableRows.length > 0) {
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
            const name = card.dataset.name || '';
            const rumpun = card.dataset.rumpun || '';
            const status = card.dataset.status || '';
            const usul = card.dataset.usul || '';
            const tahap = card.dataset.tahap || '';
            
            if (name.includes(searchTerm) || 
                rumpun.includes(searchTerm) || 
                status.includes(searchTerm) || 
                usul.includes(searchTerm) || 
                tahap.includes(searchTerm)) {
                card.classList.remove('hidden');
                visibleCardCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (noResults) {
            if (visibleRowCount === 0) noResults.classList.add('show');
            else noResults.classList.remove('show');
        }

        if (noResultsMobile) {
            if (visibleCardCount === 0) noResultsMobile.classList.add('show');
            else noResultsMobile.classList.remove('show');
        }
    });
}
</script>

@endsection