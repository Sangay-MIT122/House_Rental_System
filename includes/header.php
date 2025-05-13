<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>House Rental</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS + Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
    }

    body {
      margin: 0;
      padding: 0;
    }

    .navbar-custom {
      background-color: #1e3a5f;
      transition: all 0.3s ease-in-out;
      padding: 18px 0;
    }

    .navbar-shrink {
      padding: 8px 0 !important;
    }

    .navbar-brand img {
      height: 44px;
      transition: all 0.3s ease-in-out;
      margin-right: 10px;
      filter: brightness(0) saturate(100%) invert(88%) sepia(20%) saturate(368%) hue-rotate(130deg) brightness(103%) contrast(97%);
    }

    .navbar-shrink .navbar-brand img {
      height: 30px !important;
    }

    .navbar-brand span {
      font-weight: 700;
      font-size: 1.4rem;
      color: #f8f9fa;
      transition: all 0.3s ease;
    }

    .nav-link {
      color: #e0e6f0 !important;
      font-weight: 500;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      color: #ffffff !important;
      font-weight: 600;
      font-size: 1.05rem;
    }

    .navbar-toggler {
      border-color: #ffffff;
    }

    .navbar-toggler-icon {
      filter: brightness(100);
    }
  </style>
</head>
<body>
<div class="d-flex flex-column min-vh-100"> <!-- Page wrapper starts -->

<nav class="navbar navbar-expand-lg navbar-custom fixed-top" id="mainNavbar">
  <div class="container">
     

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
  <div class="navbar-brand d-flex align-items-center" style="cursor: default;">
    <img src="https://cdn-icons-png.flaticon.com/512/69/69524.png" alt="House Icon">
    <span>RentSys</span>
  </div>
<?php else: ?>
  <a class="navbar-brand d-flex align-items-center" href="<?= str_contains($_SERVER['PHP_SELF'], '/owner/') || str_contains($_SERVER['PHP_SELF'], '/user/')  ? '../index.php' : 'index.php' ?>">
    <img src="https://cdn-icons-png.flaticon.com/512/69/69524.png" alt="House Icon">
    <span>RentSys</span>
  </a>
<?php endif; ?>


    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navBar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navBar">
    <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
      <ul class="navbar-nav me-auto">
  <li class="nav-item">
    <a class="nav-link" 
       href="<?= (str_contains($_SERVER['PHP_SELF'], '/owner/') || str_contains($_SERVER['PHP_SELF'], '/user/') || str_contains($_SERVER['PHP_SELF'], '/admin/')) ? '../index.php#browse' : 'index.php#browse' ?>">
       Browse
    </a>
  </li>
  <li class="nav-item">
  <a class="nav-link" 
     href="<?= (str_contains($_SERVER['PHP_SELF'], '/owner/') || str_contains($_SERVER['PHP_SELF'], '/user/') || str_contains($_SERVER['PHP_SELF'], '/admin/')) ? '../index.php#about' : 'index.php#about' ?>">
     About
  </a>
</li>

<li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
</ul>

<?php endif; ?>

      <ul class="navbar-nav">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="register.php">
              <i class="bi bi-person-plus"></i> Register
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">
              <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
          </li>
        <?php else: ?>
          <?php if ($_SESSION['user_role'] === 'owner'): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= str_contains($_SERVER['PHP_SELF'], '/owner/') ? 'dashboard.php' : 'owner/dashboard.php' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
              </a>
            </li>
          <?php elseif ($_SESSION['user_role'] === 'user'): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= str_contains($_SERVER['PHP_SELF'], '/user/') ? 'dashboard.php' : 'user/dashboard.php' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
              </a>
            </li>
          <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= str_contains($_SERVER['PHP_SELF'], '/admin/') ? 'dashboard.php' : 'admin/dashboard.php' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
              </a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= str_contains($_SERVER['PHP_SELF'], '/owner/') || str_contains($_SERVER['PHP_SELF'], '/user/') || str_contains($_SERVER['PHP_SELF'], '/admin/') ? '../logout.php' : 'logout.php' ?>">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<script>
  const navbar = document.getElementById("mainNavbar");
  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      navbar.classList.add("navbar-shrink");
    } else {
      navbar.classList.remove("navbar-shrink");
    }
  });
</script>

<div style="padding-top: 70px;"><!-- Page content begins -->
