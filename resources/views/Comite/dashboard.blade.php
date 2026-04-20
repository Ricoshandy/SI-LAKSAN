@extends('Comite.Components.sidebar')

@section('main-content')

  <style>
/* 🌍 Global Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

/* 🌈 Body */
body {
  display: flex;
  background-image: url("{{ asset('image/Background2.png') }}");
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
  overflow-x: hidden;
}

/* 🔹 Tombol Toggle */
.toggle-btn {
  position: fixed;
  top: 20px;
  left: 20px;
  background: linear-gradient(135deg, #5ac8fa, #007aff);
  color: white;
  border: none;
  border-radius: 12px;
  width: 50px;
  height: 45px;
  font-size: 22px;
  font-weight: bold;
  cursor: pointer;
  z-index: 1002;
  box-shadow: 0 4px 10px rgba(0,0,0,0.25);
  transition: all 0.3s ease;
}

.toggle-btn:hover {
  background: linear-gradient(135deg, #007aff, #5ac8fa);
  transform: scale(1.1);
}

/* 🔹 Sidebar */
.sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  padding: 100px 10px 80px 10px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  border-radius: 0 20px 20px 0;
  background: linear-gradient(to right, rgba(223,231,249,0.784), rgba(94,217,254,0.526), rgba(223,231,249,0.784));
  background-size: 400% 400%;
  animation: gradient 10s ease infinite;
  transition: all 0.4s ease;
  z-index: 1001;
}

@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* 🔸 Closed State */
.sidebar.closed {
  transform: translateX(-100%);
  opacity: 0;
}

/* 🔹 Logo */
.sidebar .logo {
  text-align: center;
  margin-bottom: 40px;
}

.sidebar .logo img { width: 150px; }
.sidebar .logo p { font-weight: bold; font-size: 22px; padding-top: 20px; }

/* 🔹 Navigation */
.sidebar nav a {
  display: flex; align-items: center; gap: 10px;
  font-size: 17px; padding: 10px 15px;
  margin-bottom: 10px; color: #333;
  font-weight: bold; text-decoration: none;
  border-radius: 8px; transition: background 0.3s ease;
}

.sidebar nav a:hover, .sidebar nav a.active {
  background: #ffffff;
  font-weight: 1000;
}

/* 🔹 Profile */
.profile {
  display: flex; align-items: center; gap: 10px;
}

.profile img {
  width: 40px; height: 40px;
  border-radius: 50%; object-fit: cover;
}

.profile-info { display: flex; flex-direction: column; }
.profile-info span { font-size: 14px; font-weight: 600; }
.profile-info small { font-size: 12px; color: #888; }

/* 🔹 Main Content */
.main-content {
  flex: 1;
  padding: 30px;
  padding-left: 280px;
  display: flex;
  flex-direction: column;
  gap: 30px;
  transition: padding-left 0.4s ease;
}

.main-content.expanded {
  padding-left: 40px;
}

.main-content h1 {
  font-size: 35px;
  font-weight: 700;
}

/* 🔹 Cards */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.card {
  background: linear-gradient(to right,rgba(223,231,249,0.875),rgba(101,211,245,0.497),rgba(255,255,255,0.711));
  background-size: 400% 400%;
  animation: gradient 10s ease infinite;
  padding-top: 40px;
  padding-bottom: 40px;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.34);
  text-align: center;
  transition: all 0.3s;
}

.card:hover { transform: translateY(-5px); }

.card h2 { font-size: 20px; margin-bottom: 10px; }
.card p { font-size: 24px; font-weight: 700; color: #4f46e5; }

/* 🔹 Statistik Container */
.statistik-container {
  width: 95%;
  margin: 0 auto;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.3s ease;
  overflow: hidden;
  background: linear-gradient(to right,rgba(255,255,255,0.418),rgba(101,211,245,0.497),rgba(255,255,255,0.418));
}

.statistik-container:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.2);
}

.statistik-container canvas {
  width: 100% !important;
  max-width: 700px;
  height: 350px !important;
  margin: 0 auto;
}

/* 📱 Responsive */
@media (max-width: 768px) {
  .main-content { padding-left: 60px; }
}

@media (max-width: 480px) {
  .sidebar { transform: translateX(-100%); }
  .main-content { padding: 20px; }
}
  </style>


<h1>Dashboard Komite Integritas</h1>

{{-- Alert --}}
@includeWhen(View::exists('Comite.Components.alert'), 'Comite.Components.alert')

{{-- Cards --}}

    <div class="cards">
      <div class="card"><h2>Total Dosen</h2><p>{{ $totalDosen }}</p></div>
      <div class="card"><h2>Total Pengajuan</h2><p>{{ $totalPengajuan }}</p></div>
      <div class="card"><h2>Berkas Ditolak</h2><p>{{ $berkasDitolak }}</p></div>
      <div class="card"><h2>Berkas Diterima</h2><p>{{ $berkasDiterima }}</p></div>
    </div>

{{-- Statistik --}}
<div class="statistik-container">
  <h2>📈 Statistik Pengajuan (Komite)</h2>
  <canvas id="statistikChart"></canvas>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('statistikChart').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: @json($periodeLabels ?? ['Periode 1','Periode 2','Periode 3']),
      datasets: [{
        data: @json($jumlahPengajuan ?? [12, 19, 7]),
        backgroundColor: [
          'rgba(75,192,192,0.7)',
          'rgba(153,102,255,0.7)',
          'rgba(255,159,64,0.7)'
        ],
        borderRadius: 10,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      animation: {
        duration: 1500,
        easing: 'easeOutBounce'
      },
      scales: {
        y: { beginAtZero: true }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });
</script>

@endsection
