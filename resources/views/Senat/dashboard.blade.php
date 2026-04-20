@extends('Senat.Components.sidebar')
@section('main-content')

<h1>Dashboard Senat</h1>

{{-- Alert --}}
@includeWhen(View::exists('Senat.Components.alert'), 'Senat.Components.alert')

<div class="cards">
  <div class="card">
    <h2>Total Dosen</h2>
    <p>{{ $totalDosen ?? 0 }}</p>
  </div>
  <div class="card">
    <h2>Total Pengajuan</h2>
    <p>{{ $totalPengajuan ?? 0 }}</p>
  </div>
  <div class="card">
    <h2>Berkas Ditolak</h2>
    <p>{{ $berkasDitolak ?? 0 }}</p>
  </div>
  <div class="card">
    <h2>Berkas Diterima</h2>
    <p>{{ $berkasDiterima ?? 0 }}</p>
  </div>
</div>

<div class="statistik-container">
  <h2>📈 Statistik Pengajuan Per Periode</h2>
  <canvas id="statistikChart"></canvas>
</div>

<script>
  const ctx = document.getElementById('statistikChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: @json($periodeLabels ?? []),
      datasets: [{
        data: @json($jumlahPengajuan ?? []),
        backgroundColor: [
          'rgba(75,192,192,0.7)',
          'rgba(153,102,255,0.7)',
          'rgba(255,159,64,0.7)',
          'rgba(255,99,132,0.7)',
          'rgba(54,162,235,0.7)',
          'rgba(255,206,86,0.7)'
        ],
        borderRadius: 10,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      animation: { duration: 2000, easing: 'easeOutBounce' },
      scales: { y: { beginAtZero: true } },
      plugins: { legend: { display: false } }
    }
  });
</script>

@endsection