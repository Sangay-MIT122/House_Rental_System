<?php
include '../includes/db.php';
include '../includes/header.php';
if ($_SESSION['user_role'] !== 'admin') die(header("Location: ../login.php"));

$toastMessage = '';
$toastType = '';

if (isset($_POST['confirm_delete_user'])) {
    $id = intval($_POST['user_id']);

    $roleRes = $conn->query("SELECT role FROM users WHERE id = $id");
    if ($roleRes && $role = $roleRes->fetch_assoc()) {
        if ($role['role'] === 'owner') {
            $conn->query("DELETE FROM properties WHERE owner_id = $id");
        } elseif ($role['role'] === 'user') {
            $conn->query("DELETE FROM bookings WHERE user_id = $id");
        }
    }
    $conn->query("DELETE FROM users WHERE id = $id");
    $toastMessage = "User deleted successfully.";
    $toastType = 'success';
}

if (isset($_POST['confirm_delete_property'])) {
    $pid = intval($_POST['property_id']);
    $conn->query("DELETE FROM properties WHERE id = $pid");
    $toastMessage = "Property deleted successfully.";
    $toastType = 'success';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .toast-box {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 15px 20px;
      background-color: #198754;
      color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInOut 4s ease-in-out forwards;
      z-index: 9999;
    }

    .toast-box.error { background-color: #dc3545; }
    .toast-box.warning { background-color: #ffc107; color: black; }

    @keyframes fadeInOut {
      0% { opacity: 0; transform: translateY(20px); }
      10%, 90% { opacity: 1; transform: translateY(0); }
      100% { opacity: 0; transform: translateY(20px); }
    }
  </style>
</head>
<body>

<div class="container py-5">
  <h2>Admin Dashboard</h2>

  <h4 class="mt-4">👤 Users</h4>
  <table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
      <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php
      $admin_id = $_SESSION['user_id'];
      $users = $conn->query("SELECT * FROM users WHERE id != $admin_id");
      while ($u = $users->fetch_assoc()):
      ?>
        <tr>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= ucfirst($u['role']) ?></td>
          <td>
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmUserDeleteModal" onclick="setUserId(<?= $u['id'] ?>)">Delete</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h4 class="mt-5">🏠 Properties</h4>
  <table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
      <tr><th>Title</th><th>Owner</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php
      $posts = $conn->query("SELECT p.id, p.title, u.name FROM properties p JOIN users u ON p.owner_id = u.id");
      while ($p = $posts->fetch_assoc()):
      ?>
        <tr>
          <td><?= htmlspecialchars($p['title']) ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td>
            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmPostDeleteModal" onclick="setPropertyId(<?= $p['id'] ?>)">Delete</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- User Delete Modal -->
<div class="modal fade" id="confirmUserDeleteModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Delete User</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this user and all their associated data?
        <input type="hidden" name="user_id" id="deleteUserId">
      </div>
      <div class="modal-footer">
        <button type="submit" name="confirm_delete_user" class="btn btn-danger">Yes, Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Property Delete Modal -->
<div class="modal fade" id="confirmPostDeleteModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Delete Property</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this property?
        <input type="hidden" name="property_id" id="deletePropertyId">
      </div>
      <div class="modal-footer">
        <button type="submit" name="confirm_delete_property" class="btn btn-danger">Yes, Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Toast -->
<?php if (!empty($toastMessage)): ?>
  <div class="toast-box <?= $toastType ?>">
    <?= htmlspecialchars($toastMessage) ?>
  </div>
<?php endif; ?>

<script>
  function setUserId(id) {
    document.getElementById('deleteUserId').value = id;
  }
  function setPropertyId(id) {
    document.getElementById('deletePropertyId').value = id;
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
