<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Dashboard Komite')</title>

  <link rel="icon" type="image/png" href="{{ asset('image/Logo UIN.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
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
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* TOGGLE */
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
      cursor: pointer;
      z-index: 1002;
      box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }

    /* SIDEBAR */
    .sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      padding: 100px 10px 80px 10px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-radius: 0 20px 20px 0;
      background: linear-gradient(
        to right,
        rgba(223,231,249,0.78),
        rgba(94,217,254,0.52),
        rgba(223,231,249,0.78)
      );
      transition: all 0.4s ease;
      z-index: 1001;
    }

    .sidebar.collapsed {
      transform: translateX(-100%);
      opacity: 0;
    }

    /* LOGO */
    .logo {
      text-align: center;
      margin-bottom: 40px;
    }

    .logo img {
      width: 140px;
    }

    .logo p {
      font-size: 22px;
      font-weight: 700;
      margin-top: 15px;
    }

    /* NAV */
    nav a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 15px;
      margin-bottom: 10px;
      font-size: 16px;
      font-weight: 600;
      color: #333;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
    }

    nav a:hover,
    nav a.active {
      background: #ffffff;
      font-weight: 700;
    }

    nav svg {
      width: 20px;
      height: 20px;
    }

    /* PROFILE */
    .profile {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0 10px;
    }

    .profile svg {
      width: 42px;
      height: 42px;
    }

    .profile-info span {
      font-size: 14px;
      font-weight: 600;
      display: block;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 170px;
    }

    .profile-info small {
      font-size: 12px;
      color: #555;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 170px;
    }

    /* MAIN */
    .main-content {
      flex: 1;
      padding: 30px;
      padding-left: 280px;
      width: 100%;
      transition: padding-left 0.4s ease;
    }

    .main-content.expanded {
      padding-left: 40px;
    }
  </style>
</head>

<body>

<button class="toggle-btn" id="toggleSidebar">☰</button>

<div class="sidebar" id="sidebar">
  <div>
    <div class="logo">
      <img src="{{ asset('image/Logo UIN.png') }}">
      <p>Komite Integritas</p>
    </div>

    <nav>
      <a href="{{ route('comite_dashboard') }}"
         class="{{ request()->routeIs('comite_dashboard') ? 'active' : '' }}">
        🏠 Dashboard
      </a>

      <a href="{{ route('comite.pengajuan.list') }}"
         class="{{ request()->routeIs('comite.pengajuan.*') ? 'active' : '' }}">
        📋 List Pengajuan
      </a>

      <a href="{{ route('logout') }}">🚪 Logout</a>
    </nav>
  </div>

  <div class="profile">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="black">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
    </svg>
    <div class="profile-info">
      <span>{{ Auth::user()->name }}</span>
      <small>{{ Auth::user()->email }}</small>
    </div>
  </div>
</div>

<div class="main-content" id="mainContent">
  @include('Comite.Components.alert')
  @yield('main-content')
</div>

<script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  });
</script>

</body>
</html>
