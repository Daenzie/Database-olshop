<?php
include 'includes/db.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Cek apakah produk sudah ada di cart
$cek = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$cek->bind_param("ii", $user_id, $product_id);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows > 0) {
    // Kalau sudah ada, tambahkan quantity
    $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
} else {
    // Kalau belum, insert baru
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit;
?>
