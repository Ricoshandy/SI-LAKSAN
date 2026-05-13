{{-- resources/views/Kepegawaian/Components/sidebar.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Dashboard Kepegawaian')</title>
  <link rel="icon" type="image/png" href="{{ asset('image/Logo UIN.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    /* 🌍 Reset & Base */
    * {
      margin: 0; padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      background-image: url("{{ asset('image/Background2.png') }}");
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      align-items: flex-start; 
      min-height: 100vh;
      overflow-x: hidden;
      transition: all 0.3s ease;
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
      padding: 100px 10px 80px 10px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      border-radius: 0 20px 20px 0;
      background: linear-gradient(to right, rgba(223,231,249,0.784), rgba(94,217,254,0.526), rgba(223,231,249,0.784));
      background-size: 400% 400%;
      transition: all 0.4s ease;
      z-index: 1001;
    }

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
    .sidebar .logo p {
      font-weight: bold;
      font-size: 22px;
      padding-top: 20px;
    }

    /* 🔹 Navigation */
    .sidebar nav a {
      display: flex; align-items: center; gap: 10px;
      font-size: 17px; padding: 10px 15px;
      margin-bottom: 10px; color: #333;
      font-weight: bold; text-decoration: none;
      border-radius: 8px; transition: background 0.3s ease;
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
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

    /* 🔹 Main Wrapper */
    .main-wrapper {
      flex: 1;
      padding: 30px;
      padding-left: 280px;
      width: 100%;
      transition: padding-left 0.4s ease;
    }

    .main-wrapper.expanded {
      padding-left: 40px;
    }

    /* 🔹 Responsive */
    @media (max-width: 768px) {
      .main-wrapper { padding-left: 60px; }
    }

    @media (max-width: 480px) {
      .sidebar { transform: translateX(-100%); }
      .main-wrapper { padding: 20px; }
    }
  </style>
</head>

<body>

  <!-- 🔘 Tombol Toggle -->
  <button class="toggle-btn" id="toggleSidebar">☰</button>

  <!-- 🌈 Sidebar -->
  <div class="sidebar" id="sidebar">
    <div>
      <div class="logo">
        <img src="{{ asset('image/Logo UIN.png') }}" alt="Logo">
        <p>Kepegawaian</p>
      </div>

      <nav>
        <a href="{{ route('dosen_dashboard') }}" class="{{ request()->routeIs('dosen_dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
        <a href="{{ route('kepegawaian.periode.list') }}" class="{{ request()->routeIs('kepegawaian.periode.list') ? 'active' : '' }}">📆 Periode Pengajuan</a>
        <a href="{{ route('kepegawaian.pengajuan.list') }}" class="{{ request()->routeIs('kepegawaian.pengajuan.list') ? 'active' : '' }}">📋 List Pengajuan</a>
        <a href="{{ route('logout') }}">🚪 Logout</a>
      </nav>
    </div>

    <div class="profile">
      <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Profile">
      <div class="profile-info">
        <span>{{ Auth::user()->name ?? 'Nama User' }}</span>
        <small>{{ Auth::user()->email ?? 'user@email.com' }}</small>
      </div>
    </div>
  </div>

  <!-- 🧩 Main Wrapper -->
  <div class="main-wrapper" id="mainContent">
    @include('Kepegawaian.Components.alert')
    @yield('main-content')
  </div>

  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('closed');
      mainContent.classList.toggle('expanded');
    });
  </script>

</body>
</html>
