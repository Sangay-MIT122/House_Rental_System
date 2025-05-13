<?php
include 'includes/db.php';
include 'includes/header.php';

$email = $password = "";
$errors = ['login' => ''];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($user = $res->fetch_assoc()) {
    if (
      ($user['role'] === 'admin' && $password === $user['password']) ||
      ($user['role'] !== 'admin' && password_verify($password, $user['password']))
    ) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_role'] = $user['role'];

      $success = "<div id='successMsg' class='alert alert-success text-center mt-4'>Login successful! Redirecting...</div>";
      echo "<script>
        setTimeout(() => {
          const msg = document.getElementById('successMsg');
          if (msg) msg.style.opacity = '0';
        }, 1800);
        setTimeout(() => {
          window.location = '" . 
            ($user['role'] === 'admin' ? "admin/dashboard.php" :
            ($user['role'] === 'owner' ? "owner/dashboard.php" : "user/dashboard.php")) . 
          "';
        }, 900);
      </script>";
    } else {
      $errors['login'] = "Invalid credentials.";
    }
  } else {
    $errors['login'] = "Invalid credentials.";
  }
}
?>

<div class="container mt-5 mb-5 d-flex align-items-center justify-content-center flex-grow-1">
  <div class="card shadow border w-100" style="max-width: 500px;">
    <div class="card-body">
      <h2 class="mb-4 text-center">Login</h2>
      
      <?php if ($errors['login']): ?>
        <div class="alert alert-danger"><?= $errors['login'] ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <input class="form-control" name="email" type="email" placeholder="Email" required value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="mb-3">
          <input class="form-control" name="password" type="password" placeholder="Password" required>
        </div>
        <button class="btn btn-success w-100">Login</button>
      </form>

      <?= $success ?>

      <div class="text-center mt-4">
        <small class="text-muted">Don't have an account?</small>
        <a href="register.php" class="d-block mt-1">Register here</a>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
