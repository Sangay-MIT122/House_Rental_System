<?php
include 'includes/db.php';
include 'includes/header.php';

$errors = ['name' => '', 'email' => '', 'phone' => '', 'password' => '', 'confirm' => '', 'role' => '', 'general' => ''];
$values = ['name' => '', 'email' => '', 'phone' => '', 'role' => ''];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $values['name']  = trim($_POST['name']);
  $values['email'] = trim($_POST['email']);
  $values['phone'] = trim($_POST['phone']);
  $password        = $_POST['password'];
  $confirm         = $_POST['confirm_password'];
  $values['role']  = isset($_POST['role']) ? $_POST['role'] : '';

  if (empty($values['name'])) $errors['name'] = "Name is required.";
  if (empty($values['email']) || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email required.";
  if (empty($values['phone'])) $errors['phone'] = "Phone number is required.";
  if (empty($password)) $errors['password'] = "Password is required.";
  if ($password !== $confirm) $errors['confirm'] = "Passwords do not match.";
  if (empty($values['role'])) $errors['role'] = "Please select a role.";

  if (!$errors['email']) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $values['email']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $errors['email'] = "Email is already registered.";
    }
  }

  if (!array_filter($errors)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $values['name'], $values['email'], $values['phone'], $hash, $values['role']);
    if ($stmt->execute()) {
      $success = "<div id='successMsg' class='alert alert-success text-center mt-4'>Registration successful! Redirecting to login...</div>";
      echo "<script>
        setTimeout(() => {
          const msg = document.getElementById('successMsg');
          if (msg) msg.style.opacity = '0';
        }, 1800);
        setTimeout(() => {
          window.location = 'login.php';
        }, 2000);
      </script>";
    } else {
      $errors['general'] = "Registration failed. Please try again.";
    }
  }
}
?>

<style>
  html, body {
    height: 100%;
  }
</style>

<div class="container mt-5 mb-5 d-flex align-items-center justify-content-center flex-grow-1">
  <div class="card shadow border w-100" style="max-width: 600px;">
    <div class="card-body">
      <h2 class="mb-4 text-center">Register</h2>
      <?php if ($errors['general']): ?>
        <div class="alert alert-danger"><?= $errors['general'] ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-2">
          <input class="form-control" name="name" placeholder="Full Name" value="<?= htmlspecialchars($values['name']) ?>">
          <?php if ($errors['name']): ?><div class="text-danger"><?= $errors['name'] ?></div><?php endif; ?>
        </div>
        <div class="mb-2">
          <input class="form-control" name="email" type="email" placeholder="Email" value="<?= htmlspecialchars($values['email']) ?>">
          <?php if ($errors['email']): ?><div class="text-danger"><?= $errors['email'] ?></div><?php endif; ?>
        </div>
        <div class="mb-2">
          <input class="form-control" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($values['phone']) ?>">
          <?php if ($errors['phone']): ?><div class="text-danger"><?= $errors['phone'] ?></div><?php endif; ?>
        </div>
        <div class="mb-2">
          <input class="form-control" name="password" type="password" placeholder="Password">
          <?php if ($errors['password']): ?><div class="text-danger"><?= $errors['password'] ?></div><?php endif; ?>
        </div>
        <div class="mb-2">
          <input class="form-control" name="confirm_password" type="password" placeholder="Confirm Password">
          <?php if ($errors['confirm']): ?><div class="text-danger"><?= $errors['confirm'] ?></div><?php endif; ?>
        </div>
        <div class="mb-2">
          <select class="form-control" name="role">
            <option value="" disabled <?= $values['role'] === '' ? 'selected' : '' ?>>Select Role</option>
            <option value="user" <?= $values['role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="owner" <?= $values['role'] === 'owner' ? 'selected' : '' ?>>Owner</option>
          </select>
          <?php if ($errors['role']): ?><div class="text-danger"><?= $errors['role'] ?></div><?php endif; ?>
        </div>
        <button class="btn btn-primary w-100">Register</button>
      </form>

      <?= $success ?>

      <div class="text-center mt-4">
        <small class="text-muted">Already have an account?</small>
        <a href="login.php" class="d-block mt-1">Login here</a>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
