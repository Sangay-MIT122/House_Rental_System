<?php include 'includes/db.php'; include 'includes/header.php'; ?>

<h2>Available Houses</h2>
<div class="row">
<?php
$res = $conn->query("SELECT * FROM properties WHERE status = 'available'");
while ($row = $res->fetch_assoc()):
?>
  <div class="col-md-4 mb-4">
    <div class="card">
      <img src="uploads/<?= $row['image'] ?>" class="card-img-top" style="height:200px; object-fit:cover;">
      <div class="card-body">
        <h5 class="card-title"><?= $row['title'] ?></h5>
        <p><?= substr($row['description'], 0, 100) ?>...</p>
        <a href="view-property.php?id=<?= $row['id'] ?>" class="btn btn-primary">View</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
