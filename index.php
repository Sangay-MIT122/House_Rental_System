<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>House Rental System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
  html {
    scroll-behavior: smooth;
  }

  .hero-slide {
    position: relative;
    height: 85vh;
    overflow: hidden;
  }

  .hero-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(50%);
  }

  .carousel-item {
    position: relative; /* Important for caption alignment */
  }

  .carousel-caption {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-15%, -30%);
  z-index: 10;
  text-align: center;
  color: #fff;
  width: 100%;
  padding: 0 15px;
}


  .carousel-caption h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
  }

  .btn-explore {
    font-size: 1.1rem !important;
    padding: 14px 36px !important;
    border-radius: 50px !important;
    background-color:rgb(12, 57, 126) !important;
    color: #fff !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(51, 5, 5, 0.2) !important;
    transition: all 0.3s ease-in-out !important;
  }

  .btn-explore:hover {
    background-color:rgb(17, 40, 73) !important;
    transform: scale(1.08) !important;
  }

  [id] {
    scroll-margin-top: 200px;
  }

  #contact a:hover {
    color: #007bff !important;
    transform: scale(1.1);
    transition: 0.3s ease-in-out;
  }

  #about ul i {
    transition: transform 0.3s ease;
  }

  #about ul li:hover i {
    transform: scale(1.2);
  }
</style>


 
</head>
<body>

<!-- Hero Section -->
<div id="homeCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
  <div class="carousel-inner">
    <!-- Slide 1 -->
    <div class="carousel-item active hero-slide">
      <img src="https://images.pexels.com/photos/186077/pexels-photo-186077.jpeg" alt="Slide 1" class="d-block w-100">
      <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
        <h1 class="text-white fw-bold">Live Beautifully</h1>
        <p class="text-light">Find rentals that feel like home.</p>
        <a href="#browse" class="btn btn-explore mt-3">Explore Now</a>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item hero-slide">
      <img src="https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg" alt="Slide 2" class="d-block w-100">
      <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
        <h1 class="text-white fw-bold">Modern Apartments & Villas</h1>
        <p class="text-light">Browse properties that fit your lifestyle.</p>
        <a href="#browse" class="btn btn-explore mt-3">Explore Now</a>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-item hero-slide">
      <img src="https://images.pexels.com/photos/280229/pexels-photo-280229.jpeg" alt="Slide 3" class="d-block w-100">
      <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
        <h1 class="text-white fw-bold">Simple, Secure Bookings</h1>
        <p class="text-light">Book and manage rentals easily online.</p>
        <a href="#browse" class="btn btn-explore mt-3">Explore Now</a>
      </div>
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>


<!-- Browse Section -->
<section id="browse" class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center">Available Rentals</h2>
    <div class="row">
      <?php
      $res = $conn->query("SELECT * FROM properties WHERE status='available'");
      while ($row = $res->fetch_assoc()):
        $pid = $row['id'];
      ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="uploads/<?= $row['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
            <p class="card-text"><?= substr(htmlspecialchars($row['description']), 0, 100) ?>...</p>
            <button class="btn btn-outline-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modal<?= $pid ?>">View</button>
          </div>
        </div>
      </div>


      <!-- Modal -->
      <div class="modal fade" id="modal<?= $pid ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title"><?= htmlspecialchars($row['title']) ?></h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <img src="uploads/<?= $row['image'] ?>" class="img-fluid mb-3" style="max-height:300px;">
              <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
              <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
              <p><strong>Rent:</strong> $<?= $row['rent'] ?> /month</p>
              <p><strong>Bedrooms:</strong> <?= $row['bedroom'] ?> | <strong>Bathrooms:</strong> <?= $row['bathroom'] ?> | <strong>Garage:</strong> <?= $row['garage'] ?></p>
            </div>
            <div class="modal-footer">
  <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
    <form method="post" action="book-property.php">
      <input type="hidden" name="property_id" value="<?= $pid ?>">
      <button type="submit" name="book_now" class="btn btn-success">Book Now</button>
    </form>
  <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'owner'): ?>
    <button class="btn btn-secondary" disabled>Login as user to book</button>
  <?php else: ?>
    <a href="login.php" class="btn btn-warning">Login to Book</a>
    <a href="register.php" class="btn btn-secondary">Register</a>
  <?php endif; ?>
  
  <button class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
</div>

          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- About Us Section -->
<section id="about" class="py-5" style="background: linear-gradient(135deg, #f3f6fa, #ffffff);">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold text-primary">Why Choose RentSys?</h2>
      <p class="text-muted fs-5 mb-0">Your trusted platform for rental property management and booking.</p>
    </div>

    <div class="row justify-content-center align-items-center g-3">
      <div class="col-lg-5 text-center mb-4 mb-lg-0">
        <img src="uploads/about.jpg" alt="About RentSys" class="img-fluid rounded shadow" style="max-height: 320px;">
      </div>

      <div class="col-lg-6">
        <div class="d-flex flex-column gap-3">
          <!-- Feature 1 -->
          <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
              <i class="bi bi-house-door fs-5"></i>
            </div>
            <div>
              <h6 class="mb-1">Easy Property Listing</h6>
              <p class="text-muted mb-0 small">Owners can list homes in minutes and manage them easily.</p>
            </div>
          </div>

          <!-- Feature 2 -->
          <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
              <i class="bi bi-calendar-check fs-5"></i>
            </div>
            <div>
              <h6 class="mb-1">Smart Booking System</h6>
              <p class="text-muted mb-0 small">Users can book and track rentals instantly and securely.</p>
            </div>
          </div>

          <!-- Feature 3 -->
          <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm">
            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
              <i class="bi bi-speedometer2 fs-5"></i>
            </div>
            <div>
              <h6 class="mb-1">Professional Dashboard</h6>
              <p class="text-muted mb-0 small">Real-time control of all activity from one dashboard.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5" style="background: #f8f9fb;">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold text-primary">Get in Touch</h2>
      <p class="text-muted mb-0">Connect with RentSys on your favorite platforms or send us an email anytime.</p>
    </div>

    <div class="row justify-content-center g-3">
      <!-- Email -->
      <div class="col-md-4 d-flex align-items-start bg-white p-3 rounded shadow-sm">
        <div class="me-3 text-primary">
          <i class="bi bi-envelope-fill fs-4"></i>
        </div>
        <div>
          <h6 class="mb-1">Email Us</h6>
          <p class="text-muted mb-0 small">support@rentsys.com</p>
        </div>
      </div>

      <!-- Phone -->
      <div class="col-md-4 d-flex align-items-start bg-white p-3 rounded shadow-sm">
        <div class="me-3 text-success">
          <i class="bi bi-telephone-fill fs-4"></i>
        </div>
        <div>
          <h6 class="mb-1">Call Us</h6>
          <p class="text-muted mb-0 small">+61 123 456 789</p>
        </div>
      </div>

      <!-- Location -->
      <div class="col-md-4 d-flex align-items-start bg-white p-3 rounded shadow-sm">
        <div class="me-3 text-danger">
          <i class="bi bi-geo-alt-fill fs-4"></i>
        </div>
        <div>
          <h6 class="mb-1">Office</h6>
          <p class="text-muted mb-0 small">Canberra, ACT, Australia</p>
        </div>
      </div>

      <!-- Social Media -->
      <div class="col-12 text-center mt-4">
        <h6 class="text-secondary">Follow Us</h6>
        <div class="d-flex justify-content-center gap-3 mt-2">
          <a href="https://www.facebook.com" class="text-primary fs-4"><i class="bi bi-facebook"></i></a>
           <a href="https://www.instagram.com" class="text-danger fs-4"><i class="bi bi-instagram"></i></a>
          <a href="https://www.linkedin.com" class="text-dark fs-4"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'includes/footer.php'; ?>
