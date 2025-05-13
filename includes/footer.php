<?php
$basePath = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/owner/') !== false || strpos($_SERVER['PHP_SELF'], '/user/') !== false) ? '../' : '';
?>

<!-- Bootstrap CSS (ensure it's included in your <head>) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Footer -->
<footer class="bg-dark text-light pt-4 mt-5">
  <div class="container">
    <!-- Developer Info -->
    <div class="row justify-content-center text-center mb-3">
      <h6 class="text-uppercase mb-3">Developed By</h6>
      <div class="d-flex justify-content-center gap-4">
        <!-- Dev 1 -->
        <div class="text-center">
          <img src="<?= $basePath ?>uploads/girl1.jpg" alt="Dev 987827" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
          <p class="small mt-2 mb-0 text-white-50">987827</p>
        </div>
        <!-- Dev 2 -->
        <div class="text-center">
          <img src="<?= $basePath ?>uploads/girl2.jpg" alt="Dev 987829" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
          <p class="small mt-2 mb-0 text-white-50">987829</p>
        </div>
        <!-- Dev 3 -->
        <div class="text-center">
          <img src="<?= $basePath ?>uploads/girl3.jpg" alt="Dev 987676" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
          <p class="small mt-2 mb-0 text-white-50">987676</p>
        </div>
      </div>
    </div>

    <hr class="border-secondary" />

    <!-- Bottom Row -->
    <div class="row text-center text-md-start align-items-center pb-3">
      <div class="col-md-6 mb-3 mb-md-0">
        <p class="mb-0 small">&copy; <?= date('Y') ?> RentSys. All rights reserved.</p>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
        <a href="#" class="text-light me-3"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
        <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
      </div>
    </div>
  </div>
</footer>
