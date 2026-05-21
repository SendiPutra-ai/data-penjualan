<?php
session_start();
if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"]) {
    header("Location: menu.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Data Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      background: rgba(255,255,255,0.97);
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.4);
      padding: 40px 36px;
      width: 100%;
      max-width: 400px;
    }
    .login-title {
      font-size: 1.6rem;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 4px;
    }
    .login-subtitle {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 28px;
    }
    .form-control:focus {
      border-color: #0f3460;
      box-shadow: 0 0 0 0.2rem rgba(15,52,96,0.2);
    }
    .btn-login {
      background: #0f3460;
      border: none;
      border-radius: 8px;
      padding: 10px;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: background 0.2s;
    }
    .btn-login:hover {
      background: #16213e;
    }
    .form-label {
      font-weight: 600;
      color: #343a40;
      font-size: 0.88rem;
    }
    .form-control {
      border-radius: 8px;
      padding: 10px 14px;
      border: 1.5px solid #dee2e6;
    }
    .alert {
      border-radius: 8px;
      font-size: 0.88rem;
    }
    .brand-icon {
      width: 48px;
      height: 48px;
      background: #0f3460;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="brand-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="white" viewBox="0 0 16 16">
        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.029 10 8 10c-2.029 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
      </svg>
    </div>
    <div class="login-title">Selamat Datang</div>
    <div class="login-subtitle">Silakan masuk ke akun Anda</div>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger" role="alert">
        Email atau password salah. Silakan coba lagi.
      </div>
    <?php endif; ?>

    <form action="login.php" method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan email" required autocomplete="off">
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
      </div>
      <button type="submit" class="btn btn-login btn-primary w-100 text-white">Masuk</button>
    </form>

    <div class="text-center mt-3" style="font-size:0.82rem;color:#adb5bd;">
      Default: admin@gmail.com / 123456
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>