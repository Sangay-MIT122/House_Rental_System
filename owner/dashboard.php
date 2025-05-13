<?php
include '../includes/db.php';
include '../includes/header.php';
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'owner') {
    header("Location: ../login.php");
    exit;
}
$owner_id = $_SESSION['user_id'];

// Handle booking status
if (isset($_GET['booking']) && isset($_GET['action'])) {
    $status = $_GET['action'] === 'accept' ? 'accepted' : 'rejected';
    $booking_id = intval($_GET['booking']);
    $conn->query("UPDATE bookings SET status = '$status' WHERE id = $booking_id");
    if ($status === 'accepted') {
        $result = $conn->query("SELECT property_id FROM bookings WHERE id = $booking_id");
        if ($result && $row = $result->fetch_assoc()) {
            $conn->query("UPDATE properties SET status = 'booked' WHERE id = " . intval($row['property_id']));
        }
    }
}

// Add new property
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_property'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $address = $_POST['address'];
    $bathroom = $_POST['bathroom'];
    $bedroom = $_POST['bedroom'];
    $garage = $_POST['garage'];
    $rent = $_POST['rent'];
    $duration = $_POST['lease_duration'];
    $img = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img);

    $stmt = $conn->prepare("INSERT INTO properties (title, description, image, owner_id, address, bathroom, bedroom, garage, rent, lease_duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisssiis", $title, $desc, $img, $owner_id, $address, $bathroom, $bedroom, $garage, $rent, $duration);
    $stmt->execute();
}

// Edit property
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_property'])) {
    $id = $_POST['property_id'];
    $title = $_POST['edit_title'];
    $desc = $_POST['edit_description'];
    $address = $_POST['edit_address'];
    $bathroom = $_POST['edit_bathroom'];
    $bedroom = $_POST['edit_bedroom'];
    $garage = $_POST['edit_garage'];
    $rent = $_POST['edit_rent'];
    $duration = $_POST['edit_duration'];

    if ($_FILES['edit_image']['name']) {
        $img = $_FILES['edit_image']['name'];
        move_uploaded_file($_FILES['edit_image']['tmp_name'], "../uploads/" . $img);
        $stmt = $conn->prepare("UPDATE properties SET title=?, description=?, image=?, address=?, bathroom=?, bedroom=?, garage=?, rent=?, lease_duration=? WHERE id=? AND owner_id=?");
        $stmt->bind_param("ssssiiisiii", $title, $desc, $img, $address, $bathroom, $bedroom, $garage, $rent, $duration, $id, $owner_id);
    } else {
        $stmt = $conn->prepare("UPDATE properties SET title=?, description=?, address=?, bathroom=?, bedroom=?, garage=?, rent=?, lease_duration=? WHERE id=? AND owner_id=?");
        $stmt->bind_param("sssiiisiii", $title, $desc, $address, $bathroom, $bedroom, $garage, $rent, $duration, $id, $owner_id);
    }
    $stmt->execute();
}

// Delete property
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_property'])) {
    $id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM properties WHERE id=$id AND owner_id=$owner_id");
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.layout-wrapper { margin-left: 220px; padding: 100px 20px; }
.sidebar {
    width: 220px; background-color: rgb(77, 110, 96); color: white;
    position: fixed; top: 60px; bottom: 0; left: 0; padding: 20px 15px; overflow-y: auto;
}
.sidebar a { color: #e0e6f0; display: block; padding: 10px; text-decoration: none; }
.sidebar a:hover { background-color: rgb(2, 10, 19); color: white; }
</style>

<div class="layout-wrapper">
  <div class="sidebar">
    <h4>🏠 Owner Panel</h4>
    <a href="#addProperty">➕ Add Property</a>
    <a href="#yourProperties">🏘 Your Properties</a>
    <a href="#bookings">📥 Bookings Received</a>
  </div>

  <div class="main-content container">
    <!-- Add Property -->
    <section id="addProperty">
      <h3>Add New Property</h3>
      <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow border">
        <input type="hidden" name="add_property" value="1" />
        <div class="row g-3">
          <div class="col-md-6"><input name="title" class="form-control" placeholder="Title" required></div>
          <div class="col-md-6"><input name="address" class="form-control" placeholder="Address" required></div>
          <div class="col-12"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
          <div class="col-md-4"><input name="bathroom" type="number" class="form-control" placeholder="Bathrooms" required></div>
          <div class="col-md-4"><input name="bedroom" type="number" class="form-control" placeholder="Bedrooms" required></div>
          <div class="col-md-4"><input name="garage" type="number" class="form-control" placeholder="Garage"></div>
          <div class="col-md-6"><input name="rent" type="number" class="form-control" placeholder="Rent ($)" required></div>
          <div class="col-md-6"><input name="lease_duration" type="number" class="form-control" placeholder="Lease Duration (months)" required></div>
          <div class="col-12"><input type="file" name="image" class="form-control" required></div>
        </div>
        <div class="mt-3 text-end">
          <button type="reset" class="btn btn-secondary">Reset</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </section>

    <!-- Your Properties Section -->
    <section id="yourProperties" class="mt-5">
      <h3>Your Properties</h3>
      <div class="row g-4">
      <?php
      $props = $conn->query("SELECT * FROM properties WHERE owner_id = $owner_id");
      while ($row = $props->fetch_assoc()):
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="card shadow-sm h-100">
            <img src="../uploads/<?= $row['image'] ?>" class="card-img-top" alt="Image" style="height:200px; object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
              <p class="card-text text-muted"><?= htmlspecialchars($row['address']) ?></p>
              <p><strong>Rent:</strong> $<?= $row['rent'] ?> | <strong>Status:</strong> <?= ucfirst($row['status']) ?></p>
              <div class="d-flex justify-content-between">
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <form method="post" enctype="multipart/form-data" class="modal-content">
              <input type="hidden" name="update_property" value="1">
              <input type="hidden" name="property_id" value="<?= $row['id'] ?>">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Property</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input name="edit_title" class="form-control mb-2" value="<?= htmlspecialchars($row['title']) ?>" required>
                <input name="edit_address" class="form-control mb-2" value="<?= htmlspecialchars($row['address']) ?>" required>
                <textarea name="edit_description" class="form-control mb-2"><?= htmlspecialchars($row['description']) ?></textarea>
                <div class="row g-2">
                  <div class="col"><input name="edit_bathroom" type="number" class="form-control" value="<?= $row['bathroom'] ?>" required></div>
                  <div class="col"><input name="edit_bedroom" type="number" class="form-control" value="<?= $row['bedroom'] ?>" required></div>
                  <div class="col"><input name="edit_garage" type="number" class="form-control" value="<?= $row['garage'] ?>"></div>
                </div>
                <input name="edit_rent" type="number" class="form-control my-2" value="<?= $row['rent'] ?>" required>
                <input name="edit_duration" type="number" class="form-control mb-2" value="<?= $row['lease_duration'] ?>" required>
                <label>Replace Image:</label>
                <input type="file" name="edit_image" class="form-control">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <form method="post" class="modal-content">
              <input type="hidden" name="delete_property" value="1">
              <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body bg-danger text-white">
                Are you sure you want to delete <strong><?= htmlspecialchars($row['title']) ?></strong>?
              </div>
              <div class="modal-footer bg-danger">
                <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-light">Yes, Delete</button>
              </div>
            </form>
          </div>
        </div>

      <?php endwhile; ?>
      </div>
    </section>

    <!-- Bookings Section -->
    <section id="bookings" class="mt-5">
      <h3>Bookings Received</h3>
      <table class="table table-bordered bg-white">
        <thead><tr><th>User</th><th>Property</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        <?php
        $bookings = $conn->query("SELECT b.id, b.status, u.name AS user_name, p.title AS property_title FROM bookings b JOIN users u ON b.user_id = u.id JOIN properties p ON b.property_id = p.id WHERE p.owner_id = $owner_id");
        while ($b = $bookings->fetch_assoc()):
        ?>
        <tr>
          <td><?= htmlspecialchars($b['user_name']) ?></td>
          <td><?= htmlspecialchars($b['property_title']) ?></td>
          <td><?= ucfirst($b['status']) ?></td>
          <td>
            <?php if ($b['status'] === 'pending'): ?>
              <a href="?booking=<?= $b['id'] ?>&action=accept" class="btn btn-sm btn-success">Accept</a>
              <a href="?booking=<?= $b['id'] ?>&action=reject" class="btn btn-sm btn-danger">Reject</a>
            <?php else: ?>
              <span class="text-muted">No actions</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </section>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
