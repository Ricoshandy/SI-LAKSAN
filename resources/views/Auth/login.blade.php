<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SI LAKSAN - Login</title>
  <link rel="icon" type="image/png" href="/image/Logo UIN.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background-image: url("/image/Background2.png");
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      display: flex;
      justify-content: center;
      align-items: center;
      /* animasi dihapus biar gak ada kilat putih */
    }

    .container {
      width: 1000px;
      height: 555px;
      background: white;
      display: flex;
      border-radius: 20px;
      overflow: hidden;
      position: relative;
      box-shadow: 0px 0px 30px rgba(0,0,0,0.1);
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInContainer 0.8s ease forwards;
    }

    /* Left Panel */
    .left-panel {
      width: 50%;
      background: linear-gradient(270deg, #3272be, #3f95ff, #3272be);
      color: white;
      position: relative;
      padding: 60px 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      animation: bgPulse 6s ease-in-out infinite;
    }

    .left-panel .content {
      z-index: 2;
      position: relative;
    }

    .left-panel .logo {
      width: 170px;
      margin-bottom: 25px;
    }

    .left-panel h1 {
      font-size: 32px;
      margin-bottom: 10px;
    }

    .left-panel p {
      font-size: 14px;
      line-height: 1.6;
    }

    .footer-links {
      position: absolute;
      bottom: 20px;
      width: 100%;
      text-align: center;
      font-size: 12px;
      z-index: 2;
    }

    .footer-links a {
      color: white;
      text-decoration: underline;
    }

    /* Wave */
    .wave {
      position: absolute;
      right: -1px;
      top: 0;
      height: 100%;
      width: 100px;
      z-index: 1;
      animation: moveWave 6s ease-in-out infinite;
    }

    /* Right Panel */
    .right-panel {
      width: 50%;
      background: white;
      padding: 60px 40px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form {
      width: 100%;
    }

    .form h2 {
      color: #0d47a1;
      font-weight: 600;
      margin-bottom: 30px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-bottom: 2px solid #ccc;
      font-size: 14px;
      background: none;
      outline: none;
      transition: all 0.3s ease;
    }

    input:focus {
      border-bottom: 2px solid #0d47a1;
    }

    .terms {
      margin: 15px 0;
      font-size: 12px;
    }

    .terms input {
      margin-right: 5px;
    }

    .terms a {
      color: #0d47a1;
      text-decoration: underline;
    }

    .buttons {
      margin-top: 20px;
      display: flex;
      gap: 15px;
    }

    .btn-primary {
      background: #0d47a1;
      border: none;
      color: white;
      padding: 10px 25px;
      border-radius: 30px;
      font-weight: bold;
      cursor: pointer;
      transition: background 1s, transform 1s;
    }

    .btn-primary:hover {
      background: #1565c0;
      transform: translateY(-2px);
    }

    /* Animation */
    @keyframes moveWave {
      0% { transform: translateX(0px); }
      50% { transform: translateX(10px); }
      100% { transform: translateX(0px); }
    }

    @keyframes fadeInContainer {
      0% { opacity: 0; transform: translateY(40px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes bgPulse {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        height: auto;
        width: 90%;
      }

      .left-panel, .right-panel {
        width: 100%;
        height: auto;
        text-align: center;
      }

      .wave {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="container">

    <!-- LEFT PANEL -->
    <div class="left-panel">
      <div class="content">
        <img src="/image/Logo UIN.png" alt="Logo UIN" class="logo">
        <h1>SI LAKSAN</h1>
        <p>Sistem Informasi Layanan Akses Pengajuan <br>Jabatan Dosen UIN Raden Fatah Palembang</p>
      </div>
      <svg class="wave" viewBox="0 0 500 500" preserveAspectRatio="none">
        <path d="M0,0 Q250,100 500,0 L500,500 L0,500 Z" style="stroke: none; fill: #ffffff;"></path>
      </svg>
      <div class="footer-links">
        <a href="https://ricoshandymahendra.vercel.app">Developer</a> | <a href="#">Contact Here</a>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
      <form class="form" action="{{ route('login') }}" method="POST">
        @csrf
        <h2>Silahkan Login Akun Anda</h2>

        <input type="text" name="email" placeholder="NIP" required>
        <input type="password" name="password" placeholder="Password" required>

        <div class="terms">
          <input type="checkbox" id="terms" required>
          <label for="terms">Saya setuju dengan <a href="#">Terms & Conditions</a></label>
        </div>

        <div class="buttons">
          <button type="submit" class="btn-primary">Login</button>
        </div>
      </form>
    </div>

  </div>
</body>
</html>
