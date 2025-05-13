<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_now']) && isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'user') {
    $uid = $_SESSION['user_id'];
    $property_id = intval($_POST['property_id']);
    $conn->query("INSERT INTO bookings (user_id, property_id) VALUES ($uid, $property_id)");
    header("Location: index.php?booked=success");
} else {
    header("Location: login.php");
}
?>
