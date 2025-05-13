<?php
include 'includes/db.php';
include 'includes/header.php';

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM properties WHERE id = $id");
$property = $res->fetch_assoc();

if (!$property) {
  echo "<div class='alert alert-danger'>Property not found.</div>";
  include 'includes/footer.php';
  exit;
}

// If form submitted
if (isset($_POST['book']) && isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'user') {
  $uid = $_SESSION['user_id'];

  // Check if this user has already booked this property (any status)
  $check = $conn->query("SELECT id FROM bookings WHERE user_id = $uid AND property_id = $id");
  if ($check && $check->num_rows > 0) {
    header("Location: view-property.php?id=$id&already=1");
    exit;
  } else {
    // Insert booking
    $conn->query("INSERT INTO bookings (user_id, property_id, status) VALUES ($uid, $id, 'pending')");
    
    // Mark property as booked to prevent others
    $conn->query("UPDATE properties SET status = 'booked' WHERE id = $id");
    
    header("Location: view-property.php?id=$id&booked=1");
    exit;
  }
}

// Check if user already booked this property (regardless of status)
$alreadyBooked = false;
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'user') {
  $uid = $_SESSION['user_id'];
  $check = $conn->query("SELECT id FROM bookings WHERE user_id = $uid AND property_id = $id");
  $alreadyBooked = ($check && $check->num_rows > 0);
}
?>

<div class="container mt-4">
  <h2><?= htmlspecialchars($property['title']) ?></h2>
  <img src="uploads/<?= $property['image'] ?>" class="img-fluid mb-3" style="max-height:300px;">
  <p><?= nl2br(htmlspecialchars($property['description'])) ?></p>

  <?php if (isset($_GET['booked'])): ?>
    <div class="alert alert-success">Booking confirmed successfully!</div>
  <?php elseif (isset($_GET['already'])): ?>
    <div class="alert alert-warning">You have already booked this property.</div>
  <?php endif; ?>

  <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'user'): ?>
    <?php if ($alreadyBooked): ?>
      <button class="btn btn-secondary" disabled>Booked</button>
    <?php else: ?>
      <form method="post">
        <button name="book" class="btn btn-success">Book Now</button>
      </form>
    <?php endif; ?>
  <?php else: ?>
    <div class="alert alert-warning">Please login as a user to book.</div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
