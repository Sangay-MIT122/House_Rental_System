<?php
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    header("Location: ../login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$res = $conn->query("SELECT b.id, p.title, b.status, p.image FROM bookings b JOIN properties p ON b.property_id = p.id WHERE b.user_id = $uid");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold text-primary"><i class="bi bi-house-door"></i> Your Bookings</h2>
    <p class="text-muted">Below are the properties you have booked.</p>
  </div>

  <div class="row g-4">
    <?php if ($res->num_rows > 0): ?>
      <?php while ($row = $res->fetch_assoc()): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card shadow-sm h-100">
            <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Property Image">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
              <p><strong>Status:</strong> 
                <?php if ($row['status'] == 'pending'): ?>
                  <span class="badge bg-warning text-dark">Pending</span>
                <?php elseif ($row['status'] == 'accepted'): ?>
                  <span class="badge bg-success">Accepted</span>
                <?php elseif ($row['status'] == 'rejected'): ?>
                  <span class="badge bg-danger">Rejected</span>
                <?php endif; ?>
              </p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <div class="alert alert-info">
          <i class="bi bi-info-circle"></i> You have not booked any properties yet.
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include '../includes/footer.php'; ?>
